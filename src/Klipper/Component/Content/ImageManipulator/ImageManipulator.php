<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\ImageManipulator;

use Imagine\Exception\InvalidArgumentException as ImagineInvalidArgumentException;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Klipper\Component\Content\ImageManipulator\Cache\CacheInterface;
use Klipper\Component\Content\ImageManipulator\Exception\InvalidArgumentException;
use Klipper\Component\Content\ImageManipulator\ImageInterface as ManipulatorImageInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ImageManipulator implements ImageManipulatorInterface
{
    public const DEFAULT_AVAILABLE_EXTENSIONS = [
        'BMP',
        'GIF',
        'JPEG',
        'JPG',
        'PNG',
        'WBMP',
        'WEBP',
        'XPM',
    ];

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var string
     */
    protected $tempPath;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string[]
     */
    protected $availableExtensions;

    /**
     * Constructor.
     *
     * @param ImagineInterface $imagine             The imagine engine
     * @param CacheInterface   $cache               The cache
     * @param string           $tempPath            The path of the temporary directory
     * @param array            $options             The options of imagine
     * @param string[]         $availableExtensions The available extensions
     * @param null|Filesystem  $filesystem          The filesystem
     */
    public function __construct(
        ImagineInterface $imagine,
        CacheInterface $cache,
        string $tempPath,
        array $options = [],
        array $availableExtensions = [],
        ?Filesystem $filesystem = null
    ) {
        $this->imagine = $imagine;
        $this->cache = $cache;
        $this->tempPath = $tempPath;
        $this->options = $options;
        $this->filesystem = $filesystem ?? new Filesystem();
        $this->availableExtensions = $this->buildAvailableExtensions($availableExtensions);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(?string $extension): bool
    {
        return \in_array(strtoupper($extension), $this->availableExtensions, true);
    }

    /**
     * {@inheritdoc}
     *
     * @throws
     */
    public function create(string $path, ?ConfigInterface $config = null): ManipulatorImageInterface
    {
        $fs = $this->filesystem;
        $config = $config ?? new Config();

        if (null !== $originalImage = $this->getOriginalImage($config, $path)) {
            return $originalImage;
        }

        if (false !== $cacheImage = $this->cache->get($path, $config)) {
            return $cacheImage;
        }

        $fs->mkdir($this->tempPath);

        $ext = $this->buildExtension($config, $path);
        $tmpName = uniqid('', true);
        $tmpFile = $this->tempPath.'/'.$tmpName.'.'.$ext;
        $stream = @fopen($path, 'r');

        if (false === $stream) {
            throw new FileNotFoundException($path);
        }

        $image = $this->imagine->read($stream);
        $box = $this->buildBox($config, $image);
        $mode = $this->buildMode($config);
        $options = $this->options;

        $image = $image->thumbnail($box, $mode);
        if (\array_key_exists('interlace', $options)) {
            $image = $image->interlace($options['interlace']);
            unset($options['interlace']);
        }

        try {
            $image->save($tmpFile, $options);
        } catch (ImagineInvalidArgumentException $e) {
            if (\is_resource($stream)) {
                @fclose($stream);
            }

            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        if (\is_resource($stream)) {
            @fclose($stream);
        }

        $resource = @fopen($tmpFile, 'r');
        $this->cache->set($path, $config, $resource);
        if (\is_resource($resource)) {
            @fclose($resource);
        }

        $streamResource = @fopen($tmpFile, 'r');

        return new Image($streamResource, mime_content_type($tmpFile), static function () use ($streamResource, $fs, $tmpFile): void {
            if (\is_resource($streamResource)) {
                @fclose($streamResource);
            }

            $fs->remove($tmpFile);
        });
    }

    /**
     * Get the original image resource.
     *
     * @param ConfigInterface $config The config
     * @param string          $path   The image path
     *
     * @throws FileNotFoundException
     */
    protected function getOriginalImage(ConfigInterface $config, string $path): ?Image
    {
        $ext = $this->buildExtension($config, $path);
        $streamImage = null;

        if ($config->getKeepOriginal() && $ext === pathinfo($path, PATHINFO_EXTENSION)
                && ConfigInterface::MODE_CONTAINER === $config->getMode()
                && null === $config->getWidth() && null === $config->getHeight()) {
            $streamResource = @fopen($path, 'r');

            if (false === $streamResource) {
                throw new FileNotFoundException($path);
            }

            $streamImage = new Image($streamResource, mime_content_type($path));
        }

        return $streamImage;
    }

    /**
     * Build the image extension.
     *
     * @param ConfigInterface $config The config
     * @param string          $path   The path
     */
    protected function buildExtension(ConfigInterface $config, string $path): string
    {
        return $config->getExtension() ?? pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Build the image box sizes.
     *
     * @param ConfigInterface $config The config
     * @param ImageInterface  $image  The imagine image
     */
    protected function buildBox(ConfigInterface $config, ImageInterface $image): Box
    {
        $width = $config->getWidth();
        $height = $config->getHeight();
        $scale = $config->getScale();
        $imgWidth = $image->getSize()->getWidth();
        $imgHeight = $image->getSize()->getHeight();

        switch ($config->getMode()) {
            case ConfigInterface::MODE_COVER:
                $width = $width ?? $height;
                $height = $height ?? $width;

                if (null !== $width && null !== $height) {
                    $width = round($width * $scale);
                    $height = round($height * $scale);
                } else {
                    $width = $imgWidth;
                    $height = $imgHeight;
                }

                if ($width > $imgWidth) {
                    $reduction = $imgWidth / $width;
                    $width = $imgWidth;
                    $height = round($height * $reduction);
                }

                if ($height > $imgHeight) {
                    $reduction = $imgHeight / $height;
                    $height = $imgHeight;
                    $width = round($width * $reduction);
                }

                break;
            case ConfigInterface::MODE_CONTAINER:
            default:
                $width = ($width ?? $imgWidth) * $scale;
                $height = ($height ?? $imgHeight) * $scale;
                $width = $width <= $imgWidth ? $width : $imgWidth;
                $height = $height <= $imgHeight ? $height : $imgHeight;

                break;
        }

        return new Box($width, $height);
    }

    /**
     * Build the image mode.
     *
     * @param ConfigInterface $config The config
     */
    protected function buildMode(ConfigInterface $config): string
    {
        return ConfigInterface::MODE_COVER === $config->getMode()
            ? ImageInterface::THUMBNAIL_OUTBOUND
            : ImageInterface::THUMBNAIL_INSET;
    }

    /**
     * Build the available extensions.
     *
     * @param string[] $availableExtensions The custom available extensions
     *
     * @return string[]
     */
    protected function buildAvailableExtensions(array $availableExtensions): array
    {
        if (!empty($availableExtensions)) {
            return $availableExtensions;
        }

        if ($this->imagine instanceof \Imagine\Imagick\Imagine) {
            return \Imagick::queryFormats();
        }

        if ($this->imagine instanceof \Imagine\Gmagick\Imagine) {
            try {
                return (new \Gmagick())->queryformats();
            } catch (\Throwable $e) {
                // do nothing
            }
        }

        return static::DEFAULT_AVAILABLE_EXTENSIONS;
    }
}

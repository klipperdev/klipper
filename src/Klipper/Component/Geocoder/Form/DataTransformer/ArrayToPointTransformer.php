<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Geocoder\Form\DataTransformer;

use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Array to point data transformer.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ArrayToPointTransformer implements DataTransformerInterface
{
    /**
     * Transforms a point into a array.
     *
     * @param null|Point $value The point value
     *
     * @return null|array Array value
     *
     * @throws TransformationFailedException If the given value is not a string
     */
    public function transform($value): ?array
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!$value instanceof Point) {
            throw new TransformationFailedException('Expected a '.Point::class.'.');
        }

        return [
            'x' => $value->getLongitude(),
            'y' => $value->getLatitude(),
        ];
    }

    /**
     * Transforms a array into a point.
     *
     * @param array $value The array value
     *
     * @throws TransformationFailedException If the given value is not a array
     */
    public function reverseTransform($value): ?Point
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        if (!\is_array($value)) {
            throw new TransformationFailedException('Expected a array.');
        }

        if (!isset($value['x'], $value['y'])) {
            throw new TransformationFailedException('Properties "x" and "y" are required.');
        }

        return new Point($value['x'], $value['y']);
    }

    /**
     * Check if the value of reverse transformer is empty.
     *
     * @param mixed $value The value of reverse transformer
     */
    private function isEmpty($value): bool
    {
        return null === $value || empty($value) || (\is_array($value)
            && \array_key_exists('x', $value) && null === $value['x']
            && \array_key_exists('y', $value) && null === $value['y']);
    }
}

<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Serializer;

use JMS\Serializer\Exception\InvalidArgumentException;
use Klipper\Component\Content\Serializer\Type\LangOrgUrl;
use Klipper\Component\Content\Serializer\Type\LangUrl;
use Klipper\Component\Content\Serializer\Type\OrgUrl;
use Klipper\Component\Content\Serializer\Type\Url;
use Klipper\Component\Routing\OrganizationalRoutingInterface;
use Klipper\Component\Routing\RoutingInterface;
use Klipper\Component\Routing\TranslatableRoutingInterface;
use Klipper\Component\RoutingExtra\Routing\PropertyPathMatcherInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UrlGenerator
{
    public const TYPES = [
        'Url' => Url::class,
        'LangUrl' => LangUrl::class,
        'OrgUrl' => OrgUrl::class,
        'LangOrgUrl' => LangOrgUrl::class,
    ];

    /**
     * @var RoutingInterface
     */
    protected $routing;

    /**
     * @var PropertyPathMatcherInterface
     */
    protected $propertyPathMatcher;

    /**
     * Constructor.
     *
     * @param RoutingInterface             $routing             The routing
     * @param PropertyPathMatcherInterface $propertyPathMatcher The property path matcher
     */
    public function __construct(
        RoutingInterface $routing,
        PropertyPathMatcherInterface $propertyPathMatcher
    ) {
        $this->routing = $routing;
        $this->propertyPathMatcher = $propertyPathMatcher;
    }

    /**
     * Generate the property name and url.
     *
     * @param string $type   The type of url generator
     * @param array  $params The type parameters
     * @param object $object The object
     */
    public function generate(string $type, array $params, object $object): string
    {
        if (!\in_array($type, array_values(self::TYPES), true)) {
            $msg = 'There url generator type "%s" does not exist, only "%s" are availables';

            throw new InvalidArgumentException(sprintf($msg, $type, implode('", "', array_keys(self::TYPES))));
        }

        $params = $this->validateParams($params);
        $route = array_shift($params);
        $url = null;

        if (\is_object($object)) {
            $mapping = $this->buildMapping($params);
            $url = $this->getUrl($type, $route, $mapping, $object);
        }

        return $url;
    }

    /**
     * Validate the parameters of type.
     *
     * @param array $params The type parameters
     */
    protected function validateParams(array $params): array
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException('There first parameter "route name" must be defined for "url" serializer type');
        }

        return $params;
    }

    /**
     * Build the route mapping from the type parameters.
     *
     * @param array $params The type parameters
     */
    protected function buildMapping(array $params): array
    {
        $mapping = [];

        foreach ($params as $i => $param) {
            $pos = strpos($param, '=');

            if (false === $pos) {
                $msg = 'The parameter at the position "%s" of url serializer type must have the pattern "<key>=<value>"';

                throw new InvalidArgumentException(sprintf($msg, $i + 1));
            }

            $param = $this->escapeParameter(str_replace('=', ': ', $param));
            $mapping[] = Yaml::parse($param);
        }

        return \count($mapping) > 0 ? array_merge(...$mapping) : $mapping;
    }

    /**
     * Escape the mapping parameter for yaml parser.
     *
     * @param string $param The mapping parameter
     */
    protected function escapeParameter(string $param): string
    {
        if (strrpos($param, '`') > 0) {
            $posStart = strpos($param, '`');
            $length = mb_strlen($param) - $posStart - 2;
            $param = mb_substr($param, 0, $posStart)."'".addslashes(mb_substr($param, $posStart + 1, $length))."'";
        }

        return $param;
    }

    /**
     * Generate the url.
     *
     * @param string $type       The generator type
     * @param string $route      The route name
     * @param array  $parameters The route parameters
     * @param object $object     The object
     *
     * @return mixed
     */
    protected function getUrl(string $type, string $route, array $parameters, object $object)
    {
        $translatable = $this->routing instanceof TranslatableRoutingInterface;
        $organizational = $this->routing instanceof OrganizationalRoutingInterface;

        switch ($type) {
            case LangOrgUrl::class:
                if ($organizational && $translatable) {
                    $method = 'getLangOrgUrl';
                } elseif ($translatable) {
                    $method = 'getLangUrl';
                } else {
                    $method = 'getUrl';
                }

                break;
            case OrgUrl::class:
                $method = $organizational ? 'getOrgUrl' : 'getUrl';

                break;
            case LangUrl::class:
                $method = $translatable ? 'getLangUrl' : 'getUrl';

                break;
            case Url::class:
            default:
                $method = 'getUrl';

                break;
        }

        $parameters = $this->propertyPathMatcher->matchRouteParameters($parameters, $object);

        return $this->routing->{$method}($route, $parameters, false);
    }
}

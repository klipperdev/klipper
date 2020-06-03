<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\Request\ParamConverter;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Expression language for doctrine param converter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DoctrineParamConverterExpressionLanguage extends BaseExpressionLanguage
{
    private RequestStack $requestStack;

    /**
     * @param ExpressionFunctionProviderInterface[] $providers
     */
    public function __construct(RequestStack $requestStack, ?CacheItemPoolInterface $cache = null, array $providers = [])
    {
        $this->requestStack = $requestStack;

        parent::__construct($cache, $providers);
    }

    protected function registerFunctions(): void
    {
        parent::registerFunctions();

        $requestStack = $this->requestStack;

        $this->addFunction(
            new ExpressionFunction(
                'lang',
                static function (): void {
                    throw new \InvalidArgumentException('The doctrine param converter lang expression language function cannot be compiled');
                },
                static function (array $params, $required = false) use ($requestStack) {
                    $lang = null;

                    if (null !== $request = $requestStack->getCurrentRequest()) {
                        $lang = $request->query->get('lang');
                    }

                    if ($required && null === $lang) {
                        $lang = \Locale::getDefault();
                    }

                    return $lang;
                }
            )
        );
    }
}

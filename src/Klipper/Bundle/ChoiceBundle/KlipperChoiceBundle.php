<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ChoiceBundle;

use Klipper\Component\Choice\ExtendableChoiceInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperChoiceBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if ($this->container->hasParameter('klipper_choice.extendable_choice.configs')) {
            $configs = $this->container->getParameter('klipper_choice.extendable_choice.configs');

            foreach ($configs as $class => $config) {
                if (is_a($class, ExtendableChoiceInterface::class, true)) {
                    $identifiers = $class.'::setIdentifiers';
                    $identifiers($config['identifiers'], $config['override'] ?? false);

                    if (null !== ($transDomainVal = $config['translation_domain'] ?? null)) {
                        $transDomain = $class.'::setTranslationDomain';
                        $transDomain($transDomainVal);
                    }
                }
            }
        }
    }
}

<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Security\Firewall;

use Klipper\Component\User\Listener\LocaleSubscriber;

/**
 * Enable the kernel request locale session listener.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class LocaleSessionFirewallListener
{
    protected LocaleSubscriber $localeSubscriber;

    protected array $config;

    /**
     * @param LocaleSubscriber $localeSubscriber The kernel request locale subscriber
     * @param array            $config           The config defined in firewall
     */
    public function __construct(LocaleSubscriber $localeSubscriber, array $config)
    {
        $this->localeSubscriber = $localeSubscriber;
        $this->config = $config;
    }

    public function __invoke(): void
    {
        if ($this->config['enabled']) {
            $this->localeSubscriber->enableLocaleSession();
        } else {
            $this->localeSubscriber->disableLocaleSession();
        }
    }
}

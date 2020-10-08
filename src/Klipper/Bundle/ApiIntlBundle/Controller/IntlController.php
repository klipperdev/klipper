<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiIntlBundle\Controller;

use Klipper\Bundle\ApiBundle\Controller\ControllerHelper;
use Klipper\Component\DoctrineExtensionsExtra\Representation\Pagination;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Locales;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Intl controller.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class IntlController
{
    /**
     * List all available locales.
     *
     * @Route("/available_locales", methods={"GET"})
     */
    public function availableLocales(
        ControllerHelper $helper
    ): Response {
        $names = Locales::getNames();
        $locales = [];

        foreach ($names as $code => $name) {
            if (false === strpos($code, '_')) {
                $locales[] = [
                    'code' => $code,
                    'name' => $name,
                ];
            }
        }

        $locales = array_map(static function ($value) {
            $value['name'] = ucfirst($value['name']);

            return $value;
        }, $locales);

        return $helper->view(new Pagination(
            $locales,
            1,
            \count($locales),
            1,
            \count($locales)
        ));
    }
}

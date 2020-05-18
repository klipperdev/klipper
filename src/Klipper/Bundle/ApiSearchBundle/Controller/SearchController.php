<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiSearchBundle\Controller;

use Klipper\Bundle\ApiBundle\Controller\AbstractController;
use Klipper\Bundle\ApiSearchBundle\RequestHeaders;
use Klipper\Component\Search\SearchManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Search controller.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SearchController extends AbstractController
{
    /**
     * Search in all objects or selected objects.
     *
     * @param Request                $request       The request
     * @param SearchManagerInterface $searchManager The search manager
     *
     * @Route("/search", methods={"GET"})
     */
    public function all(Request $request, SearchManagerInterface $searchManager): Response
    {
        $query = $this->getParam($request, RequestHeaders::SEARCH_QUERY);
        $objects = $this->getParam($request, RequestHeaders::SEARCH_OBJECTS);
        $object = $this->getParam($request, RequestHeaders::SEARCH_OBJECT);

        $objects = \is_string($objects) && !empty($objects)
            ? array_map('trim', explode(',', $objects))
            : [];

        if (!empty($object) && !\in_array($object, $objects, true)) {
            $objects[] = $object;
        }

        return $this->view($searchManager->search($query, $objects));
    }

    private function getParam(Request $request, string $param)
    {
        $param = strtolower($param);
        $queryParam = 0 === strpos($param, 'x-') ? substr($param, 2) : $param;

        return $request->headers->has($param)
            ? $request->headers->get($param)
            : $request->query->get($queryParam);
    }
}

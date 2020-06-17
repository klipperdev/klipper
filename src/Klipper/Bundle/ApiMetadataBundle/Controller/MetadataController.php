<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiMetadataBundle\Controller;

use Klipper\Bundle\ApiBundle\Controller\ControllerHelper;
use Klipper\Bundle\ApiBundle\Util\RequestHeaderUtil;
use Klipper\Bundle\ApiBundle\View\View;
use Klipper\Bundle\ApiMetadataBundle\RequestHeaders;
use Klipper\Bundle\ApiMetadataBundle\ViewGroups;
use Klipper\Component\DoctrineExtensionsExtra\Filterable\FilterManager;
use Klipper\Component\Metadata\Exception\ChoiceNotFoundException;
use Klipper\Component\Metadata\Exception\MetadataNotFoundException;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Metadata controller.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataController
{
    /**
     * Get the choices.
     *
     * @Route("/metadatas/choices", methods={"GET"})
     */
    public function allChoices(
        ControllerHelper $helper,
        PermissionMetadataManagerInterface $pmManager
    ): Response {
        return $helper->view($pmManager->getChoices());
    }

    /**
     * Get the choice.
     *
     * @Route("/metadatas/choices/{name}", methods={"GET"})
     */
    public function showChoice(
        ControllerHelper $helper,
        PermissionMetadataManagerInterface $pmManager,
        string $name
    ): Response {
        try {
            return $helper->view($pmManager->getChoice($name));
        } catch (ChoiceNotFoundException $e) {
            throw $helper->createNotFoundException();
        }
    }

    /**
     * Get the metadatas of objects.
     *
     * @param Request                            $request   The request
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     *
     * @Route("/metadatas", methods={"GET"})
     */
    public function all(
        ControllerHelper $helper,
        Request $request,
        PermissionMetadataManagerInterface $pmManager
    ): Response {
        if (RequestHeaderUtil::getBoolean($request, RequestHeaders::METADATA_DETAILS)) {
            $helper->setView(View::create())->getContext()->addGroup(ViewGroups::METADATA_DETAILS);
        }

        return $helper->view($pmManager->getMetadatas());
    }

    /**
     * Get the metadata of object.
     *
     * @Route("/metadatas/{name}", methods={"GET"})
     */
    public function show(
        ControllerHelper $helper,
        PermissionMetadataManagerInterface $pmManager,
        string $name
    ): Response {
        try {
            $helper->setView(View::create())->getContext()->addGroup(ViewGroups::METADATA_DETAILS);

            return $helper->view($pmManager->getMetadata($name));
        } catch (MetadataNotFoundException $e) {
            throw $helper->createNotFoundException();
        }
    }

    /**
     * Get the configuration filters of the object metadata.
     *
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     * @param FilterManager                      $fManager  The doctrine filter manager
     * @param string                             $name      The object name
     *
     * @Route("/metadatas/{name}/filters", methods={"GET"})
     */
    public function allFilters(
        ControllerHelper $helper,
        PermissionMetadataManagerInterface $pmManager,
        FilterManager $fManager,
        string $name
    ): Response {
        try {
            $pmManager->getMetadata($name);

            return $helper->view($fManager->getFiltersByName($name));
        } catch (MetadataNotFoundException $e) {
            throw $helper->createNotFoundException();
        }
    }
}

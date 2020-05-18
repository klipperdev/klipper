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

use Klipper\Bundle\ApiBundle\Controller\AbstractController;
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
class MetadataController extends AbstractController
{
    /**
     * Get the choices.
     *
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     *
     * @Route("/metadatas/choices", methods={"GET"})
     */
    public function allChoices(PermissionMetadataManagerInterface $pmManager): Response
    {
        return $this->view($pmManager->getChoices());
    }

    /**
     * Get the choice.
     *
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     * @param string                             $name      The choice name
     *
     * @Route("/metadatas/choices/{name}", methods={"GET"})
     */
    public function showChoice(PermissionMetadataManagerInterface $pmManager, string $name): Response
    {
        try {
            return $this->view($pmManager->getChoice($name));
        } catch (ChoiceNotFoundException $e) {
            throw $this->createNotFoundException();
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
    public function all(Request $request, PermissionMetadataManagerInterface $pmManager): Response
    {
        if (RequestHeaderUtil::getBoolean($request, RequestHeaders::METADATA_DETAILS)) {
            $this->setView(View::create())->getContext()->addGroup(ViewGroups::METADATA_DETAILS);
        }

        return $this->view($pmManager->getMetadatas());
    }

    /**
     * Get the metadata of object.
     *
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     * @param string                             $name      The object name
     *
     * @Route("/metadatas/{name}", methods={"GET"})
     */
    public function show(PermissionMetadataManagerInterface $pmManager, string $name): Response
    {
        try {
            $this->setView(View::create())->getContext()->addGroup(ViewGroups::METADATA_DETAILS);

            return $this->view($pmManager->getMetadata($name));
        } catch (MetadataNotFoundException $e) {
            throw $this->createNotFoundException();
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
        PermissionMetadataManagerInterface $pmManager,
        FilterManager $fManager,
        string $name
    ): Response {
        try {
            $pmManager->getMetadata($name);

            return $this->view($fManager->getFiltersByName($name));
        } catch (MetadataNotFoundException $e) {
            throw $this->createNotFoundException();
        }
    }
}

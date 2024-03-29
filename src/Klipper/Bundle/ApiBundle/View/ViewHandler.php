<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiBundle\View;

use Klipper\Bundle\ApiBundle\Serializer\Context;
use Klipper\Bundle\ApiBundle\Serializer\SerializerInterface;
use Klipper\Component\Security\Identity\RoleSecurityIdentity;
use Klipper\Component\Security\Identity\SecurityIdentityManagerInterface;
use Klipper\Component\Security\Organizational\OrganizationalUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ViewHandler implements ConfigurableViewHandlerInterface
{
    protected ?TokenStorageInterface $tokenStorage;

    protected ?SecurityIdentityManagerInterface $sim;

    protected int $emptyContentCode;

    protected bool $serializeNull;

    protected bool $maxDepthChecks = false;

    protected ?bool $serializeNullStrategy = null;

    protected ?string $exclusionStrategyVersion = null;

    protected array $exclusionStrategyGroups = [];

    private RequestStack $requestStack;

    private SerializerInterface $serializer;

    /**
     * @param RequestStack                          $requestStack     The request stack
     * @param SerializerInterface                   $serializer       The serializer
     * @param null|TokenStorageInterface            $tokenStorage     The token storage
     * @param null|SecurityIdentityManagerInterface $sim              The security identity manager
     * @param int                                   $emptyContentCode The HTTP response status code when the view data is null
     * @param bool                                  $serializeNull    Whether or not to serialize null view data
     */
    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        ?TokenStorageInterface $tokenStorage = null,
        ?SecurityIdentityManagerInterface $sim = null,
        int $emptyContentCode = Response::HTTP_NO_CONTENT,
        bool $serializeNull = false
    ) {
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
        $this->sim = $sim;
        $this->emptyContentCode = $emptyContentCode;
        $this->serializeNull = $serializeNull;
    }

    public function setSerializeNullStrategy(bool $isEnabled): void
    {
        $this->serializeNullStrategy = $isEnabled;
    }

    public function setMaxDepthChecks(bool $maxDepthChecks): void
    {
        $this->maxDepthChecks = $maxDepthChecks;
    }

    public function setExclusionStrategyVersion(string $version): void
    {
        $this->exclusionStrategyVersion = $version;
    }

    public function setExclusionStrategyGroups(array $groups): void
    {
        $this->exclusionStrategyGroups = $groups;
    }

    public function handle(View $view, ?Request $request = null): Response
    {
        $request = $request ?? $this->requestStack->getCurrentRequest();
        $format = $view->getFormat();

        if (empty($format) && null !== $request) {
            $format = $request->getRequestFormat();
        }

        $view->getContext()->addGroups($this->getRoleGroups());

        $response = $this->initResponse($view, $format);

        if (null !== $request && !$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', $request->getMimeType($format));
        }

        return $response;
    }

    /**
     * Get the roles for serializer groups.
     *
     * @return string[]
     */
    protected function getRoleGroups(): array
    {
        $roles = [];
        $token = null !== $this->tokenStorage ? $this->tokenStorage->getToken() : null;

        if (null !== $token && null !== $this->sim) {
            $identities = $this->sim->getSecurityIdentities($token);

            foreach ($identities as $identity) {
                if ($identity instanceof RoleSecurityIdentity) {
                    $roles[] = OrganizationalUtil::format($identity->getIdentifier());
                }
            }
        }

        return $roles;
    }

    /**
     * Initializes a response object that represents the view and holds the view's status code.
     *
     * @param View   $view   The view
     * @param string $format The format
     */
    protected function initResponse(View $view, string $format): Response
    {
        $content = null;

        if ($this->serializeNull || null !== $view->getData()) {
            $context = $this->getSerializationContext($view);
            $content = $this->serializer->serialize($view->getData(), $format, $context);
        }

        $response = $view->getResponse();
        $response->setStatusCode($this->getStatusCode($view, $content));

        if (null !== $content) {
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Gets or creates a serialization context and initializes it with the view exclusion
     * strategies, groups and versions if a new context is created.
     *
     * @param View $view The view
     */
    protected function getSerializationContext(View $view): Context
    {
        $context = $view->getContext();

        if ($this->maxDepthChecks) {
            $context->enableMaxDepthChecks();
        }

        if (null !== $this->serializeNullStrategy && null === $context->getSerializeNull()) {
            $context->setSerializeNull($this->serializeNullStrategy);
        }

        if ($this->exclusionStrategyVersion && null === $context->getVersion()) {
            $context->setVersion($this->exclusionStrategyVersion);
        }

        if ($this->exclusionStrategyGroups && empty($context->getGroups())) {
            $context->setGroups($this->exclusionStrategyGroups);
        }

        return $context;
    }

    /**
     * Gets a response HTTP status code from a View instance.
     * By default it will return 200.
     *
     * @param View  $view    The view
     * @param mixed $content The content
     *
     * @return int HTTP status code
     */
    protected function getStatusCode(View $view, $content = null): int
    {
        $statusCode = $view->getStatusCode();

        if (null !== $statusCode) {
            return $statusCode;
        }

        return null !== $content ? Response::HTTP_OK : $this->emptyContentCode;
    }
}

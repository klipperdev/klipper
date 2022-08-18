<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Bridge;

use Klipper\Component\SecurityOauth\Model\OauthClientInterface;
use Klipper\Component\SecurityOauth\Repository\OauthClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ClientRepository implements ClientRepositoryInterface
{
    protected OauthClientRepositoryInterface $repository;

    public function __construct(OauthClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getClientEntity(
        $clientIdentifier,
        $grantType = null,
        $clientSecret = null,
        $mustValidateSecret = true
    ): ?ClientEntityInterface {
        $appClient = $this->repository->findEnabled($clientIdentifier);

        if (null === $appClient) {
            return null;
        }

        if (null !== $clientSecret && !$this->validateGrantType($appClient, $grantType)) {
            return null;
        }

        if ($mustValidateSecret && null !== $clientSecret && !hash_equals((string) $appClient->getSecret(), (string) $clientSecret)) {
            return null;
        }

        return new Client($clientIdentifier, $appClient->getName(), $appClient->getRedirectUri());
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $appClient = $this->repository->findEnabled($clientIdentifier);

        if (null === $appClient) {
            return false;
        }

        if (null !== $clientSecret && !hash_equals((string) $appClient->getSecret(), (string) $clientSecret)) {
            return false;
        }

        return $this->validateGrantType($appClient, $grantType);
    }

    private function validateGrantType(OauthClientInterface $client, ?string $grantType): bool
    {
        $grantType = $grantType ?? 'implicit';

        return \in_array($grantType, $client->getGrantTypes(), true);
    }
}

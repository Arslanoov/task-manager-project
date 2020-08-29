<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

final class Client implements ClientEntityInterface
{
    use EntityTrait;
    use ClientTrait;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
    public function setRedirectUri($uri): void
    {
        $this->redirectUri = $uri;
    }
}

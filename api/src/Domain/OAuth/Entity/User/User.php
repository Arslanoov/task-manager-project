<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\User;

use League\OAuth2\Server\Entities\UserEntityInterface;

final class User implements UserEntityInterface
{
    private string $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}

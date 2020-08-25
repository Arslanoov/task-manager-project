<?php

declare(strict_types=1);

namespace Infrastructure\Domain\User\Service;

use Domain\User\Service\User\PasswordHasherInterface;
use RuntimeException;

final class PasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        if (false === $hash) {
            throw new RuntimeException('Unable to generate hash.');
        }
        return $hash;
    }
}
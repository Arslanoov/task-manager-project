<?php

declare(strict_types=1);

namespace Domain\User\Service\User;

use RuntimeException;

final class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if (false === $hash) {
            throw new RuntimeException('Unable to generate hash.');
        }
        return $hash;
    }
}
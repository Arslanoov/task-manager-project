<?php

declare(strict_types=1);

namespace Domain\User\Service\User;

final class PasswordHasher
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}
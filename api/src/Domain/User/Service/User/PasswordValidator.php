<?php

declare(strict_types=1);

namespace Domain\User\Service\User;

final class PasswordValidator
{
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
<?php

declare(strict_types=1);

namespace Domain\User\Service\User;

interface PasswordValidatorInterface
{
    public function validate(string $password, string $hash): bool;
}
<?php

declare(strict_types=1);

namespace Domain\User\Service\User;

use RuntimeException;

interface PasswordHasherInterface
{
    /**
     * @throws RuntimeException
     * @param string $password
     * @return string
     */
    public function hash(string $password): string;
}
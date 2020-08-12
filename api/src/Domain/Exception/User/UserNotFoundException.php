<?php

declare(strict_types=1);

namespace Domain\Exception\User;

use Domain\Exception\DomainException;
use Throwable;

final class UserNotFoundException extends DomainException
{
    public function __construct($message = "User not found.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
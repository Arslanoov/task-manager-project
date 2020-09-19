<?php

declare(strict_types=1);

namespace Domain\Exception\User;

use Domain\Exception\DomainException;
use Throwable;

final class UserNotFoundException extends DomainException
{
    public function __construct($message = "User is not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

final class ForbiddenException extends Exception
{
    public function __construct($message = "Forbidden.", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
<?php

declare(strict_types=1);

namespace Domain\Exception\Schedule;

use DomainException;
use Throwable;

final class TaskNotFoundException extends DomainException
{
    public function __construct($message = "Task not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
<?php

declare(strict_types=1);

namespace Domain\Exception\Schedule;

use DomainException;
use Throwable;

final class ScheduleNotFoundException extends DomainException
{
    public function __construct($message = "Schedule not found.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
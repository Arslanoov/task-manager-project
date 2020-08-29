<?php

declare(strict_types=1);

namespace Domain\Exception\Schedule;

use Domain\Exception\DomainException;
use Throwable;

final class ScheduleNotFoundException extends DomainException
{
    public function __construct($message = "Schedule not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

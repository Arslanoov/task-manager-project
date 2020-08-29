<?php

declare(strict_types=1);

namespace Domain\Exception\Schedule;

use Domain\Exception\DomainException;
use Throwable;

final class StepNotFoundException extends DomainException
{
    public function __construct($message = "Step not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

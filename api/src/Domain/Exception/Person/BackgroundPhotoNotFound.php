<?php

declare(strict_types=1);

namespace Domain\Exception\Person;

use Domain\Exception\DomainException;
use Throwable;

final class BackgroundPhotoNotFound extends DomainException
{
    public function __construct($message = "Background photo not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

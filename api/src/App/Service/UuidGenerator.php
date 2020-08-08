<?php

declare(strict_types=1);

namespace App\Service;

use Ramsey\Uuid\Uuid;

final class UuidGenerator
{
    public function uuid4(): string
    {
        return Uuid::uuid4()->toString();
    }
}
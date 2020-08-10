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

    public function uuid1(): string
    {
        return Uuid::uuid1()->toString();
    }
}
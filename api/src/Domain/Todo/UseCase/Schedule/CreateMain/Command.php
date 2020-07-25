<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateMain;

final class Command
{
    public string $userId;

    /**
     * Command constructor.
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}
<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Remove;

final class Command
{
    public string $taskId;

    /**
     * Command constructor.
     * @param string $taskId
     */
    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }
}

<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Create;

final class Command
{
    public string $taskId;
    public string $name;

    /**
     * Command constructor.
     * @param string $taskId
     * @param string $name
     */
    public function __construct(string $taskId, string $name)
    {
        $this->taskId = $taskId;
        $this->name = $name;
    }
}
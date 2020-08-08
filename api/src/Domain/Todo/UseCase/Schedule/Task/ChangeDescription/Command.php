<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\ChangeDescription;

final class Command
{
    public string $taskId;
    public string $description;

    /**
     * Command constructor.
     * @param string $taskId
     * @param string $description
     */
    public function __construct(string $taskId, string $description)
    {
        $this->taskId = $taskId;
        $this->description = $description;
    }
}
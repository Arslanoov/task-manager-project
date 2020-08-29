<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Move;

final class Command
{
    public string $taskId;
    public string $newScheduleId;

    /**
     * Command constructor.
     * @param string $taskId
     * @param string $newScheduleId
     */
    public function __construct(string $taskId, string $newScheduleId)
    {
        $this->taskId = $taskId;
        $this->newScheduleId = $newScheduleId;
    }
}

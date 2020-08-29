<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Create;

final class Command
{
    public int $id;
    public string $taskId;
    public string $name;

    /**
     * Command constructor.
     * @param int $id
     * @param string $taskId
     * @param string $name
     */
    public function __construct(int $id, string $taskId, string $name)
    {
        $this->id = $id;
        $this->taskId = $taskId;
        $this->name = $name;
    }
}

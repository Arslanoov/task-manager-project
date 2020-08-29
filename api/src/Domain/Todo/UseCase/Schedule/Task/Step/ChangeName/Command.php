<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\ChangeName;

final class Command
{
    public int $stepId;
    public string $name;

    /**
     * Command constructor.
     * @param int $stepId
     * @param string $name
     */
    public function __construct(int $stepId, string $name)
    {
        $this->stepId = $stepId;
        $this->name = $name;
    }
}

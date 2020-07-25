<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Down;

final class Command
{
    public int $stepId;

    /**
     * Command constructor.
     * @param int $stepId
     */
    public function __construct(int $stepId)
    {
        $this->stepId = $stepId;
    }
}
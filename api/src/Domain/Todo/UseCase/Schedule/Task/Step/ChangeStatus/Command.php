<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\ChangeStatus;

final class Command
{
    public int $id;
    public string $status;

    /**
     * Command constructor.
     * @param int $id
     * @param string $status
     */
    public function __construct(int $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }
}

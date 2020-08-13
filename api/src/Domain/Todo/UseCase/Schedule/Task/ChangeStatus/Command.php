<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\ChangeStatus;

final class Command
{
    public string $id;
    public string $status;

    /**
     * Command constructor.
     * @param string $id
     * @param string $status
     */
    public function __construct(string $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }
}
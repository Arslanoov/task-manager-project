<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Remove;

final class Command
{
    public string $id;

    /**
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateMain;

final class Command
{
    public string $personId;

    /**
     * Command constructor.
     * @param string $personId
     */
    public function __construct(string $personId)
    {
        $this->personId = $personId;
    }
}
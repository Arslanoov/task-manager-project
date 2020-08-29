<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateCustom;

final class Command
{
    public string $id;
    public string $personId;
    public string $name;

    /**
     * Command constructor.
     * @param string $id
     * @param string $personId
     * @param string $name
     */
    public function __construct(string $id, string $personId, string $name)
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->name = $name;
    }
}

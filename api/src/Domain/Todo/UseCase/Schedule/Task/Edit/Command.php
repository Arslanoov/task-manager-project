<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Edit;

final class Command
{
    public string $id;
    public string $name;
    public string $importantLevel;
    public string $description;

    /**
     * Command constructor.
     * @param string $id
     * @param string $name
     * @param string $importantLevel
     * @param string $description
     */
    public function __construct(string $id, string $name, string $importantLevel, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->importantLevel = $importantLevel;
        $this->description = $description;
    }
}

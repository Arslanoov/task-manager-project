<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Create;

final class Command
{
    public string $scheduleId;
    public string $id;
    public string $name;
    public ?string $description = null;
    public string $importantLevel;

    /**
     * Command constructor.
     * @param string $scheduleId
     * @param string $id
     * @param string $name
     * @param string|null $description
     * @param string $importantLevel
     */
    public function __construct(string $scheduleId, string $id, string $name, ?string $description, string $importantLevel)
    {
        $this->scheduleId = $scheduleId;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->importantLevel = $importantLevel;
    }
}
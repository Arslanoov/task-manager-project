<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateByDate;

use DateTimeImmutable;

final class Command
{
    public DateTimeImmutable $date;
    public string $personId;

    /**
     * Command constructor.
     * @param DateTimeImmutable $date
     * @param string $personId
     */
    public function __construct(DateTimeImmutable $date, string $personId)
    {
        $this->date = $date;
        $this->personId = $personId;
    }
}
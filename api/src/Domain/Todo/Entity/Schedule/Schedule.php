<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Domain\User\Entity\User;
use Cycle\Annotated\Annotation as Cycle;

/**
 * Class Schedule
 * @Cycle\Entity(
 *     table="todo_schedules",
 *     role="schedule"
 * )
 * @Cycle\Table()
 */
final class Schedule
{
    /**
     * @Cycle\Column(type="primary", primary=true)
     * @Cycle\Relation\Embedded(target="ScheduleId")
     */
    private ScheduleId $id;
    /**
     * @Cycle\Column(type="string(32)")
     * @Cycle\Relation\HasOne(target="user")
     */
    private User $user;
    /** @Cycle\Column(type="datetime") */
    private DateTimeImmutable $date;
    /** @Cycle\Relation\Embedded(target="ImportantLevel") */
    private ImportantLevel $level;
    /** @Cycle\Relation\Embedded(target="Type") */
    private Type $type;
    /** @Cycle\Column(type="integer") */
    private int $tasksCount = 0;

    /**
     * Schedule constructor.
     * @param ScheduleId $id
     * @param User $user
     * @param DateTimeImmutable $date
     * @param ImportantLevel $level
     * @param Type $type
     * @param int $tasksCount
     */
    private function __construct(
        ScheduleId $id, User $user, DateTimeImmutable $date,
        ImportantLevel $level, Type $type, int $tasksCount = 0
    )
    {
        $this->id = $id;
        $this->user = $user;
        $this->date = $date;
        $this->level = $level;
        $this->type = $type;
        $this->tasksCount = $tasksCount;
    }

    public static function main(ScheduleId $id, User $user, ImportantLevel $level): self
    {
        return new self(
            $id, $user, new DateTimeImmutable('today'),
            $level, Type::main()
        );
    }

    public static function daily(ScheduleId $id, User $user, ImportantLevel $level): self
    {
        return new self(
            $id, $user, new DateTimeImmutable('today'),
            $level, Type::daily()
        );
    }

    /**
     * @return ScheduleId
     */
    public function getId(): ScheduleId
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return ImportantLevel
     */
    public function getLevel(): ImportantLevel
    {
        return $this->level;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getTasksCount(): int
    {
        return $this->tasksCount;
    }

    public function isNotImportant(): bool
    {
        return $this->getLevel()->isNotImportant();
    }

    public function isImportant(): bool
    {
        return $this->getLevel()->isImportant();
    }

    public function isVeryImportant(): bool
    {
        return $this->getLevel()->isVeryImportant();
    }

    public function isMain(): bool
    {
        return $this->getType()->isMain();
    }

    public function isDaily(): bool
    {
        return $this->getType()->isDaily();
    }
}
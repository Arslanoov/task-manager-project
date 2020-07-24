<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Domain\User\Entity\User\User;
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
     * @Cycle\Column(type="string(255)")
     * @Cycle\Relation\BelongsTo(target="user", innerKey="user", outerKey="id")
     */
    private User $user;
    /** @Cycle\Column(type="datetime") */
    private DateTimeImmutable $date;
    /** @Cycle\Relation\Embedded(target="Type") */
    private Type $type;
    /** @Cycle\Column(type="integer") */
    private int $tasksCount = 0;

    /**
     * Schedule constructor.
     * @param ScheduleId $id
     * @param User $user
     * @param DateTimeImmutable $date
     * @param Type $type
     * @param int $tasksCount
     */
    private function __construct(
        ScheduleId $id, User $user, DateTimeImmutable $date,
        Type $type, int $tasksCount = 0
    )
    {
        $this->id = $id;
        $this->user = $user;
        $this->date = $date;
        $this->type = $type;
        $this->tasksCount = $tasksCount;
    }

    public static function main(ScheduleId $id, User $user): self
    {
        return new self(
            $id, $user, new DateTimeImmutable('today'),
            Type::main()
        );
    }

    public static function daily(ScheduleId $id, User $user): self
    {
        return new self(
            $id, $user, new DateTimeImmutable('today'),
            Type::daily()
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

    public function isMain(): bool
    {
        return $this->getType()->isMain();
    }

    public function isDaily(): bool
    {
        return $this->getType()->isDaily();
    }
}
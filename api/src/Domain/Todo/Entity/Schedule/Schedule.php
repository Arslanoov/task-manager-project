<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Domain\Todo\Entity\Person\Person;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_schedules")
 * @ORM\Entity()
 */
final class Schedule
{
    /**
     * @var Id
     * @ORM\Column(type="todo_schedule_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Domain\Todo\Entity\Person\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    private Person $person;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="date_immutable")
     */
    private DateTimeImmutable $date;
    /**
     * @var Type
     * @ORM\Column(type="todo_schedule_type")
     */
    private Type $type;
    /**
     * @var int
     * @ORM\Column(type="integer", name="tasks_count")
     */
    private int $tasksCount = 0;

    /**
     * Schedule constructor.
     * @param Id $id
     * @param Person $person
     * @param DateTimeImmutable $date
     * @param Type $type
     * @param int $tasksCount
     */
    private function __construct(
        Id $id, Person $person, DateTimeImmutable $date,
        Type $type, int $tasksCount = 0
    )
    {
        $this->id = $id;
        $this->person = $person;
        $this->date = $date;
        $this->type = $type;
        $this->tasksCount = $tasksCount;
    }

    public static function main(Id $id, Person $person): self
    {
        return new self(
            $id, $person, new DateTimeImmutable('today'),
            Type::main()
        );
    }

    public static function daily(Id $id, Person $person): self
    {
        return new self(
            $id, $person, new DateTimeImmutable('today'),
            Type::daily()
        );
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
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
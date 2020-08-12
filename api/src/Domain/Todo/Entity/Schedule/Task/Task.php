<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Todo\Entity\Schedule\Schedule;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_schedule_tasks")
 * @ORM\Entity()
 */
class Task
{
    /**
     * @var Id
     * @ORM\Column(type="todo_schedule_task_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Schedule
     * @ORM\ManyToOne(targetEntity="Domain\Todo\Entity\Schedule\Schedule")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", nullable=false)
     */
    private Schedule $schedule;
    /**
     * @var Name
     * @ORM\Column(type="todo_schedule_task_name")
     */
    private Name $name;
    /**
     * @var Description
     * @ORM\Column(type="todo_schedule_task_description")
     */
    private Description $description;
    /**
     * @var ImportantLevel
     * @ORM\Column(type="todo_schedule_task_important_level")
     */
    private ImportantLevel $level;
    /**
     * @var Status
     * @ORM\Column(type="todo_schedule_task_status")
     */
    private Status $status;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Domain\Todo\Entity\Schedule\Task\Step\Step", mappedBy="task")
     */
    private Collection $steps;

    /**
     * Task constructor.
     * @param Id $id
     * @param Schedule $schedule
     * @param Name $name
     * @param Description $description
     * @param ImportantLevel $level
     * @param Status $status
     */
    private function __construct(
        Id $id, Schedule $schedule, Name $name,
        Description $description, ImportantLevel $level, Status $status
    )
    {
        $this->id = $id;
        $this->schedule = $schedule;
        $this->name = $name;
        $this->description = $description;
        $this->level = $level;
        $this->status = $status;
        $this->steps = new ArrayCollection();
    }

    public static function new(
        Id $id, Schedule $schedule, Name $name,
        Description $description, ImportantLevel $level
    ): self
    {
        return new self(
            $id,
            $schedule,
            $name,
            $description,
            $level,
            Status::notComplete()
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
     * @return Schedule
     */
    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Description
     */
    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @return ImportantLevel
     */
    public function getLevel(): ImportantLevel
    {
        return $this->level;
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps->toArray();
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
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

    public function isNotComplete(): bool
    {
        return $this->getStatus()->isNotComplete();
    }

    public function isInProgress(): bool
    {
        return $this->getStatus()->isInProgress();
    }

    public function isComplete(): bool
    {
        return $this->getStatus()->isComplete();
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeImportantLevel(ImportantLevel $importantLevel): void
    {
        $this->level = $importantLevel;
    }

    public function changeDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function changeSchedule(Schedule $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function stopExecution(): void
    {
        $this->status = Status::notComplete();
    }

    public function startExecution(): void
    {
        $this->status = Status::inProgress();
    }

    public function complete(): void
    {
        $this->status = Status::complete();
    }

    public function makeNotImportant(): void
    {
        $this->level = ImportantLevel::notImportant();
    }

    public function makeImportant(): void
    {
        $this->level = ImportantLevel::important();
    }

    public function makeVeryImportant(): void
    {
        $this->level = ImportantLevel::veryImportant();
    }
}
<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

use Cycle\Annotated\Annotation as Cycle;
use Domain\Todo\Entity\Schedule\Task\Task;

/**
 * Class Step
 * @Cycle\Entity(
 *     table="todo_schedule_task_steps",
 *     role="step"
 * )
 * @Cycle\Table()
 */
final class Step
{
    /**
     * @Cycle\Column(type="primary", primary=true)
     * @Cycle\Relation\Embedded(target="StepId")
     */
    private StepId $id;
    /**
     * @Cycle\Column(type="string")
     * @Cycle\Relation\BelongsTo(target="task", innerKey="task", outerKey="id")
     */
    private Task $task;
    /** @Cycle\Relation\Embedded(target="StepName") */
    private StepName $name;
    /** @Cycle\Relation\Embedded(target="SortOrder") */
    private SortOrder $sortOrder;
    /** @Cycle\Relation\Embedded(target="StepStatus") */
    private StepStatus $status;

    /**
     * Step constructor.
     * @param StepId $id
     * @param Task $task
     * @param StepName $name
     * @param SortOrder $sortOrder
     * @param StepStatus $status
     */
    private function __construct(
        StepId $id, Task $task, StepName $name,
        SortOrder $sortOrder, StepStatus $status
    )
    {
        $this->id = $id;
        $this->task = $task;
        $this->name = $name;
        $this->sortOrder = $sortOrder;
        $this->status = $status;
    }

    public static function new(StepId $id, Task $task, StepName $name): self
    {
        return new self(
            $id, $task, $name, new SortOrder(null),
            StepStatus::notComplete()
        );
    }

    /**
     * @return StepId
     */
    public function getId(): StepId
    {
        return $this->id;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @return StepName
     */
    public function getName(): StepName
    {
        return $this->name;
    }

    /**
     * @return SortOrder
     */
    public function getSortOrder(): SortOrder
    {
        return $this->sortOrder;
    }

    /**
     * @return StepStatus
     */
    public function getStatus(): StepStatus
    {
        return $this->status;
    }

    public function changeSortOrder(SortOrder $order): void
    {
        $this->sortOrder = $order;
    }

    public function changeName(StepName $name): void
    {
        $this->name = $name;
    }
}
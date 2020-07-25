<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Create;

use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepName;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\Entity\Schedule\Task\TaskId;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\TransactionRunnerInterface;

final class Handler
{
    private StepRepository $steps;
    private TaskRepository $tasks;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param StepRepository $steps
     * @param TaskRepository $tasks
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(StepRepository $steps, TaskRepository $tasks, TransactionRunnerInterface $transaction)
    {
        $this->steps = $steps;
        $this->tasks = $tasks;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->getById(new TaskId($command->taskId));
        $nextId = $this->steps->getNextId();

        $step = Step::new(
            $nextId,
            $task,
            new StepName($command->name)
        );

        $step->changeSortOrder(new SortOrder($nextId->getValue()));

        $this->steps->add($step);

        $this->transaction->run();
    }
}
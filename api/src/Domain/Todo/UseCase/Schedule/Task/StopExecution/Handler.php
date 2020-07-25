<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\StopExecution;

use Domain\Todo\Entity\Schedule\Task\TaskId;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\TransactionRunnerInterface;

final class Handler
{
    private TaskRepository $tasks;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param TaskRepository $tasks
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(TaskRepository $tasks, TransactionRunnerInterface $transaction)
    {
        $this->tasks = $tasks;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->findById(new TaskId($command->taskId));

        $task->stopExecution();

        $this->transaction->run();
    }
}
<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Move;

use Domain\Todo\Entity\Schedule\ScheduleId;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\TaskId;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\TransactionRunnerInterface;

final class Handler
{
    private TaskRepository $tasks;
    private ScheduleRepository $schedules;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param TaskRepository $tasks
     * @param ScheduleRepository $schedules
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(TaskRepository $tasks, ScheduleRepository $schedules, TransactionRunnerInterface $transaction)
    {
        $this->tasks = $tasks;
        $this->schedules = $schedules;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->getById(new TaskId($command->taskId));
        $newSchedule = $this->schedules->getById(new ScheduleId($command->newScheduleId));

        $task->changeSchedule($newSchedule);

        $this->transaction->run();
    }
}
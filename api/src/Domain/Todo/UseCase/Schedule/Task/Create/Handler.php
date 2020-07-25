<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Create;

use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Description;
use Domain\Todo\Entity\Schedule\Task\ImportantLevel;
use Domain\Todo\Entity\Schedule\Task\Name;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\TransactionRunnerInterface;
use Domain\Todo\Entity\Schedule\ScheduleId;

final class Handler
{
    private ScheduleRepository $schedules;
    private TaskRepository $tasks;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param ScheduleRepository $schedules
     * @param TaskRepository $tasks
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(ScheduleRepository $schedules, TaskRepository $tasks, TransactionRunnerInterface $transaction)
    {
        $this->schedules = $schedules;
        $this->tasks = $tasks;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        $schedule = $this->schedules->getById(new ScheduleId($command->scheduleId));

        $task = Task::new(
            $schedule,
            new Name($command->name),
            new Description($command->description ?: 'Empty description'),
            new ImportantLevel($command->importantLevel)
        );

        $this->tasks->add($task);

        $this->transaction->run();
    }
}
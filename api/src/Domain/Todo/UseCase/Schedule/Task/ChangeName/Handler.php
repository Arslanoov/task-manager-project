<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\ChangeName;

use Domain\Todo\Entity\Schedule\Task\Name;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\FlusherInterface;

final class Handler
{
    private TaskRepository $tasks;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param TaskRepository $tasks
     * @param FlusherInterface $flusher
     */
    public function __construct(TaskRepository $tasks, FlusherInterface $flusher)
    {
        $this->tasks = $tasks;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->findById(new Id($command->taskId));

        $task->changeName(new Name($command->name));

        $this->flusher->flush();
    }
}
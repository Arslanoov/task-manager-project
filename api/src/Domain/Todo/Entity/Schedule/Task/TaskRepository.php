<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Transaction;
use Domain\Exception\Schedule\TaskNotFoundException;

final class TaskRepository
{
    private ORMInterface $orm;
    private RepositoryInterface $tasks;
    private Transaction $transaction;

    /**
     * UserRepository constructor.
     * @param ORMInterface $orm
     * @param Transaction $transaction
     */
    public function __construct(ORMInterface $orm, Transaction $transaction)
    {
        $this->orm = $orm;
        $this->tasks = $orm->getRepository(Task::class);
        $this->transaction = $transaction;
    }

    public function findById(TaskId $id): ?Task
    {
        /** @var Task $task */
        $task = $this->tasks->findByPK($id->getValue());
        return $task;
    }

    public function getById(TaskId $id): Task
    {
        if (!$task = $this->findById($id)) {
            throw new TaskNotFoundException();
        }

        return $task;
    }

    public function add(Task $task): void
    {
        $this->transaction->persist($task);
    }

    public function remove(Task $task): void
    {
        $this->transaction->delete($task);
    }
}
<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\TaskNotFoundException;

final class TaskRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $tasks;

    /**
     * TaskRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->tasks = $em->getRepository(Task::class);
    }

    public function findById(Id $id): ?Task
    {
        /** @var Task $task */
        $task = $this->tasks->find($id->getValue());
        return $task;
    }

    public function getById(Id $id): Task
    {
        if (!$task = $this->findById($id)) {
            throw new TaskNotFoundException();
        }

        return $task;
    }

    public function add(Task $task): void
    {
        $this->em->persist($task);
    }

    public function remove(Task $task): void
    {
        $this->em->remove($task);
    }
}
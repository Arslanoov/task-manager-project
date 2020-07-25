<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select\Repository;
use Cycle\ORM\Transaction;
use Domain\Exception\Schedule\StepNotFoundException;
use Domain\Todo\Entity\Schedule\Task\Task;
use Spiral\Database\DatabaseInterface;

final class StepRepository
{
    private ORMInterface $orm;
    private Repository $steps;
    private DatabaseInterface $database;
    private Transaction $transaction;

    /**
     * UserRepository constructor.
     * @param ORMInterface $orm
     * @param Transaction $transaction
     */
    public function __construct(ORMInterface $orm, Transaction $transaction)
    {
        /** @var Repository $steps */
        $steps = $orm->getRepository(Step::class);
        $this->steps = $steps;

        $this->orm = $orm;
        $this->database = $orm->getSource(Step::class)->getDatabase();
        $this->transaction = $transaction;
    }

    public function findById(StepId $id): ?Step
    {
        /** @var Step $step */
        $step = $this->steps->findByPK($id->getValue());
        return $step;
    }

    public function findHigherStep(Task $task, SortOrder $order): ?Step
    {
        /** @var Step $step */
        $step = $this->steps->select()
            ->where('sort_order', '<', $order->getValue())
            ->where('task_id', '=', $task->getId()->getValue())
        ;

        return $step;
    }

    public function findLowerStep(Task $task, SortOrder $order): ?Step
    {
        /** @var Step $step */
        $step = $this->steps->select()
            ->where('sort_order', '>', $order->getValue())
            ->where('task_id', '=', $task->getId()->getValue())
        ;

        return $step;
    }

    public function getById(StepId $id): Step
    {
        if (!$step = $this->findById($id)) {
            throw new StepNotFoundException();
        }

        return $step;
    }

    public function getHigherStep(Task $task, SortOrder $order): Step
    {
        if (!$step = $this->findHigherStep($task, $order)) {
            throw new StepNotFoundException('Higher step not found.');
        }

        return $step;
    }

    public function getLowerStep(Task $task, SortOrder $order): Step
    {
        if (!$step = $this->findHigherStep($task, $order)) {
            throw new StepNotFoundException('Lower step not found.');
        }

        return $step;
    }

    public function getNextId(): StepId
    {
        return new StepId(
            (int) $this->database
                ->query("SELECT nextval('todo_schedule_task_steps_id_seq')")
                ->fetch()
            ['nextval']
        );
    }

    public function add(Step $step): void
    {
        $this->transaction->persist($step);
    }

    public function remove(Step $step): void
    {
        $this->transaction->delete($step);
    }
}
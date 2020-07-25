<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Down;

use Domain\Todo\Entity\Schedule\Task\Step\StepId;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\TransactionRunnerInterface;

final class Handler
{
    private StepRepository $steps;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param StepRepository $steps
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(StepRepository $steps, TransactionRunnerInterface $transaction)
    {
        $this->steps = $steps;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        $step = $this->steps->getById(new StepId($command->stepId));
        $lowerStep = $this->steps->getLowerStep($step->getTask(), $step->getSortOrder());

        $oldOrder = $step->getSortOrder();

        $step->changeSortOrder($lowerStep->getSortOrder());
        $lowerStep->changeSortOrder($oldOrder);
        
        $this->steps->add($step);
        $this->steps->add($lowerStep);

        $this->transaction->run();
    }
}
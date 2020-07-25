<?php

declare(strict_types=1);

namespace Domain;

use Cycle\ORM\Transaction;
use Throwable;

final class TransactionRunner implements TransactionRunnerInterface
{
    private Transaction $transaction;

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        $this->transaction->run();
    }
}
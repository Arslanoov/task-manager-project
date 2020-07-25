<?php

declare(strict_types=1);

namespace Domain;

use Cycle\ORM\Transaction;
use Throwable;

final class TransactionRunner implements TransactionRunnerInterface
{
    private Transaction $transaction;

    /**
     * TransactionRunner constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        $this->transaction->run();
    }
}
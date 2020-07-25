<?php

declare(strict_types=1);

namespace Domain;

interface TransactionRunnerInterface
{
    public function run(): void;
}
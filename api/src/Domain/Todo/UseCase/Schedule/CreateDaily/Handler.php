<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateDaily;

use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\TransactionRunnerInterface;
use Domain\User\Entity\User\UserId;
use Domain\User\Entity\User\UserRepository;
use Domain\Todo\Entity\Schedule\ScheduleId;

final class Handler
{
    private UserRepository $users;
    private ScheduleRepository $schedules;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param ScheduleRepository $schedules
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(UserRepository $users, ScheduleRepository $schedules, TransactionRunnerInterface $transaction)
    {
        $this->users = $users;
        $this->schedules = $schedules;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getById(new UserId($command->userId));

        $schedule = Schedule::daily(
            ScheduleId::uuid4(),
            $user
        );

        $this->schedules->add($schedule);

        $this->transaction->run();
    }
}
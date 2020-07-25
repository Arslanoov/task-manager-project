<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Transaction;
use DateTimeImmutable;

final class ScheduleRepository
{
    private ORMInterface $orm;
    private RepositoryInterface $schedules;
    private Transaction $transaction;

    /**
     * UserRepository constructor.
     * @param ORMInterface $orm
     * @param Transaction $transaction
     */
    public function __construct(ORMInterface $orm, Transaction $transaction)
    {
        $this->orm = $orm;
        $this->schedules = $orm->getRepository(Schedule::class);
        $this->transaction = $transaction;
    }

    public function findByDate(DateTimeImmutable $date): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOne([
            'date' => $date
        ]);

        return $schedule;
    }

    public function add(Schedule $schedule): void
    {
        $this->transaction->persist($schedule);
    }

    public function remove(Schedule $schedule): void
    {
        $this->transaction->delete($schedule);
    }
}
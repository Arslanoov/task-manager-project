<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\ScheduleNotFoundException;

final class ScheduleRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $schedules;

    /**
     * ScheduleRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->schedules = $em->getRepository(Schedule::class);
    }

    public function findByDate(DateTimeImmutable $date): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'date' => $date
        ]);

        return $schedule;
    }

    public function findById(Id $id): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->find($id->getValue());
        return $schedule;
    }

    public function getById(Id $id): Schedule
    {
        if (!$schedule = $this->findById($id)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    public function add(Schedule $schedule): void
    {
        $this->em->persist($schedule);
    }

    public function remove(Schedule $schedule): void
    {
        $this->em->remove($schedule);
    }
}
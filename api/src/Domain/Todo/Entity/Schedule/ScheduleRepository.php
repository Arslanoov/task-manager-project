<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\ScheduleNotFoundException;
use Domain\Todo\Entity\Person\Person;

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

    // By date

    public function findDailyByDate(Person $person, DateTimeImmutable $date): ?Schedule
    {
        /** @var Schedule|null $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => $date,
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function getDailyByDate(Person $person, DateTimeImmutable $date): Schedule
    {
        if (!$schedule = $this->findDailyByDate($person, $date)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    // Main

    public function findPersonMainSchedule(Person $person): ?Schedule
    {
        /** @var Schedule|null $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'type' => Type::main()
        ]);

        return $schedule;
    }

    public function getPersonMainSchedule(Person $person): ?Schedule
    {
        if (!$schedule = $this->findPersonMainSchedule($person)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    // Daily

    public function findNextSchedule(Person $person, Schedule $schedule): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => $schedule->getDate()->modify('+1 day'),
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function findPreviousSchedule(Person $person, Schedule $schedule): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => $schedule->getDate()->modify('-1 day'),
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function findPersonTodaySchedule(Person $person): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => new DateTimeImmutable('today'),
            'type' => Type::daily()
        ]);

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
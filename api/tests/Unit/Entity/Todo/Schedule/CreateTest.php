<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\Todo\Schedule;

use DateTimeImmutable;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleId;
use Domain\User\Entity\User\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Builder\UserBuilder;

class CreateTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = (new UserBuilder())->build();
    }

    public function testSuccessDaily(): void
    {
        $user = $this->user;

        $date = new DateTimeImmutable('today');

        $schedule = Schedule::daily(
            $id = ScheduleId::uuid4(),
            $user
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getUser(), $user);

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isMain());
        $this->assertTrue($schedule->isDaily());
    }

    public function testSuccessMain(): void
    {
        $user = $this->user;
        $date = new DateTimeImmutable('today');

        $schedule = Schedule::main(
            $id = ScheduleId::uuid4(),
            $user
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getUser(), $user);

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isDaily());
        $this->assertTrue($schedule->isMain());
    }
}
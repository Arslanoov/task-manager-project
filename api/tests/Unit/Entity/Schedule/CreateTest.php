<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\Schedule;

use DateTimeImmutable;
use Domain\Todo\Entity\Schedule\ImportantLevel;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleId;
use Domain\User\Entity\User;
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
            $user,
            $level = ImportantLevel::veryImportant()
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getUser(), $user);

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertFalse($schedule->isNotImportant());
        $this->assertFalse($schedule->isImportant());
        $this->assertTrue($schedule->isVeryImportant());

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
            $user,
            $level = ImportantLevel::veryImportant()
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getUser(), $user);

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertFalse($schedule->isNotImportant());
        $this->assertFalse($schedule->isImportant());
        $this->assertTrue($schedule->isVeryImportant());

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isDaily());
        $this->assertTrue($schedule->isMain());
    }

    // level

    public function testEmptyLevel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty value. Got: ""');

        $user = $this->user;

        Schedule::daily(
            ScheduleId::uuid4(),
            $user,
            new ImportantLevel('')
        );
    }

    public function testTooLongLevel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain between 4 and 16 characters. Got: "sssssssssssssssssssssssssss"');

        $user = $this->user;

        Schedule::daily(
            ScheduleId::uuid4(),
            $user,
            new ImportantLevel('sssssssssssssssssssssssssss')
        );
    }

    public function testTooShortLevel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain between 4 and 16 characters. Got: "s"');

        $user = $this->user;

        Schedule::daily(
            ScheduleId::uuid4(),
            $user,
            new ImportantLevel('s')
        );
    }

    public function testInvalidLevel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected one of: "Not Important", "Import", "Very Important". Got: "Invalid level"');

        $user = $this->user;

        Schedule::daily(
            ScheduleId::uuid4(),
            $user,
            new ImportantLevel('Invalid level')
        );
    }
}
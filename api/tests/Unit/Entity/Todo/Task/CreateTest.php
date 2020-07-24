<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\Todo\Task;

use Domain\Todo\Entity\Schedule\Task\Description;
use Domain\Todo\Entity\Schedule\Task\ImportantLevel;
use Domain\Todo\Entity\Schedule\Task\Name;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskId;
use PHPUnit\Framework\TestCase;
use Tests\Builder\ScheduleBuilder;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $schedule = (new ScheduleBuilder())->main();

        $task = new Task(
            $id = TaskId::uuid4(),
            $schedule,
            $name = new Name('Task Name'),
            $description = new Description('Description'),
            $level = ImportantLevel::veryImportant()
        );

        $this->assertEquals($task->getId(), $id);
        $this->assertTrue($task->getId()->isEqual($id));

        $this->assertEquals($task->getSchedule(), $schedule);

        $this->assertEquals($task->getName(), $name);
        $this->assertTrue($task->getName()->isEqual($name));

        $this->assertEquals($task->getDescription(), $description);
        $this->assertTrue($task->getDescription()->isEqual($description));

        $this->assertFalse($task->isNotImportant());
        $this->assertFalse($task->isImportant());
        $this->assertTrue($task->isVeryImportant());
    }
}
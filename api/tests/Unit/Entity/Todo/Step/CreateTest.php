<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\Todo\Step;

use Domain\Todo\Entity\Schedule\Task\Step\StepName;
use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepId;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TaskBuilder;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = (new TaskBuilder())->build();

        $step = Step::new(
            new StepId(1),
            $task,
            $name = new StepName('Step name')
        );

        $this->assertNotEmpty($step->getId());

        $this->assertEquals($task, $step->getTask());

        $this->assertEquals($name, $step->getName());
        $this->assertTrue($step->getName()->isEqual($name));

        $this->assertNull($step->getSortOrder()->getValue());

        $step->changeSortOrder($order = new SortOrder(22));

        $this->assertEquals($order, $step->getSortOrder());
    }
}
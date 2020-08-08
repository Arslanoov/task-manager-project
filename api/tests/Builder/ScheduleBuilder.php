<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\Id;
use Domain\User\Entity\User\User;

final class ScheduleBuilder
{
    private Id $id;
    private User $user;

    public function __construct()
    {
        $this->id = Id::uuid4();
        $this->user = (new UserBuilder())->build();
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withUser(User $user): self
    {
        $builder = clone $this;
        $builder->user = $user;
        return $builder;
    }

    public function daily(): Schedule
    {
        return Schedule::daily(
            $this->id,
            $this->user
        );
    }

    public function main(): Schedule
    {
        return Schedule::main(
            $this->id,
            $this->user
        );
    }
}
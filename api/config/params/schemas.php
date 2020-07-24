<?php

use Domain\Todo\Entity\Schedule\ImportantLevel;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleId;
use Domain\Todo\Entity\Schedule\Type;
use Domain\User\Entity\User\User;
use Cycle\ORM;

return [
    'schemas' => [
        User::class => [
            ORM\Schema::ENTITY      => User::class,
            ORM\Schema::DATABASE    => 'default',
            ORM\Schema::TABLE       => 'user_users',
            ORM\Schema::PRIMARY_KEY => 'id',
            ORM\Schema::COLUMNS     => [
                'id'   => 'id',
                'login' => 'login',
                'email' => 'email',
                'status' => 'status'
            ],
            ORM\Schema::TYPECAST    => [
                'id' => 'string'
            ],
            ORM\Schema::RELATIONS   => []
        ],
        Schedule::class => [
            ORM\Schema::ENTITY      => Schedule::class,
            ORM\Schema::DATABASE    => 'default',
            ORM\Schema::TABLE       => 'todo_schedules',
            ORM\Schema::PRIMARY_KEY => 'id',
            ORM\Schema::COLUMNS     => [
                ScheduleId::class   => 'id',
                User::class => 'user',
                DateTimeImmutable::class => 'date',
                'tasksCount' => 'tasks_count',
                ImportantLevel::class => 'important_level',
                Type::class => 'type'
            ],
            ORM\Schema::TYPECAST    => [
                'id' => 'string'
            ],
            ORM\Schema::RELATIONS   => [
                'schedule' => [
                    ORM\Relation::TYPE   => ORM\Relation::HAS_ONE,
                    ORM\Relation::TARGET => 'schedule',
                    ORM\Relation::SCHEMA => [
                        ORM\Relation::CASCADE   => true,
                        ORM\Relation::INNER_KEY => 'id',
                        ORM\Relation::OUTER_KEY => 'user',
                    ]
                ]
            ]
        ]
    ]
];
<?php

use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\User\Entity\User\User;
use Cycle\ORM;
use Domain\User\Entity\User\UserMapper;

return [
    'schemas' => [
        User::class => [
            ORM\Schema::ENTITY      => User::class,
            ORM\Schema::DATABASE    => 'default',
            ORM\Schema::TABLE       => 'user_users',
            ORM\Schema::PRIMARY_KEY => 'id',
            ORM\Schema::COLUMNS     => [
                'id', 'login', 'email', 'status', 'password'
            ],
            ORM\Schema::TYPECAST    => [],
            ORM\Schema::RELATIONS   => [],
            ORM\Schema::MAPPER => UserMapper::class
        ],
        Schedule::class => [
            ORM\Schema::ENTITY      => Schedule::class,
            ORM\Schema::DATABASE    => 'default',
            ORM\Schema::TABLE       => 'todo_schedules',
            ORM\Schema::PRIMARY_KEY => 'id',
            ORM\Schema::COLUMNS     => [
                'id', 'user', 'date', 'tasks_count',
                'important_level', 'type'
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
                        ORM\Relation::INNER_KEY => 'user',
                        ORM\Relation::OUTER_KEY => 'id'
                    ]
                ]
            ]
        ],
        Task::class => [
            ORM\Schema::ENTITY      => Task::class,
            ORM\Schema::DATABASE    => 'default',
            ORM\Schema::TABLE       => 'todo_schedule_tasks',
            ORM\Schema::PRIMARY_KEY => 'id',
            ORM\Schema::COLUMNS     => [
                'id', 'schedule', 'name', 'description',
                'important_level'
            ],
            ORM\Schema::TYPECAST    => [
                'id' => 'string'
            ],
            ORM\Schema::RELATIONS   => [
                'task' => [
                    ORM\Relation::TYPE   => ORM\Relation::HAS_ONE,
                    ORM\Relation::TARGET => 'task',
                    ORM\Relation::SCHEMA => [
                        ORM\Relation::CASCADE   => true,
                        ORM\Relation::INNER_KEY => 'schedule',
                        ORM\Relation::OUTER_KEY => 'id'
                    ]
                ]
            ]
        ],
        Step::class => [
            ORM\Schema::ENTITY      => Step::class,
            ORM\Schema::DATABASE    => 'default',
            ORM\Schema::TABLE       => 'todo_schedule_task_steps',
            ORM\Schema::PRIMARY_KEY => 'id',
            ORM\Schema::COLUMNS     => [
                'id', 'task', 'name', 'sort_order', 'status'
            ],
            ORM\Schema::TYPECAST    => [
                'id' => 'integer'
            ],
            ORM\Schema::RELATIONS   => [
                'step' => [
                    ORM\Relation::TYPE   => ORM\Relation::HAS_ONE,
                    ORM\Relation::TARGET => 'step',
                    ORM\Relation::SCHEMA => [
                        ORM\Relation::CASCADE   => true,
                        ORM\Relation::INNER_KEY => 'task',
                        ORM\Relation::OUTER_KEY => 'id'
                    ]
                ]
            ]
        ]
    ]
];
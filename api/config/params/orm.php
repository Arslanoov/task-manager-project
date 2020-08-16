<?php

return [
    'doctrine' => [
        'dev_mode' => ENV === 'dev' ? true : false,
        'cache_dir' => 'var/cache/doctrine',
        'metadata_dirs' => ['src/Domain'],
        'connection' => [
            'url' => 'pgsql://app:secret@api-postgres:5432/app?charset=utf8',
        ],
        'types' => [
            // User
            Domain\User\Entity\User\IdType::NAME => Domain\User\Entity\User\IdType::class,
            Domain\User\Entity\User\LoginType::NAME => Domain\User\Entity\User\LoginType::class,
            Domain\User\Entity\User\EmailType::NAME => Domain\User\Entity\User\EmailType::class,
            Domain\User\Entity\User\PasswordType::NAME => Domain\User\Entity\User\PasswordType::class,
            Domain\User\Entity\User\StatusType::NAME => Domain\User\Entity\User\StatusType::class,

            // OAuth
            Domain\OAuth\Entity\Client\ClientType::NAME => Domain\OAuth\Entity\Client\ClientType::class,
            Domain\OAuth\Entity\Scope\ScopeType::NAME => Domain\OAuth\Entity\Scope\ScopeType::class,

            // Person
            Domain\Todo\Entity\Person\IdType::NAME => Domain\Todo\Entity\Person\IdType::class,
            Domain\Todo\Entity\Person\LoginType::NAME => Domain\Todo\Entity\Person\LoginType::class,

            // Schedule
            Domain\Todo\Entity\Schedule\IdType::NAME => Domain\Todo\Entity\Schedule\IdType::class,
            Domain\Todo\Entity\Schedule\NameType::NAME => Domain\Todo\Entity\Schedule\NameType::class,
            Domain\Todo\Entity\Schedule\TypeType::NAME => Domain\Todo\Entity\Schedule\TypeType::class,

            // Task
            Domain\Todo\Entity\Schedule\Task\IdType::NAME => Domain\Todo\Entity\Schedule\Task\IdType::class,
            Domain\Todo\Entity\Schedule\Task\NameType::NAME => Domain\Todo\Entity\Schedule\Task\NameType::class,
            Domain\Todo\Entity\Schedule\Task\DescriptionType::NAME => Domain\Todo\Entity\Schedule\Task\DescriptionType::class,
            Domain\Todo\Entity\Schedule\Task\ImportantLevelType::NAME => Domain\Todo\Entity\Schedule\Task\ImportantLevelType::class,
            Domain\Todo\Entity\Schedule\Task\StatusType::NAME => Domain\Todo\Entity\Schedule\Task\StatusType::class,

            // Step
            Domain\Todo\Entity\Schedule\Task\Step\IdType::NAME => Domain\Todo\Entity\Schedule\Task\Step\IdType::class,
            Domain\Todo\Entity\Schedule\Task\Step\NameType::NAME => Domain\Todo\Entity\Schedule\Task\Step\NameType::class,
            Domain\Todo\Entity\Schedule\Task\Step\SortOrderType::NAME => Domain\Todo\Entity\Schedule\Task\Step\SortOrderType::class,
            Domain\Todo\Entity\Schedule\Task\Step\StatusType::NAME => Domain\Todo\Entity\Schedule\Task\Step\StatusType::class
        ]
    ]
];
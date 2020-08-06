<?php

return [
    'doctrine' => [
        'dev_mode' => true,
        'cache_dir' => 'var/cache/doctrine',
        'metadata_dirs' => ['src/Domain'],
        'connection' => [
            'url' => 'pgsql://app:secret@api-postgres:5432/app?charset=utf8',
        ],
        'types' => [
            Domain\User\Entity\User\IdType::NAME => Domain\User\Entity\User\IdType::class,
            Domain\User\Entity\User\LoginType::NAME => Domain\User\Entity\User\LoginType::class,
            Domain\User\Entity\User\EmailType::NAME => Domain\User\Entity\User\EmailType::class,
            Domain\User\Entity\User\PasswordType::NAME => Domain\User\Entity\User\PasswordType::class,
            Domain\User\Entity\User\StatusType::NAME => Domain\User\Entity\User\StatusType::class,

            Domain\OAuth\Entity\Client\ClientType::NAME => Domain\OAuth\Entity\Client\ClientType::class,
            Domain\OAuth\Entity\Scope\ScopeType::NAME => Domain\OAuth\Entity\Scope\ScopeType::class
        ]
    ]
];
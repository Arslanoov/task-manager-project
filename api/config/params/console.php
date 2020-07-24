<?php

use App\Console\Commands;

return [
    'console' => [
        'commands' => [
            Commands\HelloCommand::class,
            Commands\Cycle\RunMigrationsCommand::class,
            Commands\Cycle\CreateMigrationCommand::class,
            Commands\Cycle\RollbackAllMigrationsCommand::class,
            Commands\Cycle\RollbackLatestMigrationCommand::class,
            Commands\User\CreateCommand::class
        ]
    ]
];
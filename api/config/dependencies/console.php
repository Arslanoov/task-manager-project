<?php

use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Infrastructure\App\Doctrine\Factory\DiffCommandFactory;
use Furious\Container\Container;

/** @var Container $container */

$container->set(DiffCommand::class, function (Container $container) {
    return (new DiffCommandFactory)($container);
});
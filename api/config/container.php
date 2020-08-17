<?php

use Laminas\ServiceManager\ServiceManager;

$dependencies = array_merge_recursive(
    require __DIR__ . '/dependencies/app.php',
    require __DIR__ . '/dependencies/console.php',
    require __DIR__ . '/dependencies/framework.php',
    require __DIR__ . '/dependencies/oauth.php',
    require __DIR__ . '/dependencies/orm.php',
    require __DIR__ . '/dependencies/photo.php'
);

$container = new ServiceManager($dependencies);

$config = [];
$configFiles = glob(__DIR__ . '/params/{*}.php', GLOB_BRACE);
foreach ($configFiles as $configFile) {
    $config += require $configFile;
}

$container->setService('config', $config);

return $container;
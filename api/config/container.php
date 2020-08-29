<?php

use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ConfigAggregator\ConfigAggregator;

$dependenciesAggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/dependencies/{*}.php'),
]);
$dependencies = $dependenciesAggregator->getMergedConfig();

$configAggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/params/{*}.php'),
]);
$config = $configAggregator->getMergedConfig();

$container = new ServiceManager($dependencies);
$container->setService('config', $config);

return $container;

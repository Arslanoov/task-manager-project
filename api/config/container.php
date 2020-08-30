<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ConfigAggregator\ConfigAggregator;

$dependencies = require_once(__DIR__ . '/dependencies.php');
$params = require_once(__DIR__ . '/params.php');

$container = new ServiceManager($dependencies);
$container->setService('config', $params);

return $container;

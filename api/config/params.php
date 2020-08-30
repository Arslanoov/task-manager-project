<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$configAggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/params/*.php'),
    new PhpFileProvider(__DIR__ . '/params/' . (getenv('ENV') ?? 'prod') . '/*.php')
]);

return $configAggregator->getMergedConfig();

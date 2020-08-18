<?php

declare(strict_types=1);

use Framework\Http\Application;
use Furious\HttpRunner\Runner;
use Laminas\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

(function () {
    define('ENV', 'dev');

    $container = require 'config/container.php';

    /** @var Application $app */
    $app = $container->get(Application::class);

    (require 'config/pipeline.php')($app);
    (require 'config/routes.php')($app);

    $request = (new ServerRequestFactory())->fromGlobals();
    $response = $app->handle($request);

    $response =
        $response->withHeader('X-Developer', 'Arslanoov');

    $runner = new Runner();
    $runner->run($response);
})();
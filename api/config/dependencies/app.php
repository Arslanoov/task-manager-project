<?php

use App\Http\Middleware\ErrorHandler;
use Framework\Http\Psr7\FuriousResponseFactory;
use Framework\Http\Psr7\ResponseFactory;
use Furious\Container\Container;

/** @var Container $container */

$container->set(ResponseFactory::class, function (Container $container) {
    return $container->get(FuriousResponseFactory::class);
});

$container->set(ErrorHandler::class, function (Container $container) {
    return new ErrorHandler(
        $container->get(ResponseFactory::class),
        boolval($container->get('config')['debug'])
    );
});
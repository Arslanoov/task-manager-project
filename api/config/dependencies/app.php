<?php

use App\Http\Middleware\ErrorHandler;
use Framework\Http\Psr7\FuriousResponseFactory;
use Framework\Http\Psr7\LaminasResponseFactory;
use Framework\Http\Psr7\ResponseFactory;
use Furious\Container\Container;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

/** @var Container $container */

$container->set(ResponseFactory::class, function (Container $container) {
    return $container->get(LaminasResponseFactory::class);
});

$container->set(ServerRequestInterface::class, function (Container $container) {
    return (new ServerRequestFactory())->fromGlobals();
});

$container->set(ErrorHandler::class, function (Container $container) {
    return new ErrorHandler(
        $container->get(ResponseFactory::class),
        boolval($container->get('config')['debug'])
    );
});
<?php

use App\Http\Middleware\ErrorHandler;
use Framework\Http\Psr7\LaminasResponseFactory;
use Framework\Http\Psr7\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'abstract_factories' => [
        ReflectionBasedAbstractFactory::class,
    ],
    'factories' => [
        ResponseFactory::class => function (ContainerInterface $container) {
            return $container->get(LaminasResponseFactory::class);
        },
        ServerRequestInterface::class => function (ContainerInterface $container) {
            return (new ServerRequestFactory())->fromGlobals();
        },
        ErrorHandler::class => function (ContainerInterface $container) {
            return new ErrorHandler(
                $container->get(ResponseFactory::class),
                boolval($container->get('config')['debug'])
            );
        }
    ]
];
<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Framework\Http\Middleware\LazyMiddlewareDecorator;
use Framework\Http\Middleware\SinglePassDecoratorMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionObject;

interface MiddlewareResolverInterface
{
    /**
     * @param $handler
     * @return LazyMiddlewareDecorator|SinglePassDecoratorMiddleware|MiddlewarePipeInterface|string
     */
    public function resolve($handler);
}

<?php

declare(strict_types=1);

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolverInterface;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DispatchMiddleware implements MiddlewareInterface
{
    private MiddlewareResolverInterface $resolver;

    public function __construct(MiddlewareResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Result $result */
        if (!$result = $request->getAttribute(Result::class)) {
            return $handler->handle($request);
        }
        $middleware = $this->resolver->resolve($result->getAction());
        return $middleware->process($request, $handler);
    }
}
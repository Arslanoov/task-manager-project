<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class InvalidArgumentHandler implements MiddlewareInterface
{
    private ResponseFactory $response;

    /**
     * InvalidArgumentHandler constructor.
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidArgumentException $e) {
            return $this->response->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
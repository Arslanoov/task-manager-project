<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    private ResponseFactory $response;
    private bool $debug;

    /**
     * ErrorHandler constructor.
     * @param ResponseFactory $response
     * @param bool $debug
     */
    public function __construct(ResponseFactory $response, bool $debug)
    {
        $this->response = $response;
        $this->debug = $debug;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            $code = $e->getCode() ?: 500;
            return $this->response->json([
                'error' => ($code !== 500 or $this->debug) ? $e->getMessage() : 'Something went wrong.'
            ], $code);
        }
    }
}
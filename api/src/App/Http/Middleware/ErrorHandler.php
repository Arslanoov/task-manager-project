<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use DateTimeImmutable;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    private ResponseFactory $response;
    private LoggerInterface $logger;
    private bool $debug;

    /**
     * ErrorHandler constructor.
     * @param ResponseFactory $response
     * @param LoggerInterface $logger
     * @param bool $debug
     */
    public function __construct(ResponseFactory $response, LoggerInterface $logger, bool $debug)
    {
        $this->response = $response;
        $this->logger = $logger;
        $this->debug = $debug;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            $code = $e->getCode() ?: 500;
            if ($code == 500) {
                $this->logger->error($e->getMessage(), [
                    'date' => new DateTimeImmutable(),
                    'trace' => $e->getTrace()
                ]);
            } else {
                $this->logger->warning($e->getMessage(), [
                    'date' => new DateTimeImmutable(),
                    'trace' => $e->getTrace()
                ]);
            }

            return $this->response->json([
                'error' => $this->canShowErrorMessage($code) ? $e->getMessage() : 'Something went wrong.'
            ], $code);
        }
    }

    private function canShowErrorMessage(int $code): bool
    {
        return $code !== 500 or $this->debug;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Domain\Todo\UseCase\Person\RemovePhoto\Command;
use Domain\Todo\UseCase\Person\RemovePhoto\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RemovePhotoAction implements RequestHandlerInterface
{
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * UploadPhotoAction constructor.
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(Handler $handler, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');

        $this->handler->handle(new Command($userId));

        return $this->response->json([], 204);
    }
}
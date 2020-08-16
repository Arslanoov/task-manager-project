<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Domain\Todo\UseCase\Person\ChangePhoto\Command;
use Domain\Todo\UseCase\Person\ChangePhoto\Handler;
use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UploadPhotoAction implements RequestHandlerInterface
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
        if (!$file = $request->getUploadedFiles()['file']) {
            throw new InvalidArgumentException('File not found');
        }

        $this->handler->handle(new Command($file, $userId));

        return $this->response->json([], 204);
    }
}
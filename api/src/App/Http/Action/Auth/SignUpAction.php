<?php

declare(strict_types=1);

namespace App\Http\Action\Auth;

use Domain\User\UseCase\User;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SignUpAction implements RequestHandlerInterface
{
    private User\SignUp\Handler $handler;
    private ResponseFactory $response;

    /**
     * SignUpAction constructor.
     * @param User\SignUp\Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(User\SignUp\Handler $handler, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $command = new User\SignUp\Command(
            $body['login'] ?? '',
            $body['email'] ?? '',
            $body['password'] ?? '',
        );

        $this->handler->handle($command);

        return $this->response->json([
            'email' => $command->email,
        ], 201);
    }
}
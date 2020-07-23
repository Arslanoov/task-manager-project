<?php

declare(strict_types=1);

namespace App\Http\Action\User;

use Domain\User\Entity\User;
use Domain\User\Entity\UserRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ListAction implements RequestHandlerInterface
{
    private UserRepository $users;
    private ResponseFactory $response;

    /**
     * ListAction constructor.
     * @param UserRepository $users
     * @param ResponseFactory $response
     */
    public function __construct(UserRepository $users, ResponseFactory $response)
    {
        $this->users = $users;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $users = $this->users->findAll();

        return $this->response->json([
            'users' => $users
        ]);
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Action\Auth;

use App\Service\UuidGenerator;
use Domain\Todo\UseCase\Person;
use Domain\User\UseCase\User;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SignUpAction implements RequestHandlerInterface
{
    private User\SignUp\Handler $userSignUpHandler;
    private Person\Create\Handler $personCreateHandler;
    private ResponseFactory $response;

    /**
     * SignUpAction constructor.
     * @param User\SignUp\Handler $userSignUpHandler
     * @param Person\Create\Handler $personCreateHandler
     * @param ResponseFactory $response
     */
    public function __construct(
        User\SignUp\Handler $userSignUpHandler,
        Person\Create\Handler $personCreateHandler,
        ResponseFactory $response
    )
    {
        $this->userSignUpHandler = $userSignUpHandler;
        $this->personCreateHandler = $personCreateHandler;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $id = (new UuidGenerator())->uuid4();
        $email = $body['email'] ?? '';

        $signUpCommand = new User\SignUp\Command(
            $id,
            $body['login'] ?? '',
            $email,
            $body['password'] ?? '',
        );

        $this->userSignUpHandler->handle($signUpCommand);

        $createCommand = new Person\Create\Command(
            $id,
            $body['login'] ?? ''
        );

        $this->personCreateHandler->handle($createCommand);

        return $this->response->json([
            'email' => $email
        ], 201);
    }
}
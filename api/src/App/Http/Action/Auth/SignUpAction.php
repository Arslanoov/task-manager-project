<?php

declare(strict_types=1);

namespace App\Http\Action\Auth;

use App\Service\Transaction;
use App\Service\UuidGenerator;
use Doctrine\DBAL\ConnectionException;
use Domain\Todo\UseCase\Person;
use Domain\User\UseCase\User;
use Exception;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SignUpAction implements RequestHandlerInterface
{
    private User\SignUp\Handler $userSignUpHandler;
    private Person\Create\Handler $personCreateHandler;
    private ResponseFactory $response;
    private Transaction $transaction;

    /**
     * SignUpAction constructor.
     * @param User\SignUp\Handler $userSignUpHandler
     * @param Person\Create\Handler $personCreateHandler
     * @param ResponseFactory $response
     * @param Transaction $transaction
     */
    public function __construct(User\SignUp\Handler $userSignUpHandler, Person\Create\Handler $personCreateHandler, ResponseFactory $response, Transaction $transaction)
    {
        $this->userSignUpHandler = $userSignUpHandler;
        $this->personCreateHandler = $personCreateHandler;
        $this->response = $response;
        $this->transaction = $transaction;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ConnectionException
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $id = (new UuidGenerator())->uuid4();
        $login = $body['login'] ?? '';
        $email = $body['email'] ?? '';

        $signUpCommand = new User\SignUp\Command($id, $login, $email, $body['password'] ?? '',);
        $createCommand = new Person\Create\Command($id, $login);

        $this->transaction->begin();
        try {
            $this->userSignUpHandler->handle($signUpCommand);
            $this->personCreateHandler->handle($createCommand);
            $this->transaction->commit();
        } catch (Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return $this->response->json([
            'email' => $email
        ], 201);
    }
}
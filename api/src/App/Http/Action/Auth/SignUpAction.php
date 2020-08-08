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
use Domain\Todo\UseCase\Schedule;

final class SignUpAction implements RequestHandlerInterface
{
    private User\SignUp\Handler $userSignUpHandler;
    private Person\Create\Handler $personCreateHandler;
    private Schedule\CreateMain\Handler $createScheduleHandler;
    private ResponseFactory $response;
    private Transaction $transaction;

    /**
     * SignUpAction constructor.
     * @param User\SignUp\Handler $userSignUpHandler
     * @param Person\Create\Handler $personCreateHandler
     * @param Schedule\CreateMain\Handler $createScheduleHandler
     * @param ResponseFactory $response
     * @param Transaction $transaction
     */
    public function __construct(
        User\SignUp\Handler $userSignUpHandler,
        Person\Create\Handler $personCreateHandler,
        Schedule\CreateMain\Handler $createScheduleHandler,
        ResponseFactory $response,
        Transaction $transaction
    )
    {
        $this->userSignUpHandler = $userSignUpHandler;
        $this->personCreateHandler = $personCreateHandler;
        $this->createScheduleHandler = $createScheduleHandler;
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
        $createPersonCommand = new Person\Create\Command($id, $login);
        $createScheduleCommand = new Schedule\CreateMain\Command($id);

        $this->transaction->begin();
        try {
            $this->userSignUpHandler->handle($signUpCommand);
            $this->personCreateHandler->handle($createPersonCommand);
            $this->createScheduleHandler->handle($createScheduleCommand);
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
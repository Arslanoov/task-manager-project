<?php

declare(strict_types=1);

namespace App\Http\Action\Auth;

use App\Service\TransactionInterface;
use App\Service\UuidGeneratorInterface;
use Doctrine\DBAL\ConnectionException;
use Domain\Todo\UseCase\Person;
use Domain\User\UseCase\User;
use Exception;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Domain\Todo\UseCase\Schedule;
use OpenApi\Annotations as OA;

final class SignUpAction implements RequestHandlerInterface
{
    private User\SignUp\Handler $userSignUpHandler;
    private Person\Create\Handler $personCreateHandler;
    private Schedule\CreateMain\Handler $createScheduleHandler;
    private ResponseFactory $response;
    private UuidGeneratorInterface $uuid;
    private TransactionInterface $transaction;

    /**
     * SignUpAction constructor.
     * @param User\SignUp\Handler $userSignUpHandler
     * @param Person\Create\Handler $personCreateHandler
     * @param Schedule\CreateMain\Handler $createScheduleHandler
     * @param ResponseFactory $response
     * @param UuidGeneratorInterface $uuid
     * @param TransactionInterface $transaction
     */
    public function __construct(
        User\SignUp\Handler $userSignUpHandler,
        Person\Create\Handler $personCreateHandler,
        Schedule\CreateMain\Handler $createScheduleHandler,
        ResponseFactory $response,
        UuidGeneratorInterface $uuid,
        TransactionInterface $transaction
    ) {
        $this->userSignUpHandler = $userSignUpHandler;
        $this->personCreateHandler = $personCreateHandler;
        $this->createScheduleHandler = $createScheduleHandler;
        $this->response = $response;
        $this->uuid = $uuid;
        $this->transaction = $transaction;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/signup",
     *     tags={"Sign Up"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"login", "email", "password"},
     *             @OA\Property(property="login", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *         )
     *     )
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ConnectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $id = $this->uuid->uuid4();
        $login = $body['login'] ?? '';
        $email = $body['email'] ?? '';

        $signUpCommand = new User\SignUp\Command($id, $login, $email, $body['password'] ?? '');
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

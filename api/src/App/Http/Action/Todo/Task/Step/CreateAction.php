<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use Doctrine\DBAL\DBALException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Step\DoctrineStepRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\DoctrineTaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Create\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Create\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class CreateAction implements RequestHandlerInterface
{
    private DoctrineStepRepository $steps;
    private DoctrineTaskRepository $tasks;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
     * @param DoctrineStepRepository $steps
     * @param DoctrineTaskRepository $tasks
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(DoctrineStepRepository $steps, DoctrineTaskRepository $tasks, Handler $handler, ResponseFactory $response)
    {
        $this->steps = $steps;
        $this->tasks = $tasks;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/todo/task/step/create",
     *     tags={"Create step"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"task_id", "name"},
     *             @OA\Property(property="task_id", type="string"),
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Errors",
     *          @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     * @throws DBALException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $taskId = $body['task_id'] ?? '';
        $name = $body['name'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));
        $this->canCreateStepForTask($request->getAttribute('oauth_user_id'), $task);

        $stepId = $this->steps->getNextId();
        $this->handler->handle(new Command($stepId->getValue(), $taskId, $name));

        return $this->response->json([
            'id' => $stepId->getValue()
        ], 201);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canCreateStepForTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
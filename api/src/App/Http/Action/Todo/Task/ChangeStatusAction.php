<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\DoctrineTaskRepository;
use Domain\Todo\UseCase\Schedule\Task\ChangeStatus\Command;
use Domain\Todo\UseCase\Schedule\Task\ChangeStatus\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class ChangeStatusAction implements RequestHandlerInterface
{
    private DoctrineTaskRepository $tasks;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * ChangeStatusAction constructor.
     * @param DoctrineTaskRepository $tasks
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(DoctrineTaskRepository $tasks, Handler $handler, ResponseFactory $response)
    {
        $this->tasks = $tasks;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/todo/task/change-status",
     *     tags={"Change task status"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"task_id", "status"},
     *             @OA\Property(property="task_id", type="string"),
     *             @OA\Property(property="status", type="string")
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
     *         response=204,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $id = $body['task_id'] ?? '';
        $status = $body['status'] ?? '';

        $task = $this->tasks->getById(new Id($id));
        $this->canChangeStatus($request->getAttribute('oauth_user_id'), $task);

        $this->handler->handle(new Command($id, $status));

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canChangeStatus(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Remove\Command;
use Domain\Todo\UseCase\Schedule\Task\Remove\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RemoveAction implements RequestHandlerInterface
{
    private TaskRepository $tasks;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
     * @param TaskRepository $tasks
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(TaskRepository $tasks, Handler $handler, ResponseFactory $response)
    {
        $this->tasks = $tasks;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $taskId = $body['task_id'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));
        $this->canRemoveTask($request->getAttribute('oauth_user_id'), $task);

        $this->handler->handle(new Command($taskId));

        return $this->response->json([]);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canRemoveTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
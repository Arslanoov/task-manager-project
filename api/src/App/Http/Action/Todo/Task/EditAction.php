<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Edit\Command;
use Domain\Todo\UseCase\Schedule\Task\Edit\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EditAction implements RequestHandlerInterface
{
    private Handler $handler;
    private TaskRepository $tasks;
    private ResponseFactory $response;

    /**
     * EditAction constructor.
     * @param Handler $handler
     * @param TaskRepository $tasks
     * @param ResponseFactory $response
     */
    public function __construct(Handler $handler, TaskRepository $tasks, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->tasks = $tasks;
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

        $taskId = $body['id'] ?? '';
        $name = $body['name'] ?? '';
        $description = $body['description'] ?? '';
        $level = $body['level'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));
        $this->canEditTask($request->getAttribute('oauth_user_id'), $task);

        $this->handler->handle(new Command($taskId, $name, $level, $description));

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canEditTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
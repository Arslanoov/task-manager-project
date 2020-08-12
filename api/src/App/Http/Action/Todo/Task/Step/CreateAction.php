<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Create\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Create\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreateAction implements RequestHandlerInterface
{
    private StepRepository $steps;
    private TaskRepository $tasks;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
     * @param StepRepository $steps
     * @param TaskRepository $tasks
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(StepRepository $steps, TaskRepository $tasks, Handler $handler, ResponseFactory $response)
    {
        $this->steps = $steps;
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
        $name = $body['name'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));

        $this->canCreateStepForTask($request->getAttribute('oauth_user_id'), $task);

        $this->handler->handle(new Command($taskId, $name));

        return $this->response->json([], 201);
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
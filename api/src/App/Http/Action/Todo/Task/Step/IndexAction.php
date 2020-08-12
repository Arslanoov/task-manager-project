<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexAction implements RequestHandlerInterface
{
    private TaskRepository $tasks;
    private StepRepository $steps;
    private ResponseFactory $response;

    /**
     * IndexAction constructor.
     * @param TaskRepository $tasks
     * @param StepRepository $steps
     * @param ResponseFactory $response
     */
    public function __construct(TaskRepository $tasks, StepRepository $steps, ResponseFactory $response)
    {
        $this->tasks = $tasks;
        $this->steps = $steps;
        $this->response = $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id') ?? '';
        $task = $this->tasks->getById(new Id($id));

        $this->canShowTask($request->getAttribute('oauth_user_id'), $task);

        $steps = $this->steps->getByTask($task);

        return $this->response->json([
            'id' => $task->getId()->getValue(),
            'steps' => $this->steps($steps)
        ]);
    }

    /**
     * @param array|Step $steps
     * @return array
     */
    private function steps(array $steps): array
    {
        return array_map(function (Step $step) {
            return [
                'id' => $step->getId()->getValue(),
                'name' => $step->getName()->getValue(),
                'sort_order' => $step->getSortOrder()->getValue(),
                'status' => $step->getStatus()->getValue()
            ];
        }, $steps);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canShowTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
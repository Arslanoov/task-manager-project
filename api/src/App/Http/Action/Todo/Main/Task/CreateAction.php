<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Main\Task;

use App\Exception\ForbiddenException;
use App\Service\UuidGenerator;
use Domain\Todo\Entity\Schedule\Id as ScheduleId;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Domain\Todo\UseCase\Schedule\Task;
use Domain\Todo\Entity\Schedule\Task\Id as TaskId;

final class CreateAction implements RequestHandlerInterface
{
    private Task\Create\Handler $handler;
    private ScheduleRepository $schedules;
    private TaskRepository $tasks;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
     * @param Task\Create\Handler $handler
     * @param ScheduleRepository $schedules
     * @param TaskRepository $tasks
     * @param ResponseFactory $response
     */
    public function __construct(Task\Create\Handler $handler, ScheduleRepository $schedules, TaskRepository $tasks, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->schedules = $schedules;
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

        $scheduleId = $body['schedule_id'] ?? '';
        $name = $body['name'] ?? '';
        $description = $body['description'] ?? 'desc';
        $level = $body['level'] ?? 'Important';

        $schedule = $this->schedules->getById(new ScheduleId($scheduleId));
        $this->canCreateTask($request->getAttribute('oauth_user_id'), $schedule);

        $taskId = (new UuidGenerator())->uuid1();

        $this->handler->handle(new Task\Create\Command(
            $scheduleId, $taskId, $name, $description, $level
        ));

        $task = $this->tasks->getById(new TaskId($taskId));

        return $this->response->json([
            'id' => $task->getId()->getValue(),
            'name' => $task->getName()->getValue(),
            'description' => $task->getDescription()->getValue(),
            'level' => $task->getLevel()->getValue(),
            'status' => $task->getStatus()->getValue()
        ]);
    }

    /**
     * @param string $userId
     * @param Schedule $schedule
     * @throws ForbiddenException
     */
    private function canCreateTask(string $userId, Schedule $schedule): void
    {
        if ($userId !== $schedule->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
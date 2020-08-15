<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\UseCase\Schedule\CreateDaily\Command;
use Domain\Todo\UseCase\Schedule\CreateDaily\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetTodayAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * GetTodayAction constructor.
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(ScheduleRepository $schedules, PersonRepository $persons, Handler $handler, ResponseFactory $response)
    {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->handler = $handler;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');

        $person = $this->persons->getById(new Id($userId));
        $schedule = $this->schedules->findPersonTodaySchedule($person);
        if (!$schedule) {
            $this->handler->handle(new Command($userId));
            $schedule = $this->schedules->findPersonTodaySchedule($person);
        }

        return $this->response->json([
            'id' => $schedule->getId()->getValue(),
            'tasks' => $this->tasks($schedule),
            'tasksCount' => $schedule->getTasksCount()
        ]);
    }

    private function tasks(Schedule $schedule): array
    {
        return array_map(function (Task $task) {
            return [
                'id' => $task->getId()->getValue(),
                'name' => $task->getName()->getValue(),
                'description' => $task->getDescription()->getValue(),
                'importantLevel' => $task->getLevel()->getValue(),
                'status' => $task->getStatus()->getValue(),
                'stepsCount' => $task->getStepsCollection()->count(),
                'finishedSteps' => $task->getFinishedSteps()
            ];
        }, array_reverse($schedule->getTasks()));
    }
}
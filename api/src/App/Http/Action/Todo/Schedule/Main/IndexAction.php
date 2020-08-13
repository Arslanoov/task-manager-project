<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Main;

use DateTimeImmutable;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\Login;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\User\Entity\User\Id as UserId;
use Domain\User\Entity\User\UserRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private UserRepository $users;
    private ResponseFactory $response;

    /**
     * IndexAction constructor.
     * @param ScheduleRepository $schedules
     * @param UserRepository $users
     * @param ResponseFactory $response
     */
    public function __construct(ScheduleRepository $schedules, UserRepository $users, ResponseFactory $response)
    {
        $this->schedules = $schedules;
        $this->users = $users;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->users->getById(new UserId($request->getAttribute('oauth_user_id') ?? ''));
        $schedule = $this->schedules->getPersonMainSchedule(new Person(
            new Id($user->getId()->getValue()),
            new Login($user->getLogin()->getRaw())
        ));

        return $this->response->json([
            'id' => $schedule->getId()->getValue(),
            'tasks' => $this->tasks($schedule),
            'tasks_count' => $schedule->getTasksCount()
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
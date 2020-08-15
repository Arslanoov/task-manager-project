<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use App\Exception\ForbiddenException;
use App\Service\Date;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Id as ScheduleId;
use Domain\Todo\Entity\Person\Id as PersonId;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\UseCase\Schedule\CreateByDate\Command;
use Domain\Todo\UseCase\Schedule\CreateByDate\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetPreviousScheduleAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * GetNextSchedule constructor.
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

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');
        $scheduleId = $request->getAttribute('id') ?? '';

        $schedule = $this->schedules->getById(new ScheduleId($scheduleId));
        $person = $this->persons->getById(new PersonId($userId));
        $this->canGetPrevious($userId, $schedule);

        $previousSchedule = $this->schedules->findPreviousSchedule($person, $schedule);
        if (!$previousSchedule) {
            $this->handler->handle(new Command(
                $schedule->getDate()->modify('-1 day'),
                $userId
            ));
            $previousSchedule = $this->schedules->findPreviousSchedule($person, $schedule);
        }

        return $this->response->json([
            'id' => $previousSchedule->getId()->getValue(),
            'date' => [
                'day' => $previousSchedule->getDate()->format('d'),
                'month' => $previousSchedule->getDate()->format('m') - 1,
                'year' => $previousSchedule->getDate()->format('Y'),
                'string' => $previousSchedule->getDate()->format('d') . 'th ' .
                    Date::MONTHS[intval($previousSchedule->getDate()->format('m')) - 1]
            ],
            'tasksCount' => $previousSchedule->getTasksCount(),
            'tasks' => $this->tasks($previousSchedule)
        ]);
    }

    /**
     * @param string $userId
     * @param Schedule $schedule
     * @throws ForbiddenException
     */
    private function canGetPrevious(string $userId, Schedule $schedule): void
    {
        if ($userId !== $schedule->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
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
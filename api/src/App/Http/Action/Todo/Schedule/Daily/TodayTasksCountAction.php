<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TodayTasksCountAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private ResponseFactory $response;

    /**
     * TasksCountAction constructor.
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param ResponseFactory $response
     */
    public function __construct(ScheduleRepository $schedules, PersonRepository $persons, ResponseFactory $response)
    {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $person = $this->persons->getById(new Id($request->getAttribute('oauth_user_id') ?? ''));

        $schedule = $this->schedules->findPersonTodaySchedule($person);
        $tasksCount = $schedule ? $schedule->getTasksCount() : 0;

        return $this->response->json([
            'count' => $tasksCount
        ]);
    }
}
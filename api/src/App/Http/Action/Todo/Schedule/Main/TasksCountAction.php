<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Main;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\Login;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\User\Entity\User\Id as UserId;
use Domain\User\Entity\User\UserRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TasksCountAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private UserRepository $users;
    private ResponseFactory $response;

    /**
     * TasksCountAction constructor.
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
            'count' => $schedule->getTasksCount()
        ]);
    }
}
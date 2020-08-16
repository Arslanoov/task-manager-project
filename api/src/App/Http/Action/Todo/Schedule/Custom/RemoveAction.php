<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Custom;

use App\Exception\ForbiddenException;
use Domain\Exception\DomainException;
use Domain\Todo\Entity\Schedule\Id;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\UseCase\Schedule\Remove\Command;
use Domain\Todo\UseCase\Schedule\Remove\Handler;
use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RemoveAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
     * @param ScheduleRepository $schedules
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(ScheduleRepository $schedules, Handler $handler, ResponseFactory $response)
    {
        $this->schedules = $schedules;
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

        $userId = $request->getAttribute('oauth_user_id');
        $id = $body['id'] ?? '';

        $schedule = $this->schedules->getById(new Id($id));
        $this->canRemove($userId, $schedule);

        if ($schedule->isNotCustom()) {
            throw new InvalidArgumentException('Schedule is not custom');
        }

        $this->handler->handle(new Command($id));

        return $this->response->json([]);
    }

    private function canRemove(string $userId, Schedule $schedule): void
    {
        if ($userId !== $schedule->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
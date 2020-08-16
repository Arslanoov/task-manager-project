<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Custom;

use App\Service\UuidGenerator;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\UseCase\Schedule\CreateCustom\Command;
use Domain\Todo\UseCase\Schedule\CreateCustom\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreateAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $userId = $request->getAttribute('oauth_user_id');
        $id = (new UuidGenerator())->uuid4();
        $name = $body['name'] ?? '';

        $this->handler->handle(new Command($id, $userId, $name));

        return $this->response->json([
            'id' => $id
        ], 201);
    }
}
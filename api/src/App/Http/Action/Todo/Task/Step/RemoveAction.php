<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Remove\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Remove\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RemoveAction implements RequestHandlerInterface
{
    private StepRepository $steps;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
     * @param StepRepository $steps
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(StepRepository $steps, Handler $handler, ResponseFactory $response)
    {
        $this->steps = $steps;
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
        $id = intval($body['id'] ?? '');

        $step = $this->steps->getById(new Id($id));
        $this->canDeleteStep($request->getAttribute('oauth_user_id'), $step);

        $this->handler->handle(new Command($id));

        return $this->response->json([]);
    }

    /**
     * @param string $userId
     * @param Step $step
     * @throws ForbiddenException
     */
    private function canDeleteStep(string $userId, Step $step): void
    {
        if ($userId !== $step->getTask()->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
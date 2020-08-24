<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\DoctrineStepRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Down\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Down\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class DownAction implements RequestHandlerInterface
{
    private DoctrineStepRepository $steps;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
     * @param DoctrineStepRepository $steps
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(DoctrineStepRepository $steps, Handler $handler, ResponseFactory $response)
    {
        $this->steps = $steps;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Patch(
     *     path="/todo/task/step/down",
     *     tags={"Move down step"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id"},
     *             @OA\Property(property="id", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Lower step not found",
     *          @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Errors",
     *          @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *          )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $id = intval($body['id'] ?? '');

        $step = $this->steps->getById(new Id($id));
        $this->canDownStep($request->getAttribute('oauth_user_id'), $step);

        $this->handler->handle(new Command($id));

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Step $step
     * @throws ForbiddenException
     */
    private function canDownStep(string $userId, Step $step): void
    {
        if ($userId !== $step->getTask()->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
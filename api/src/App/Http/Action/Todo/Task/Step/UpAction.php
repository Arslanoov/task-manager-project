<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Up\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Up\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class UpAction implements RequestHandlerInterface
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
     * @OA\Patch(
     *     path="/todo/task/step/up",
     *     tags={"Move up step"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id"},
     *             @OA\Property(property="id", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Higher step not found",
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
        $this->canUpStep($request->getAttribute('oauth_user_id'), $step);

        $this->handler->handle(new Command($id));

        return $this->response->json([]);
    }

    /**
     * @param string $userId
     * @param Step $step
     * @throws ForbiddenException
     */
    private function canUpStep(string $userId, Step $step): void
    {
        if ($userId !== $step->getTask()->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}

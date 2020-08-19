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
use OpenApi\Annotations as OA;

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

    /**
     * @OA\Post(
     *     path="/todo/custom/create",
     *     tags={"Custom schedule create"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
     *             @OA\Property(property="name", type="string")
     *         )
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
     *         response=201,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
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
<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Custom;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\Login;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Person\DoctrinePersonRepository;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\DoctrineScheduleRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class IndexAction implements RequestHandlerInterface
{
    private DoctrineScheduleRepository $schedules;
    private DoctrinePersonRepository $persons;
    private ResponseFactory $response;

    /**
     * IndexAction constructor.
     * @param DoctrineScheduleRepository $schedules
     * @param DoctrinePersonRepository $persons
     * @param ResponseFactory $response
     */
    public function __construct(DoctrineScheduleRepository $schedules, DoctrinePersonRepository $persons, ResponseFactory $response)
    {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->response = $response;
    }

    /**
     * @OA\Get(
     *     path="/todo/custom/list",
     *     tags={"Custom schedules list"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="schedules", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="tasksCount", type="integer")
     *             ))
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $person = $this->persons->getById(new Id($request->getAttribute('oauth_user_id') ?? ''));
        $schedules = $this->schedules->getPersonCustomSchedules($person);

        return $this->response->json([
            'schedules' => $this->schedules($schedules)
        ]);
    }

    private function schedules(array $schedules): array
    {
        return array_map(function (Schedule $schedule) {
            return [
                'id' => $schedule->getId()->getValue(),
                'name' => $schedule->getName()->getValue(),
                'tasksCount' => $schedule->getTasksCount()
            ];
        }, $schedules);
    }
}
<?php

namespace App\Task\Infrastructure\Controller;

use App\Shared\Infrastructure\Service\RequestService;
use App\Task\Application\StartTimer\StartTimerCommand;
use App\Task\Application\StartTimer\StartTimerCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StartTimerController extends AbstractController
{
    public function __construct(
        private readonly StartTimerCommandHandler $handler
    ) {}

    #[Route(path: '/timer/start', name: 'timer_start', methods: "POST")]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->handler->__invoke(new StartTimerCommand(RequestService::getField($request, 'name')));
            
            return new JsonResponse([
                "status" => true,
                "message" => "Timer started!"
            ], 201);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

}
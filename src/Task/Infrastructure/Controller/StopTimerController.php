<?php

namespace App\Task\Infrastructure\Controller;

use App\Shared\Infrastructure\Service\RequestService;
use App\Task\Application\StopTimer\StopTimerCommand;
use App\Task\Application\StopTimer\StopTimerCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StopTimerController extends AbstractController
{
    public function __construct(
        private readonly StopTimerCommandHandler $handler
    ) {}

    #[Route(path: '/timer/stop', name: 'timer_stop', methods: "POST")]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->handler->__invoke(new StopTimerCommand(RequestService::getField($request, 'name')));
            
            return new JsonResponse([
                "status" => true,
                "message" => "Timer stoped!"
            ], 201);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

}
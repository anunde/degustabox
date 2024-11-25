<?php

namespace App\Task\Infrastructure\Controller;

use App\Task\Application\ListTasks\ListTasksQuery;
use App\Task\Application\ListTasks\ListTasksQueryHandler;
use App\Task\Infrastructure\Transformer\ListTasksTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ListTaskController extends AbstractController
{
    public function __construct(
        private readonly ListTasksQueryHandler $handler,
        private readonly ListTasksTransformer $transfomer
    ) {}

    #[Route(path: '/tasks', name: 'list_tasks', methods: "GET")]
    public function __invoke(): JsonResponse
    {
        try {
            $tasks = $this->handler->__invoke(new ListTasksQuery(new \DateTime()));
            
            return new JsonResponse([
                "status" => true,
                "data" => $this->transfomer->transform($tasks)
            ], 201);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

}
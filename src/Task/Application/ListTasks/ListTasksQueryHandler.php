<?php

namespace App\Task\Application\ListTasks;

use App\Task\Domain\Repository\Read\TaskReadRepositoryInterface;

final readonly class ListTasksQueryHandler
{
    public function __construct(
        private readonly TaskReadRepositoryInterface $repository
    ) {}

    public function __invoke(ListTasksQuery $query): array 
    {
        return $this->repository->getAllForDate($query->getToday()->format('Y-m-d'));
    }
}
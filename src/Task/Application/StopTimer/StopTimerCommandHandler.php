<?php

namespace App\Task\Application\StopTimer;

use App\Shared\Domain\Exception\NotFoundException;
use App\Task\Domain\Entity\Task;
use App\Task\Domain\Entity\TimeEntry;
use App\Task\Domain\Repository\Write\TaskWriteRepositoryInterface;

final readonly class StopTimerCommandHandler
{
    public function __construct(
        private readonly TaskWriteRepositoryInterface $repository
    ) {}

    public function __invoke(StopTimerCommand $command): void
    {
        if (null === $task = $this->repository->findOneByName($command->getName())) {
            throw new NotFoundException("No timer found");
        }

        $task->stopActiveTimeEntry();
        $this->repository->save($task);
    }
}

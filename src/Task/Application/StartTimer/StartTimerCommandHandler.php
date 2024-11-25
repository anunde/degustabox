<?php

namespace App\Task\Application\StartTimer;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Entity\TimeEntry;
use App\Task\Domain\Repository\Write\TaskWriteRepositoryInterface;

final readonly class StartTimerCommandHandler
{
    public function __construct(
        private readonly TaskWriteRepositoryInterface $repository
    ) {}

    public function __invoke(StartTimerCommand $command): void 
    {
        $entry = TimeEntry::create(new \DateTime());

        if(null === $task = $this->repository->findOneByName($command->getName())) {
            $task = Task::create($command->getName());
        }

        $task->addTimeEntry($entry);
        $this->repository->save($task);
    }
}
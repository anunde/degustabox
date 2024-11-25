<?php 

namespace App\Task\Infrastructure\Repository\Write;

use App\Shared\Infrastructure\DataSource\DoctrineDataSource;
use App\Task\Domain\Entity\Task;
use App\Task\Domain\Repository\Write\TaskWriteRepositoryInterface;

class TaskWriteRepository implements TaskWriteRepositoryInterface {
    private DoctrineDataSource $doctrineDataSource;

    public function __construct(DoctrineDataSource $doctrineDataSource)
    {
        $this->doctrineDataSource = $doctrineDataSource;
    }

    public function save(Task $task): void
    {
        $this->doctrineDataSource->persist($task, true);
    }

    public function findOneByName(string $name): ?Task
    {
        $queryBuilder = $this->doctrineDataSource->entityManager()->createQueryBuilder();

        $queryBuilder->select('t')
            ->from(Task::class, 't')
            ->where('t.name = :name')
            ->setParameter('name', $name);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
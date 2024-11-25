<?php 

namespace App\Task\Domain\Repository\Write;

use App\Task\Domain\Entity\Task;

interface TaskWriteRepositoryInterface {
    public function save(Task $user): void;    
    public function findOneByName(string $name): ?Task;
}
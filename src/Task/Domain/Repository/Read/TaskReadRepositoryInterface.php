<?php 

namespace App\Task\Domain\Repository\Read;

interface TaskReadRepositoryInterface {
    public function getAllForDate(string $date): array;
}
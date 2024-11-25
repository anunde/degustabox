<?php

namespace App\Task\Infrastructure\Repository\Read;

use App\Shared\Infrastructure\DataSource\DoctrineDataSource;
use App\Task\Domain\Repository\Read\TaskReadRepositoryInterface;

class TaskReadRepository implements TaskReadRepositoryInterface
{
    private DoctrineDataSource $doctrineDataSource;

    public function __construct(DoctrineDataSource $doctrineDataSource)
    {
        $this->doctrineDataSource = $doctrineDataSource;
    }

    public function getAllForDate(string $date): array
    {
        $sql = "
        SELECT 
            t.Task_Id AS task_id,
            t.Task_Name AS task_name,
            t.Task_CreatedAt AS task_created_at,
            te.TE_Id AS time_entry_id,
            te.TE_Start AS time_entry_start,
            te.TE_End AS time_entry_end,
            te.TE_Duration AS time_entry_duration
        FROM task t
        LEFT JOIN time_entry te ON t.Task_Id = te.Task_Id
        WHERE DATE(t.Task_CreatedAt) = :date
        ORDER BY t.Task_CreatedAt DESC;
    ";

        $conn = $this->doctrineDataSource->entityManager()->getConnection();
        $stmt = $conn->executeQuery($sql, ['date' => $date]);

        $rawResults = $stmt->fetchAllAssociative();

        return $this->groupTasksWithTimeEntries($rawResults);
    }

    private function groupTasksWithTimeEntries(array $rawResults): array
    {
        $tasks = [];

        foreach ($rawResults as $row) {
            $taskId = $row['task_id'];

            if (!isset($tasks[$taskId])) {
                $tasks[$taskId] = [
                    'id' => $row['task_id'],
                    'name' => $row['task_name'],
                    'created_at' => $row['task_created_at'],
                    'time_entries' => [],
                ];
            }

            if (!empty($row['time_entry_id'])) {
                $tasks[$taskId]['time_entries'][] = [
                    'id' => $row['time_entry_id'],
                    'start' => $row['time_entry_start'],
                    'end' => $row['time_entry_end'],
                    'duration' => $row['time_entry_duration'],
                ];
            }
        }

        foreach ($tasks as &$task) {
            usort($task['time_entries'], function ($a, $b) {
                return $a['start'] <=> $b['start'];
            });
        }

        return array_values($tasks);
    }
}

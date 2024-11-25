<?php

namespace App\Task\Infrastructure\Transformer;

use App\Shared\Domain\Transformer\TransformerInterface;
use DateTime;

class ListTasksTransformer implements TransformerInterface
{

    public function transform($tasks): array
    {
        $result = [];
        foreach ($tasks as $task) {
            $row = [
                'name' => $task['name'],
                'entries' => []
            ];

            foreach($task['time_entries'] as $entry) {
                $start = new DateTime($entry["start"]);
                $end = new DateTime($entry["end"]);

                $row['entries'][] = [
                    "start" => $start->format('H:i'),
                    "end" => $end->format('H:i'),
                    "duration" => $entry["duration"]
                ];
            }

            $result[] = $row;
        }

        return $result;
    }
}
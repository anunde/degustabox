<?php

namespace App\Task\Infrastructure\Command;

use App\Task\Application\ListTasks\ListTasksQuery;
use App\Task\Application\ListTasks\ListTasksQueryHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskListCommand extends Command
{
    protected static $defaultName = 'app:task:list';

    public function __construct(
        private readonly ListTasksQueryHandler $listHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Lists all tasks with their status, start time, end time, and elapsed time');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $tasks = $this->listHandler->__invoke(new ListTasksQuery(new \DateTime()));

            $output->writeln("<info>Task List:</info>");
            $output->writeln(str_pad("Name", 20) . str_pad("Status", 10) . str_pad("Start Time", 10) . str_pad("End Time", 10) . str_pad("Elapsed Time", 15));
            $output->writeln(str_repeat("-", 65));

            foreach ($tasks as $task) {
                $status = $this->calculateStatus($task['time_entries']);

                $totalElapsedTime = $this->calculateTotalElapsedTime($task['time_entries']);

                $startTime = $this->formatTime($task['time_entries'][0]['start'] ?? null);
                $endTime = $this->formatTime(end($task['time_entries'])['end'] ?? null);

                $output->writeln(
                    str_pad($task['name'], 20) .
                    str_pad($status, 10) .
                    str_pad($startTime, 10) .
                    str_pad($endTime, 10) .
                    str_pad($totalElapsedTime, 15)
                );
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln("<error>An error occurred: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }


    private function calculateStatus(array $timeEntries): string
    {
        foreach ($timeEntries as $entry) {
            if ($entry['end'] === null) {
                return 'Active';
            }
        }
        return 'Completed';
    }


    private function calculateTotalElapsedTime(array $timeEntries): string
    {
        $totalSeconds = 0;

        foreach ($timeEntries as $entry) {
            $totalSeconds += $entry['duration'] ?? 0;
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function formatTime(?string $datetime): string
    {
        if ($datetime === null) {
            return 'N/A';
        }

        try {
            $date = new \DateTime($datetime);
            return $date->format('H:i');
        } catch (\Exception $e) {
            return 'Invalid';
        }
    }
}

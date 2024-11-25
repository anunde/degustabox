<?php

namespace App\Task\Infrastructure\Command;

use App\Task\Application\StartTimer\StartTimerCommand;
use App\Task\Application\StartTimer\StartTimerCommandHandler;
use App\Task\Application\StopTimer\StopTimerCommand;
use App\Task\Application\StopTimer\StopTimerCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskActionCommand extends Command
{
    protected static $defaultName = 'app:task:action';

    public function __construct(
        private readonly StartTimerCommandHandler $startHandler,
        private readonly StopTimerCommandHandler $stopHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Starts or ends a task timer')
            ->addArgument('action', InputArgument::REQUIRED, 'The action to perform (start or end)')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the task');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = $input->getArgument('action');
        $name = $input->getArgument('name');

        try {
            if ($action === 'start') {
                $this->startHandler->__invoke(new StartTimerCommand($name));
                $output->writeln("<info>Task '{$name}' started successfully.</info>");
            } elseif ($action === 'end') {
                $this->stopHandler->__invoke(new StopTimerCommand($name));
                $output->writeln("<info>Task '{$name}' ended successfully.</info>");
            } else {
                $output->writeln("<error>Invalid action. Use 'start' or 'end'.</error>");
                return Command::INVALID;
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln("<error>An error occurred: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}

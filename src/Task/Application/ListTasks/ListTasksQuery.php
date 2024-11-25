<?php

namespace App\Task\Application\ListTasks;

final class ListTasksQuery
{
    public function __construct(
        private readonly \DateTime $today
    ) {}

    public function getToday(): \DateTime
    {
        return $this->today;
    }
}
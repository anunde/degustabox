<?php

namespace App\Task\Application\StartTimer;

final class StartTimerCommand
{
    public function __construct(
        private readonly string $name
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}
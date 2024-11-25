<?php

namespace App\Task\Application\StopTimer;

final class StopTimerCommand
{
    public function __construct(
        private readonly string $name
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}
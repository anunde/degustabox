<?php

namespace App\Task\Infrastructure\Doctrine;

use App\Shared\Infrastructure\UuidType;
use App\Task\Domain\Entity\TaskId;

class TaskIdType extends UuidType
{
    const NAME = 'task_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TaskId::class;
    }
}
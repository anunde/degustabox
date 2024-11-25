<?php

namespace App\Task\Infrastructure\Doctrine;

use App\Shared\Infrastructure\UuidType;
use App\Task\Domain\Entity\TimeEntryId;

class TimeEntryIdType extends UuidType
{
    const NAME = 'time_entry_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TimeEntryId::class;
    }
}
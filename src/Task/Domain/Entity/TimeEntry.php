<?php

namespace App\Task\Domain\Entity;

use App\Shared\Domain\Entity\Entity;
use App\Shared\Domain\Exception\NotFoundException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "time_entry")]
class TimeEntry extends Entity {
    #[ORM\Id]
    #[ORM\Column(name: "TE_Id", type: "time_entry_id", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private TimeEntryId $id;

    #[ORM\Column(name: "TE_Start", type: "datetime")]
    private \DateTime $start;

    #[ORM\Column(name: "TE_End", type: "datetime", nullable: true)]
    private ?\DateTime $end;

    #[ORM\Column(name: "TE_Duration", type: "integer", nullable: true)]
    private ?int $duration;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: "timeEntries")]
    #[ORM\JoinColumn(name: "Task_Id", referencedColumnName: "Task_Id", nullable: false)]
    private Task $task;

    public function __construct(
        TimeEntryId $id,
        TimeEntryStart $start,
        ?TimeEntryStart $end,
        ?TimeEntryDuration $duration
    ) {
        $this->id = $id;
        $this->start = $start->value();
        $this->end = $end?->value();
        $this->duration = $duration?->value();
    }

    public static function create(
        $start
    ): TimeEntry {
        return new self(
            new TimeEntryId(TimeEntryId::random()),
            new TimeEntryStart($start),
            null,
            null
        );
    }

    public function getId(): TimeEntryId {
        return $this->id;
    }

    public function getStart(): \DateTime {
        return $this->start;
    }

    public function getEnd(): ?\DateTime {
        return $this->end;
    }

    public function getDuration(): ?int {
        return $this->duration;
    }

    public function setEnd(TimeEntryStart $end): void {
        $this->end = $end->value();
    }

    public function setDuration(TimeEntryDuration $duration): void {
        $this->duration = $duration->value();
    }

    public function stopTimer(): void {
        if ($this->end !== null) {
            throw new NotFoundException("Timer is already stopped.");
        }

        $this->setEnd(new TimeEntryStart(new \DateTime()));

        if (!$this->start || !$this->end) {
            throw new NotFoundException("Cannot calculate duration without both start and end times.");
        }

        $interval = $this->start->diff($this->end);
        $totalSeconds = $interval->s 
                      + ($interval->i * 60) 
                      + ($interval->h * 3600)
                      + ($interval->d * 86400);

        $this->setDuration(new TimeEntryDuration($totalSeconds));
    }


    public function setTask(Task $task): void {
        $this->task = $task;
    }
}

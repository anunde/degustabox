<?php

namespace App\Task\Domain\Entity;

use App\Shared\Domain\Entity\Entity;
use App\Shared\Domain\Exception\NotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "task")]
class Task extends Entity {
    #[ORM\Id]
    #[ORM\Column(name: "Task_Id", type: "task_id", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private TaskId $id;

    #[ORM\Column(name: "Task_Name", type: "string", unique: true)]
    private string $name;

    #[ORM\Column(name: "Task_createdAt", type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\OneToMany(mappedBy: "task", targetEntity: TimeEntry::class, cascade: ["persist", "remove"])]
    private Collection $timeEntries;

    public function __construct(
        TaskId $id,
        TaskName $name,
        TaskCreatedAt $createdAt
    ) {
        $this->id = $id;
        $this->name = $name->value();
        $this->createdAt = $createdAt->value();
        $this->timeEntries = new ArrayCollection();
    }

    public static function create(
        $name
    ): Task {
        return new self(
            new TaskId(TaskId::random()),
            new TaskName($name),
            new TaskCreatedAt(new \DateTime())
        );
    }

    public function getId(): TaskId {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getTimeEntries(): Collection {
        return $this->timeEntries;
    }

    public function addTimeEntry(TimeEntry $timeEntry): void {
        $this->timeEntries[] = $timeEntry;
        $timeEntry->setTask($this);
    }

    public function stopActiveTimeEntry(): void 
    {
        $actived = $this->timeEntries->filter(function (TimeEntry $timeEntry) {
            return $timeEntry->getEnd() === null;
        })->first();

        if(!$actived) {
            throw new NotFoundException("No active timer found for this task");
        }

        $actived->stopTimer();
    }
}
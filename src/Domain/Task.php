<?php

declare(strict_types=1);

namespace Domain;

class Task
{
    private string $id;
    private string $title;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $title)
    {
        $this->id = uniqid('task_', true);
        $this->title = $title;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}

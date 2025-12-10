<?php

declare(strict_types=1);

namespace Domain;

interface TaskRepository
{
    public function save(Task $task): void;

    public function findById(string $id): ?Task;

    public function findAll(): array;
}

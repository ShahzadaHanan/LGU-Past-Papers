<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ClassBookingRepository;

class ClassBookingService
{
    public function __construct(private ClassBookingRepository $repository) {}

    public function getAll(): array { return $this->repository->all(); }
    public function getById(int $id) { return $this->repository->find($id); }
    public function create(array $data): bool { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }
    public function updateStatus(int $id, string $status): bool { return $this->repository->updateStatus($id, $status); }
}

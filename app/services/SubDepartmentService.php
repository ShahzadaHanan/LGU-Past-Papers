<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SubDepartmentRepository;
use App\Models\SubDepartment;

class SubDepartmentService
{
    private SubDepartmentRepository $repository;

    public function __construct(SubDepartmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSubDepartments(): array
    {
        return $this->repository->all();
    }

    public function getSubDepartmentById(int $id): ?SubDepartment
    {
        return $this->repository->find($id);
    }

    public function createSubDepartment(array $data): bool
    {
        return $this->repository->create([
            'department_id' => (int) ($data['department_id'] ?? 0),
            'name' => trim((string) ($data['name'] ?? '')),
            'slug' => \App\Helpers\Slug::make((string) ($data['name'] ?? '')),
            'description' => $data['description'] ?? null,
        ]);
    }

    public function updateSubDepartment(int $id, array $data): bool
    {
        return $this->repository->update($id, [
            'department_id' => (int) ($data['department_id'] ?? 0),
            'name' => trim((string) ($data['name'] ?? '')),
            'slug' => \App\Helpers\Slug::make((string) ($data['name'] ?? '')),
            'description' => $data['description'] ?? null,
        ]);
    }

    public function deleteSubDepartment(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

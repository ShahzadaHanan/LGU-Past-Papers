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
        return $this->repository->create($data);
    }

    public function updateSubDepartment(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteSubDepartment(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

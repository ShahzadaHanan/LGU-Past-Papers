<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SubjectRepository;
use App\Models\Subject;

class SubjectService
{
    private SubjectRepository $repository;

    public function __construct(SubjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSubjects(): array
    {
        return $this->repository->all();
    }

    public function getSubjectsBySubDepartment(int $subDepartmentId): array
    {
        return $this->repository->allBySubDepartment($subDepartmentId);
    }

    public function paginate(int $subDepartmentId, string $search, int $page, int $perPage = 20): array
    {
        $offset = (max(1, $page) - 1) * $perPage;

        return $this->repository->paginate($subDepartmentId, $search, $perPage, $offset);
    }

    public function total(int $subDepartmentId, string $search): int
    {
        return $this->repository->count($subDepartmentId, $search);
    }

    public function getSubjectById(int $id): ?Subject
    {
        return $this->repository->find($id);
    }

    public function createSubject(array $data): bool
    {
        return $this->repository->create([
            'sub_department_id' => (int) ($data['sub_department_id'] ?? 0),
            'name' => trim((string) ($data['name'] ?? '')),
            'code' => trim((string) ($data['code'] ?? '')) ?: null,
            'semester' => !empty($data['semester']) ? (int) $data['semester'] : null,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_active' => !empty($data['is_active']) ? 1 : 0,
        ]);
    }

    public function updateSubject(int $id, array $data): bool
    {
        return $this->repository->update($id, [
            'sub_department_id' => (int) ($data['sub_department_id'] ?? 0),
            'name' => trim((string) ($data['name'] ?? '')),
            'code' => trim((string) ($data['code'] ?? '')) ?: null,
            'semester' => !empty($data['semester']) ? (int) $data['semester'] : null,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_active' => !empty($data['is_active']) ? 1 : 0,
        ]);
    }

    public function deleteSubject(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PaperSubmissionRepository;

class PaperSubmissionService
{
    public function __construct(private PaperSubmissionRepository $repository) {}

    public function getAll(): array { return $this->repository->all(); }
    public function getPending(): array { return $this->repository->getByStatus('pending'); }
    public function getById(int $id) { return $this->repository->find($id); }
    public function create(array $data): bool { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }

    /**
     * Approving a public submission just marks it approved. The admin still
     * needs to create the actual paper entry under Papers, picking the right
     * sub-department, exam type and session — the public submission form only
     * collects a subject name and one image, which isn't enough information
     * to auto-classify the paper.
     */
    public function approve(int $id): bool
    {
        return $this->repository->updateStatus($id, 'approved');
    }

    public function reject(int $id): bool
    {
        return $this->repository->updateStatus($id, 'rejected');
    }
}

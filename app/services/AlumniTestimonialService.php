<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AlumniTestimonialRepository;

class AlumniTestimonialService
{
    public function __construct(
        private AlumniTestimonialRepository $repository,
        private FileUploadService $fileUploadService
    ) {}

    public function getAll(): array { return $this->repository->all(); }
    public function getById(int $id) { return $this->repository->find($id); }

    public function create(array $data, ?array $photo = null): bool
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($photo && ($photo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['photo'] = $this->fileUploadService->image($photo, 'alumni');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?array $photo = null): bool
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($photo && ($photo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['photo'] = $this->fileUploadService->image($photo, 'alumni');
        } else {
            $existing = $this->repository->find($id);
            if ($existing) {
                $data['photo'] = $existing->photo;
            }
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool { return $this->repository->delete($id); }
}

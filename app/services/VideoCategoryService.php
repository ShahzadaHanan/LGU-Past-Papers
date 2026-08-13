<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\VideoCategoryRepository;
use App\Models\VideoCategory;
use App\Helpers\Slug;

class VideoCategoryService
{
    public function __construct(private VideoCategoryRepository $repository) {}

    public function getAllVideoCategories(): array
    {
        return $this->repository->all();
    }

    public function getVideoCategoryById(int $id): ?VideoCategory
    {
        return $this->repository->find($id);
    }

    public function createVideoCategory(array $data): bool
    {
        $data['slug'] = Slug::make($data['name'] ?? '');
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        return $this->repository->create($data);
    }

    public function updateVideoCategory(int $id, array $data): bool
    {
        $data['slug'] = Slug::make($data['name'] ?? '');
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        return $this->repository->update($id, $data);
    }

    public function deleteVideoCategory(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

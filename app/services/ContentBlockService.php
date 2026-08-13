<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentBlockRepository;
use App\Models\ContentBlock;

class ContentBlockService
{
    public function __construct(private ContentBlockRepository $repository) {}

    public function getAllContentBlocks(): array
    {
        return $this->repository->all();
    }

    public function getContentBlockById(int $id): ?ContentBlock
    {
        return $this->repository->find($id);
    }

    public function createContentBlock(array $data): bool
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        return $this->repository->create($data);
    }

    public function updateContentBlock(int $id, array $data): bool
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        return $this->repository->update($id, $data);
    }

    public function deleteContentBlock(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HeroSlideRepository;
use App\Models\HeroSlide;

class HeroSlideService
{
    public function __construct(
        private HeroSlideRepository $repository,
        private FileUploadService $fileUploadService
    ) {}

    public function getAllHeroSlides(): array
    {
        return $this->repository->all();
    }

    public function getHeroSlideById(int $id): ?HeroSlide
    {
        return $this->repository->find($id);
    }

    public function createHeroSlide(array $data, ?array $file = null): bool
    {
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['image_path'] = $this->fileUploadService->image($file, 'hero_slides');
        }

        if (empty($data['image_path'])) {
            throw new \Exception('Slide image is required.');
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $this->repository->create($data);
    }

    public function updateHeroSlide(int $id, array $data, ?array $file = null): bool
    {
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['image_path'] = $this->fileUploadService->image($file, 'hero_slides');
        } else {
            $existing = $this->getHeroSlideById($id);
            if ($existing) {
                $data['image_path'] = $existing->image_path;
            }
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $this->repository->update($id, $data);
    }

    public function deleteHeroSlide(int $id): bool
    {
        $existing = $this->getHeroSlideById($id);
        if ($existing) {
            $this->fileUploadService->delete($existing->image_path);
        }
        return $this->repository->delete($id);
    }
}

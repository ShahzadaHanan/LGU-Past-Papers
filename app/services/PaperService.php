<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PaperRepository;
use App\Models\Paper;
use App\Helpers\Slug;

class PaperService
{
    public function __construct(
        private PaperRepository $repository,
        private FileUploadService $fileUploadService
    ) {}

    public function getAllPapers(): array
    {
        return $this->repository->all();
    }

    public function getPaperById(int $id): ?Paper
    {
        return $this->repository->find($id);
    }

    public function search(string $search, int $page = 1, int $perPage = 15): array
    {
        return $this->repository->search($search, $perPage, ($page - 1) * $perPage);
    }

    public function total(string $search = ''): int
    {
        return $this->repository->count($search);
    }

    public function createPaper(array $data, ?array $paperImage = null, ?array $solutionImage = null, ?array $downloadFile = null): bool
    {
        $slug = Slug::make(
            ($data['subject_name'] ?? '') . '-' . ($data['session'] ?? '') . '-' . ($data['exam_type'] ?? '')
        ) . '-' . substr(uniqid(), -5);

        if ($paperImage && ($paperImage['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['paper_image'] = $this->fileUploadService->image($paperImage, 'papers');
        }

        if (empty($data['paper_image'])) {
            throw new \Exception('Paper image is required.');
        }

        if ($solutionImage && ($solutionImage['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['solution_image'] = $this->fileUploadService->image($solutionImage, 'papers/solutions');
        }

        if ($downloadFile && ($downloadFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['download_file'] = $this->fileUploadService->document($downloadFile, 'papers/downloads');
        }

        $data['slug'] = $slug;

        return $this->repository->create($data);
    }

    public function updatePaper(int $id, array $data, ?array $paperImage = null, ?array $solutionImage = null, ?array $downloadFile = null): bool
    {
        $existing = $this->getPaperById($id);

        $data['slug'] = $existing?->slug ?: Slug::make(($data['subject_name'] ?? '') . '-' . uniqid());

        if ($paperImage && ($paperImage['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['paper_image'] = $this->fileUploadService->image($paperImage, 'papers');
        } elseif ($existing) {
            $data['paper_image'] = $existing->paper_image;
        }

        if ($solutionImage && ($solutionImage['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['solution_image'] = $this->fileUploadService->image($solutionImage, 'papers/solutions');
        } elseif ($existing) {
            $data['solution_image'] = $existing->solution_image;
        }

        if ($downloadFile && ($downloadFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $data['download_file'] = $this->fileUploadService->document($downloadFile, 'papers/downloads');
        } elseif ($existing) {
            $data['download_file'] = $existing->download_file;
        }

        return $this->repository->update($id, $data);
    }

    public function deletePaper(int $id): bool
    {
        $existing = $this->getPaperById($id);

        if ($existing) {
            $this->fileUploadService->delete($existing->paper_image);
            $this->fileUploadService->delete($existing->solution_image);
        }

        return $this->repository->delete($id);
    }
}

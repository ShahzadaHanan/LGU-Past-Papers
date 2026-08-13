<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Department;
use App\Core\Validator;
use App\Services\FileUploadService;
use App\Repositories\DepartmentRepository;

class DepartmentService
{
    public function __construct(
        private Department $departments,
        private Validator $validator,
        private FileUploadService $uploads,
        private DepartmentRepository $repository
    ) {
    }

    public function getAll(): array
    {
        return $this->departments->all(
            'display_order ASC, name ASC'
        );
    }

    public function create(
        array $data,
        array $file = []
    ): void {
        $this->validator->required(
            'name',
            $data['name'] ?? ''
        );

        if ($this->validator->fails()) {
            throw new \Exception(
                'Validation failed.'
            );
        }

        $slug = \App\Helpers\Slug::make(
            $data['name']
        );

        $heroImage = null;

        if (
            !empty($file) &&
            !empty($file['name'])
        ) {
            $heroImage = $this->uploads->image(
                $file,
                'departments'
            );
        }

        $this->departments->insert([
            'name' => $data['name'],
            'slug' => $slug,
            'hero_image' => $heroImage,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'display_order' => (int) (
                $data['display_order'] ?? 0
            ),
            'is_active' => isset(
                $data['is_active']
            ) ? 1 : 0
        ]);
    }

    public function find(
        int $id
    ): array|false {
        return $this->departments->find($id);
    }

    public function update(
        int $id,
        array $data,
        array $file = []
    ): void {
        $this->validator->required(
            'name',
            $data['name'] ?? ''
        );

        if ($this->validator->fails()) {
            throw new \Exception(
                'Validation failed.'
            );
        }

        $slug = \App\Helpers\Slug::make(
            $data['name']
        );

        $updateData = [
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'display_order' => (int) (
                $data['display_order'] ?? 0
            ),
            'is_active' => isset(
                $data['is_active']
            ) ? 1 : 0
        ];

        if (
            !empty($file) &&
            !empty($file['name'])
        ) {
            $existing = $this->find($id);

            if (
                $existing &&
                !empty($existing['hero_image'])
            ) {
                $this->uploads->delete(
                    $existing['hero_image']
                );
            }

            $updateData['hero_image'] =
                $this->uploads->image(
                    $file,
                    'departments'
                );
        }

        $this->departments->update(
            $id,
            $updateData
        );
    }

    public function delete(
        int $id
    ): void {
        $existing = $this->find($id);

        if (
            $existing &&
            !empty($existing['hero_image'])
        ) {
            $this->uploads->delete(
                $existing['hero_image']
            );
        }

        $this->departments->delete($id);
    }

    public function search(
        string $search,
        int $page = 1,
        int $perPage = 15
    ): array {
        $offset = ($page - 1) * $perPage;

        return $this->repository->search(
            $search,
            $perPage,
            $offset
        );
    }

    public function total(
        string $search = ''
    ): int {
        return $this->repository->count(
            $search
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdminRepository;

class AdminUserService
{
    public function __construct(private AdminRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->all();
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        unset($data['password']);

        $data['role'] = $data['role'] ?? 'editor';

        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        unset($data['password']);

        if (empty($data['password_hash'])) {
            unset($data['password_hash']);
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

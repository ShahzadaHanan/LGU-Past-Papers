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

    /**
     * Only super_admin can reach this controller (RoleMiddleware), but the
     * form data is still whitelisted explicitly here rather than passed
     * through — raw $_POST previously reached Repository::create() as-is,
     * so an extra `role=super_admin` field in the request body would set
     * it. Every admin-editable column is named here on purpose; anything
     * not listed is silently dropped instead of reaching SQL.
     */
    public function create(array $data): bool
    {
        $payload = [
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'role' => in_array($data['role'] ?? null, ['super_admin', 'editor'], true)
                ? $data['role']
                : 'editor',
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];

        if (!empty($data['password'])) {
            $payload['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->repository->create($payload);
    }

    public function update(int $id, array $data): bool
    {
        $payload = [
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'role' => in_array($data['role'] ?? null, ['super_admin', 'editor'], true)
                ? $data['role']
                : 'editor',
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];

        if (!empty($data['password'])) {
            $payload['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->repository->update($id, $payload);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NewsletterSubscriberRepository;

class NewsletterSubscriberService
{
    public function __construct(private NewsletterSubscriberRepository $repository) {}

    public function getAll(): array { return $this->repository->all(); }
    public function getById(int $id) { return $this->repository->find($id); }
    public function create(array $data): bool { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }

    public function unsubscribeByToken(string $token): bool
    {
        return $this->repository->deactivateByToken($token);
    }

    public function toggleStatus(int $id): bool
    {
        $subscriber = $this->repository->find($id);
        if ($subscriber) {
            return $this->repository->updateStatus($id, !$subscriber->is_verified);
        }
        return false;
    }
}

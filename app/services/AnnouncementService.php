<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AnnouncementRepository;
use App\Models\Announcement;

class AnnouncementService
{
    public function __construct(private AnnouncementRepository $repository) {}

    public function getAllAnnouncements(): array
    {
        return $this->repository->all();
    }

    public function getActiveAnnouncements(): array
    {
        return $this->repository->active();
    }

    public function getAnnouncementById(int $id): ?Announcement
    {
        return $this->repository->find($id);
    }

    private function whitelist(array $data): array
    {
        $message = trim((string) ($data['message'] ?? ''));

        if ($message === '') {
            throw new \Exception('Announcement message is required.');
        }

        return [
            'message' => $message,
            'link_url' => trim((string) ($data['link_url'] ?? '')) ?: null,
            'link_label' => trim((string) ($data['link_label'] ?? '')) ?: null,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];
    }

    public function createAnnouncement(array $data): bool
    {
        return $this->repository->create($this->whitelist($data));
    }

    public function updateAnnouncement(int $id, array $data): bool
    {
        return $this->repository->update($id, $this->whitelist($data));
    }

    public function deleteAnnouncement(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function toggleActive(int $id): bool
    {
        $item = $this->getAnnouncementById($id);

        if (!$item) {
            return false;
        }

        return $this->repository->update($id, [
            'message' => $item->message,
            'link_url' => $item->link_url,
            'link_label' => $item->link_label,
            'display_order' => $item->display_order,
            'is_active' => $item->is_active ? 0 : 1,
        ]);
    }
}

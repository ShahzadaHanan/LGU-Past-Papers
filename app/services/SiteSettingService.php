<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SiteSettingRepository;
use App\Models\SiteSetting;

class SiteSettingService
{
    public function __construct(private SiteSettingRepository $repository) {}

    public function getAllSiteSettings(): array
    {
        return $this->repository->all();
    }

    public function getSiteSettingById(int $id): ?SiteSetting
    {
        return $this->repository->find($id);
    }

    public function getSettingValue(string $key): ?string
    {
        $setting = $this->repository->findByKey($key);
        return $setting ? $setting->setting_value : null;
    }

    public function createSiteSetting(array $data): bool
    {
        return $this->repository->create($data);
    }

    public function updateSiteSetting(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteSiteSetting(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

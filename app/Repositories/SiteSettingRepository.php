<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\SiteSetting;

class SiteSettingRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM site_settings");
        return array_map(fn($row) => SiteSetting::fromArray($row), $stmt->fetchAll());
    }

    public function find(int $id): ?SiteSetting
    {
        $stmt = $this->db->query("SELECT * FROM site_settings WHERE id = :id", ['id' => $id]);
        $row = $stmt->fetch();
        return $row ? SiteSetting::fromArray($row) : null;
    }

    public function findByKey(string $key): ?SiteSetting
    {
        $stmt = $this->db->query("SELECT * FROM site_settings WHERE setting_key = :key", ['key' => $key]);
        $row = $stmt->fetch();
        return $row ? SiteSetting::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->query(
            "INSERT INTO site_settings (setting_key, setting_value) VALUES (:setting_key, :setting_value)",
            [
                'setting_key' => $data['setting_key'],
                'setting_value' => $data['setting_value'] ?? null
            ]
        )->rowCount() > 0;
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->query(
            "UPDATE site_settings SET setting_key = :setting_key, setting_value = :setting_value WHERE id = :id",
            [
                'id' => $id,
                'setting_key' => $data['setting_key'],
                'setting_value' => $data['setting_value'] ?? null
            ]
        )->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM site_settings WHERE id = :id", ['id' => $id])->rowCount() > 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

class SiteSetting
{
    public function __construct(
        public ?int $id = null,
        public string $setting_key,
        public ?string $setting_value = null,
        public string $created_at = '',
        public string $updated_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            setting_key: $data['setting_key'],
            setting_value: $data['setting_value'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
        );
    }
}

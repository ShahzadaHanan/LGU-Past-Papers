<?php

declare(strict_types=1);

namespace App\Models;

class Announcement
{
    public function __construct(
        public ?int $id = null,
        public string $message = '',
        public ?string $link_url = null,
        public ?string $link_label = null,
        public int $display_order = 0,
        public bool $is_active = true,
        public string $created_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            message: $data['message'] ?? '',
            link_url: $data['link_url'] ?? null,
            link_label: $data['link_label'] ?? null,
            display_order: (int) ($data['display_order'] ?? 0),
            is_active: isset($data['is_active']) ? (bool) $data['is_active'] : true,
            created_at: $data['created_at'] ?? ''
        );
    }
}

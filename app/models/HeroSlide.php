<?php

declare(strict_types=1);

namespace App\Models;

class HeroSlide
{
    public function __construct(
        public ?int $id = null,
        public string $image_path = '',
        public ?string $caption = null,
        public ?string $link_url = null,
        public int $display_order = 0,
        public bool $is_active = true,
        public string $created_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            image_path: $data['image_path'] ?? '',
            caption: $data['caption'] ?? null,
            link_url: $data['link_url'] ?? null,
            display_order: (int) ($data['display_order'] ?? 0),
            is_active: isset($data['is_active']) ? (bool) $data['is_active'] : true,
            created_at: $data['created_at'] ?? ''
        );
    }
}

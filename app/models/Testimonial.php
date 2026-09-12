<?php

declare(strict_types=1);

namespace App\Models;

class Testimonial
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public ?string $degree = null,
        public ?string $photo = null,
        public string $quote = '',
        public int $display_order = 0,
        public bool $is_active = true,
        public string $created_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: $data['name'] ?? '',
            degree: $data['degree'] ?? null,
            photo: $data['photo'] ?? null,
            quote: $data['quote'] ?? '',
            display_order: (int) ($data['display_order'] ?? 0),
            is_active: isset($data['is_active']) ? (bool) $data['is_active'] : true,
            created_at: $data['created_at'] ?? ''
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

class AlumniTestimonial
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $batch_year = '',
        public string $degree = '',
        public ?string $photo = null,
        public string $testimonial = '',
        public ?string $linkedin_url = null,
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
            batch_year: $data['batch_year'] ?? '',
            degree: $data['degree'] ?? '',
            photo: $data['photo'] ?? null,
            testimonial: $data['testimonial'] ?? '',
            linkedin_url: $data['linkedin_url'] ?? null,
            display_order: (int) ($data['display_order'] ?? 0),
            is_active: isset($data['is_active']) ? (bool) $data['is_active'] : true,
            created_at: $data['created_at'] ?? ''
        );
    }
}

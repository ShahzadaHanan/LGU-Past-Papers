<?php

declare(strict_types=1);

namespace App\Models;

class SubDepartment
{
    public function __construct(
        public ?int $id = null,
        public int $department_id = 0,
        public string $name = '',
        public ?string $slug = null,
        public ?string $description = null,
        public string $created_at = '',
        public string $updated_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            department_id: (int) $data['department_id'],
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
        );
    }
}

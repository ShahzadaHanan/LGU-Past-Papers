<?php

declare(strict_types=1);

namespace App\Models;

class Subject
{
    public function __construct(
        public ?int $id = null,
        public int $sub_department_id = 0,
        public string $name = '',
        public ?string $code = null,
        public ?int $semester = null,
        public int $display_order = 0,
        public bool $is_active = true,
        public string $created_at = '',
        public string $updated_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            sub_department_id: (int) $data['sub_department_id'],
            name: $data['name'],
            code: $data['code'] ?? null,
            semester: isset($data['semester']) ? (int) $data['semester'] : null,
            display_order: (int) ($data['display_order'] ?? 0),
            is_active: (bool) ($data['is_active'] ?? true),
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

class Video
{
    public function __construct(
        public ?int $id = null,
        public int $category_id = 0,
        public ?int $sub_department_id = null,
        public ?string $subject_name = null,
        public string $youtube_url = '',
        public ?string $youtube_video_id = null,
        public string $title = '',
        public ?string $description = null,
        public int $display_order = 0,
        public bool $is_active = true,
        public ?string $category_name = null,
        public ?string $sub_department_name = null,
        public ?int $department_id = null,
        public ?string $department_name = null,
        public string $created_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            category_id: (int) ($data['category_id'] ?? 0),
            sub_department_id: isset($data['sub_department_id']) ? (int) $data['sub_department_id'] : null,
            subject_name: $data['subject_name'] ?? null,
            youtube_url: $data['youtube_url'] ?? '',
            youtube_video_id: $data['youtube_video_id'] ?? null,
            title: $data['title'] ?? '',
            description: $data['description'] ?? null,
            display_order: (int) ($data['display_order'] ?? 0),
            is_active: isset($data['is_active']) ? (bool) $data['is_active'] : true,
            category_name: $data['category_name'] ?? null,
            sub_department_name: $data['sub_department_name'] ?? null,
            department_id: isset($data['department_id']) ? (int) $data['department_id'] : null,
            department_name: $data['department_name'] ?? null,
            created_at: $data['created_at'] ?? ''
        );
    }
}

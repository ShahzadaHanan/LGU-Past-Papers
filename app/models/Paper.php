<?php

declare(strict_types=1);

namespace App\Models;

class Paper
{
    public function __construct(
        public ?int $id = null,
        public int $sub_department_id = 0,
        public string $exam_type = 'mids',
        public string $session = '',
        public string $subject_name = '',
        public string $slug = '',
        public string $paper_image = '',
        public ?string $solution_image = null,
        public ?string $download_file = null,
        public int $views_count = 0,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
        public ?string $sub_department_name = null,
        public string $created_at = '',
        public string $updated_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            sub_department_id: (int) ($data['sub_department_id'] ?? 0),
            exam_type: $data['exam_type'] ?? 'mids',
            session: $data['session'] ?? '',
            subject_name: $data['subject_name'] ?? '',
            slug: $data['slug'] ?? '',
            paper_image: $data['paper_image'] ?? '',
            solution_image: $data['solution_image'] ?? null,
            download_file: $data['download_file'] ?? null,
            views_count: (int) ($data['views_count'] ?? 0),
            meta_title: $data['meta_title'] ?? null,
            meta_description: $data['meta_description'] ?? null,
            sub_department_name: $data['sub_department_name'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
        );
    }
}

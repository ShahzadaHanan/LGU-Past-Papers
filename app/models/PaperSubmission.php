<?php

declare(strict_types=1);

namespace App\Models;

class PaperSubmission
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $email = '',
        public string $whatsapp = '',
        public string $subject = '',
        public ?string $description = null,
        public string $image_path = '',
        public string $status = 'pending',
        public string $created_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            whatsapp: $data['whatsapp'] ?? '',
            subject: $data['subject'] ?? '',
            description: $data['description'] ?? null,
            image_path: $data['image_path'] ?? '',
            status: $data['status'] ?? 'pending',
            created_at: $data['created_at'] ?? ''
        );
    }
}

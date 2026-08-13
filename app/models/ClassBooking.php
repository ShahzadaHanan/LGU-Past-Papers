<?php

declare(strict_types=1);

namespace App\Models;

class ClassBooking
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $email = '',
        public string $whatsapp = '',
        public string $subject = '',
        public ?string $topic = null,
        public string $fee_type = 'single_lecture',
        public ?string $description = null,
        public string $status = 'new',
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
            topic: $data['topic'] ?? null,
            fee_type: $data['fee_type'] ?? 'single_lecture',
            description: $data['description'] ?? null,
            status: $data['status'] ?? 'new',
            created_at: $data['created_at'] ?? ''
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

class NewsletterSubscriber
{
    public function __construct(
        public ?int $id = null,
        public string $email = '',
        public bool $is_verified = false,
        public ?string $unsubscribe_token = null,
        public string $subscribed_at = ''
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            email: $data['email'] ?? '',
            is_verified: isset($data['is_verified']) ? (bool) $data['is_verified'] : false,
            unsubscribe_token: $data['unsubscribe_token'] ?? null,
            subscribed_at: $data['subscribed_at'] ?? ''
        );
    }
}

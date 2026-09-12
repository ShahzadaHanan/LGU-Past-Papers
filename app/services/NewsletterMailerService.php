<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Repositories\NewsletterSubscriberRepository;

class NewsletterMailerService
{
    private const LABELS = [
        'paper' => 'New Past Paper',
        'video' => 'New Video Lecture',
        'announcement' => 'News Update',
    ];

    public function __construct(
        private NewsletterSubscriberRepository $subscribers,
        private MailService $mail,
        private Database $db
    ) {
    }

    /**
     * Emails every active (verified, not unsubscribed) subscriber about a
     * newly published paper/video/announcement and logs the send.
     *
     * @return array{sent:int, failed:int, total:int}
     */
    public function notify(string $type, ?int $sourceId, string $title, string $description, string $url): array
    {
        $label = self::LABELS[$type] ?? 'Update';
        $active = array_filter($this->subscribers->all(), fn($s) => $s->is_verified);

        $sent = 0;
        $failed = 0;

        foreach ($active as $subscriber) {
            $unsubscribeUrl = rtrim(env('APP_URL', ''), '/') . '/newsletter/unsubscribe?token=' . urlencode((string) $subscriber->unsubscribe_token);

            ob_start();
            require basePath('app/views/emails/newsletter_update.php');
            $body = (string) ob_get_clean();

            try {
                if ($this->mail->send($subscriber->email, "LGU Hub: {$label} — {$title}", $body)) {
                    $sent++;
                } else {
                    $failed++;
                }
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        $this->db->execute(
            "INSERT INTO notification_log (paper_id, type, title, url, recipients_count) VALUES (?, ?, ?, ?, ?)",
            [$type === 'paper' ? $sourceId : null, $type, $title, $url, $sent]
        );

        return ['sent' => $sent, 'failed' => $failed, 'total' => count($active)];
    }
}

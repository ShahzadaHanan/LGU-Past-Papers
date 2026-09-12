<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public function send(
        string $to,
        string $subject,
        string $body,
        bool $isHtml = true
    ): bool {
        $host = env('MAIL_HOST', '');
        if ($host === '') {
            throw new \RuntimeException('Mail is not configured — set MAIL_HOST, MAIL_USERNAME and MAIL_PASSWORD in .env.');
        }

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Username = env('MAIL_USERNAME', '');
        $mail->Password = env('MAIL_PASSWORD', '');
        $mail->Port = (int) env('MAIL_PORT', 587);
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;

        $mail->setFrom(
            env('MAIL_FROM', $mail->Username),
            env('MAIL_FROM_NAME', 'LGU Hub')
        );

        $mail->addAddress($to);

        $mail->isHTML($isHtml);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    }
}

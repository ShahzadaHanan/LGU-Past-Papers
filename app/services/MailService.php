<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public function send(
        string $to,
        string $subject,
        string $body
    ):bool{

        $mail=new PHPMailer(true);

        $mail->isSMTP();

        $mail->Host=MAIL_HOST;

        $mail->Username=MAIL_USERNAME;

        $mail->Password=MAIL_PASSWORD;

        $mail->Port=MAIL_PORT;

        $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom(
            MAIL_FROM,
            MAIL_NAME
        );

        $mail->addAddress($to);

        $mail->Subject=$subject;

        $mail->Body=$body;

        return $mail->send();
    }
}
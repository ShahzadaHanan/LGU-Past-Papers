<?php
/** @var string $label */
/** @var string $title */
/** @var string $description */
/** @var string $url */
/** @var string $unsubscribeUrl */
?>
<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:Poppins,Arial,sans-serif;color:#1a2417;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:32px 0;">
<tr><td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden;">
<tr><td style="background:#0d3e02;padding:20px 32px;">
<span style="color:#fdbf1e;font-size:20px;font-weight:700;">LGU Hub</span>
</td></tr>
<tr><td style="padding:32px;">
<p style="display:inline-block;background:rgba(13,62,2,0.08);color:#0d3e02;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;padding:4px 12px;border-radius:999px;margin:0 0 16px;"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></p>
<h1 style="font-size:20px;margin:0 0 12px;color:#1a2417;"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
<p style="font-size:14px;line-height:1.6;color:#5c6b57;margin:0 0 24px;"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
<a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" style="display:inline-block;background:#0d3e02;color:#ffffff;text-decoration:none;font-weight:600;font-size:14px;padding:12px 24px;border-radius:8px;">View on LGU Hub</a>
</td></tr>
<tr><td style="padding:20px 32px;border-top:1px solid #e2e2e2;">
<p style="font-size:12px;color:#8a958a;margin:0;">
You're receiving this because you subscribed to LGU Hub update alerts.
<a href="<?= htmlspecialchars($unsubscribeUrl, ENT_QUOTES, 'UTF-8') ?>" style="color:#8a958a;">Unsubscribe</a>
</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>

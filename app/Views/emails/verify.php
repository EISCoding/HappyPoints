<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= e($subject ?? 'E-Mail bestätigen') ?></title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:Inter,Arial,sans-serif;color:#e2e8f0;">
    <div style="padding:32px 16px;">
        <div style="max-width:620px;margin:0 auto;background:linear-gradient(180deg,#111827 0%,#0b1120 100%);border:1px solid rgba(148,163,184,.15);border-radius:28px;overflow:hidden;box-shadow:0 18px 40px rgba(15,23,42,.35);">
            <div style="padding:28px 28px 16px;">
                <div style="display:inline-block;padding:10px 14px;border-radius:999px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);font-size:12px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#cbd5e1;">Happypoints</div>
                <h1 style="margin:22px 0 10px;font-size:30px;line-height:1.1;color:#ffffff;">Bestätige deine E-Mail-Adresse</h1>
                <p style="margin:0;font-size:16px;line-height:1.7;color:#cbd5e1;">Hallo <?= e($username ?? 'du') ?>, bitte bestätige deine Registrierung, damit du dich bei Happypoints einloggen kannst.</p>
            </div>
            <div style="padding:0 28px 28px;">
                <div style="margin-top:20px;padding:18px;border-radius:22px;background:rgba(124,156,255,.12);border:1px solid rgba(124,156,255,.22);color:#dbeafe;font-size:14px;line-height:1.6;">
                    Nach dem Klick auf den Button ist dein Konto verifiziert und du kannst dich direkt einloggen.
                </div>
                <div style="margin-top:28px;">
                    <a href="<?= e($verificationUrl ?? '#') ?>" style="display:inline-block;background:#7c9cff;color:#ffffff;text-decoration:none;padding:15px 22px;border-radius:18px;font-size:15px;font-weight:700;">E-Mail jetzt bestätigen</a>
                </div>
                <p style="margin:22px 0 0;font-size:13px;line-height:1.7;color:#94a3b8;">Falls der Button nicht funktioniert, kopiere diesen Link in deinen Browser:<br><span style="color:#ffffff;"><?= e($verificationUrl ?? '') ?></span></p>
            </div>
        </div>
    </div>
</body>
</html>

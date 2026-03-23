<?php
declare(strict_types=1);

return [
    'host' => getenv('SMTP_HOST') ?: 'smtp.example.com',
    'port' => (int) (getenv('SMTP_PORT') ?: 587),
    'username' => getenv('SMTP_USERNAME') ?: 'no-reply@example.com',
    'password' => getenv('SMTP_PASSWORD') ?: 'change-me',
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
    'from_email' => getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@example.com',
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Happypoints',
    'verify_path' => '/verify-email',
];

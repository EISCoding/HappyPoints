<?php
declare(strict_types=1);

final class Mailer
{
    public static function send(string $toEmail, string $toName, string $subject, string $html, string $text = ''): void
    {
        $config = require BASE_PATH . '/config/mail.php';
        $host = (string) ($config['host'] ?? '');
        $port = (int) ($config['port'] ?? 587);
        $username = (string) ($config['username'] ?? '');
        $password = (string) ($config['password'] ?? '');
        $encryption = strtolower((string) ($config['encryption'] ?? 'tls'));
        $fromEmail = (string) ($config['from_email'] ?? $username);
        $fromName = (string) ($config['from_name'] ?? appName());

        if ($host === '' || $username === '' || $password === '' || $fromEmail === '') {
            throw new RuntimeException('SMTP ist nicht vollständig konfiguriert.');
        }

        $remoteHost = $encryption === 'ssl' ? 'ssl://' . $host : $host;
        $socket = @stream_socket_client($remoteHost . ':' . $port, $errorNumber, $errorMessage, 20, STREAM_CLIENT_CONNECT);
        if (!is_resource($socket)) {
            throw new RuntimeException('SMTP Verbindung fehlgeschlagen: ' . $errorMessage . ' (' . $errorNumber . ')');
        }

        stream_set_timeout($socket, 20);

        try {
            self::expect($socket, [220]);
            self::command($socket, 'EHLO happypoints.local', [250]);

            if ($encryption === 'tls') {
                self::command($socket, 'STARTTLS', [220]);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new RuntimeException('TLS konnte für SMTP nicht aktiviert werden.');
                }
                self::command($socket, 'EHLO happypoints.local', [250]);
            }

            self::command($socket, 'AUTH LOGIN', [334]);
            self::command($socket, base64_encode($username), [334]);
            self::command($socket, base64_encode($password), [235]);
            self::command($socket, 'MAIL FROM:<' . $fromEmail . '>', [250]);
            self::command($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
            self::command($socket, 'DATA', [354]);

            $boundary = 'b-' . bin2hex(random_bytes(12));
            $headers = [
                'Date: ' . gmdate('D, d M Y H:i:s') . ' +0000',
                'Message-ID: <' . bin2hex(random_bytes(12)) . '@happypoints.local>',
                'From: ' . self::formatAddress($fromEmail, $fromName),
                'To: ' . self::formatAddress($toEmail, $toName),
                'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=',
                'MIME-Version: 1.0',
                'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            ];

            $text = $text !== '' ? $text : trim(strip_tags($html));
            $message = implode("\r\n", $headers) . "\r\n\r\n"
                . '--' . $boundary . "\r\n"
                . "Content-Type: text/plain; charset=UTF-8\r\n"
                . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                . self::dotStuff($text) . "\r\n"
                . '--' . $boundary . "\r\n"
                . "Content-Type: text/html; charset=UTF-8\r\n"
                . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                . self::dotStuff($html) . "\r\n"
                . '--' . $boundary . "--\r\n.";

            fwrite($socket, $message . "\r\n");
            self::expect($socket, [250]);
            self::command($socket, 'QUIT', [221]);
        } finally {
            fclose($socket);
        }
    }

    public static function renderTemplate(string $template, array $data = []): string
    {
        $templateFile = VIEW_PATH . '/emails/' . $template . '.php';
        if (!is_file($templateFile)) {
            throw new RuntimeException('Mail-Template nicht gefunden: ' . $template);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $templateFile;
        return (string) ob_get_clean();
    }

    private static function formatAddress(string $email, string $name): string
    {
        return '=?UTF-8?B?' . base64_encode($name) . '?= <' . $email . '>';
    }

    private static function dotStuff(string $message): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $message);
        $normalized = preg_replace('/(^|\n)\./', "$1..", $normalized) ?? $normalized;
        return str_replace("\n", "\r\n", $normalized);
    }

    private static function command($socket, string $command, array $expectedCodes): string
    {
        fwrite($socket, $command . "\r\n");
        return self::expect($socket, $expectedCodes);
    }

    private static function expect($socket, array $expectedCodes): string
    {
        $response = '';
        do {
            $line = fgets($socket, 515);
            if ($line === false) {
                throw new RuntimeException('SMTP hat die Verbindung unerwartet beendet.');
            }
            $response .= $line;
        } while (isset($line[3]) && $line[3] === '-');

        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            throw new RuntimeException('SMTP Fehler: ' . trim($response));
        }
        return $response;
    }
}

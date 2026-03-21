<?php
declare(strict_types=1);

final class Session
{
    public static function start(string $sessionName = 'app_session'): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name($sessionName);
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function set(string $key, mixed $value): void { $_SESSION[$key] = $value; }
    public static function get(string $key, mixed $default = null): mixed { return $_SESSION[$key] ?? $default; }
    public static function has(string $key): bool { return array_key_exists($key, $_SESSION); }
    public static function remove(string $key): void { unset($_SESSION[$key]); }
    public static function regenerate(): void { if (session_status() === PHP_SESSION_ACTIVE) session_regenerate_id(true); }
    public static function flash(string $key, mixed $value): void { $_SESSION['_flash'][$key] = $value; }
    public static function getFlash(string $key, mixed $default = null): mixed {
        if (!isset($_SESSION['_flash'][$key])) return $default;
        $value = $_SESSION['_flash'][$key]; unset($_SESSION['_flash'][$key]); return $value;
    }
    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', (bool)($params['secure'] ?? false), (bool)($params['httponly'] ?? true));
        }
        session_destroy();
    }
}

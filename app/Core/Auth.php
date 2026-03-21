<?php
declare(strict_types=1);

final class Auth
{
    public static function check(): bool { return Session::has('user_id') && (int)Session::get('user_id') > 0; }
    public static function id(): ?int { return self::check() ? (int)Session::get('user_id') : null; }
    public static function login(int $userId): void { Session::regenerate(); Session::set('user_id', $userId); }
    public static function logout(): void { Session::destroy(); }
    public static function requireLogin(): void { if (!self::check()) { flashMessage('error', 'Bitte zuerst einloggen.'); Redirect::to('/login'); } }
    public static function guestOnly(): void { if (self::check()) Redirect::to('/dashboard'); }
}

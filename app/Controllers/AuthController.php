<?php
declare(strict_types=1);

final class AuthController
{
    public function showLogin(): void
    {
        Auth::guestOnly();
        View::render('auth.login');
    }

    public function login(): never
    {
        Auth::guestOnly();
        $email = (string) ($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        try {
            $userId = User::attemptLogin($email, $password);
            Auth::login($userId);
            flashMessage('success', 'Login erfolgreich.');
            Redirect::to('/dashboard');
        } catch (Throwable $e) {
            withOldInput(['email' => $email]);
            flashMessage('error', $e->getMessage());
            Redirect::to('/login');
        }
    }

    public function showRegister(): void
    {
        Auth::guestOnly();
        View::render('auth.register');
    }

    public function register(): never
    {
        Auth::guestOnly();
        $email = (string) ($_POST['email'] ?? '');
        $username = (string) ($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');
        try {
            if ($password !== $passwordConfirmation) throw new InvalidArgumentException('Die Passwörter stimmen nicht überein.');
            $userId = User::create($email, $username, $password);
            Auth::login($userId);
            flashMessage('success', 'Registrierung erfolgreich.');
            Redirect::to('/dashboard');
        } catch (Throwable $e) {
            withOldInput(['email' => $email, 'username' => $username]);
            flashMessage('error', $e->getMessage());
            Redirect::to('/register');
        }
    }

    public function logout(): never
    {
        Auth::logout();
        Session::start('happypoints_session');
        flashMessage('success', 'Du wurdest ausgeloggt.');
        Redirect::to('/login');
    }
}

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
            $user = User::findByEmail($email);
            if (!$user || !User::verifyPassword($user, $password)) {
                throw new InvalidArgumentException('E-Mail oder Passwort ist falsch.');
            }
            if (empty($user['email_verified_at'])) {
                try {
                    $this->sendVerificationMail((int) $user['id']);
                    throw new InvalidArgumentException('Bitte bestätige zuerst deine E-Mail-Adresse. Wir haben dir einen neuen Bestätigungslink gesendet.');
                } catch (RuntimeException $mailException) {
                    throw new InvalidArgumentException('Bitte bestätige zuerst deine E-Mail-Adresse. Ein erneuter Versand war gerade nicht möglich: ' . $mailException->getMessage());
                }
            }

            Auth::login((int) $user['id']);
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
            if ($password !== $passwordConfirmation) {
                throw new InvalidArgumentException('Die Passwörter stimmen nicht überein.');
            }
            $userId = User::create($email, $username, $password);
            try {
                $this->sendVerificationMail($userId);
                flashMessage('success', 'Registrierung erfolgreich. Bitte bestätige jetzt deine E-Mail-Adresse.');
            } catch (RuntimeException $mailException) {
                flashMessage('success', 'Registrierung erfolgreich. Die Bestätigungs-Mail konnte gerade nicht gesendet werden: ' . $mailException->getMessage());
            }
            Redirect::to('/login');
        } catch (Throwable $e) {
            withOldInput(['email' => $email, 'username' => $username]);
            flashMessage('error', $e->getMessage());
            Redirect::to('/register');
        }
    }

    public function verifyEmail(): never
    {
        Auth::guestOnly();
        $token = (string) ($_GET['token'] ?? '');
        try {
            $userId = EmailVerification::verify($token);
            if ($userId === null) {
                throw new InvalidArgumentException('Der Bestätigungslink ist ungültig oder abgelaufen.');
            }
            flashMessage('success', 'Deine E-Mail-Adresse wurde bestätigt. Du kannst dich jetzt einloggen.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
        }
        Redirect::to('/login');
    }

    public function logout(): never
    {
        Auth::logout();
        Session::start('happypoints_session');
        flashMessage('success', 'Du wurdest ausgeloggt.');
        Redirect::to('/login');
    }

    private function sendVerificationMail(int $userId): void
    {
        $user = User::findById($userId);
        if (!$user) {
            throw new RuntimeException('Benutzer wurde nicht gefunden.');
        }

        $token = EmailVerification::issueForUser($userId);
        $mailConfig = require BASE_PATH . '/config/mail.php';
        $verifyPath = (string) ($mailConfig['verify_path'] ?? '/verify-email');
        $verificationUrl = appUrl($verifyPath . '?token=' . urlencode($token));
        $subject = 'Bitte bestätige deine Happypoints E-Mail';
        $html = Mailer::renderTemplate('verify', [
            'subject' => $subject,
            'username' => (string) $user['username'],
            'verificationUrl' => $verificationUrl,
        ]);
        $text = "Hallo " . (string) $user['username'] . ",\n\nbitte bestätige deine E-Mail-Adresse über diesen Link:\n" . $verificationUrl . "\n\nDanach kannst du dich bei Happypoints einloggen.";

        Mailer::send((string) $user['email'], (string) $user['username'], $subject, $html, $text);
    }
}

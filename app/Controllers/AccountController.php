<?php
declare(strict_types=1);

final class AccountController
{
    public function index(): void
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }
        $user = Account::getUserWithAccount($userId);
        $partner = User::getPartnerForUser($userId);
        View::render('account.index', ['user' => $user, 'partner' => $partner]);
    }

    public function updateProfile(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $payload = [
            'display_name' => (string) ($_POST['display_name'] ?? ''),
            'headline' => (string) ($_POST['headline'] ?? ''),
            'bio' => (string) ($_POST['bio'] ?? ''),
            'city' => (string) ($_POST['city'] ?? ''),
            'favorite_activity' => (string) ($_POST['favorite_activity'] ?? ''),
            'avatar_icon' => (string) ($_POST['avatar_icon'] ?? ''),
            'accent_color' => (string) ($_POST['accent_color'] ?? ''),
            'weekly_goal' => (int) ($_POST['weekly_goal'] ?? 50),
        ];

        try {
            Profile::update($userId, $payload);
            flashMessage('success', 'Dein Profil wurde aktualisiert.');
        } catch (Throwable $e) {
            withOldInput($payload);
            flashMessage('error', $e->getMessage());
        }

        Redirect::to('/account');
    }
}

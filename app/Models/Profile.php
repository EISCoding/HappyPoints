<?php
declare(strict_types=1);

final class Profile
{
    public static function defaults(): array
    {
        return [
            'display_name' => null,
            'headline' => 'Gemeinsam Punkte sammeln & schöne Momente planen',
            'bio' => 'Passe dein Profil an, setze eigene Ziele und halte eure Lieblingsbelohnungen fest.',
            'city' => null,
            'favorite_activity' => null,
            'avatar_icon' => 'bx-happy-heart-eyes',
            'accent_color' => '#7c9cff',
            'weekly_goal' => 50,
        ];
    }

    public static function ensureForUser(int $userId): void
    {
        $stmt = Database::connection()->prepare('INSERT IGNORE INTO user_profiles (user_id, display_name, headline, bio, city, favorite_activity, avatar_icon, accent_color, weekly_goal) VALUES (:user_id, :display_name, :headline, :bio, :city, :favorite_activity, :avatar_icon, :accent_color, :weekly_goal)');
        $stmt->execute(['user_id' => $userId] + self::defaults());
    }

    public static function update(int $userId, array $payload): void
    {
        self::ensureForUser($userId);

        $displayName = trim((string) ($payload['display_name'] ?? ''));
        $headline = trim((string) ($payload['headline'] ?? ''));
        $bio = trim((string) ($payload['bio'] ?? ''));
        $city = trim((string) ($payload['city'] ?? ''));
        $favoriteActivity = trim((string) ($payload['favorite_activity'] ?? ''));
        $avatarIcon = trim((string) ($payload['avatar_icon'] ?? 'bx-happy-heart-eyes'));
        $accentColor = trim((string) ($payload['accent_color'] ?? '#7c9cff'));
        $weeklyGoal = (int) ($payload['weekly_goal'] ?? 50);

        if ($weeklyGoal <= 0) {
            throw new InvalidArgumentException('Das Wochenziel muss größer als 0 sein.');
        }
        if (!preg_match('/^bx[a-z0-9\-]+$/i', $avatarIcon)) {
            throw new InvalidArgumentException('Bitte wähle ein gültiges Boxicons-Icon.');
        }
        if (!preg_match('/^#[0-9a-f]{6}$/i', $accentColor)) {
            throw new InvalidArgumentException('Bitte wähle eine gültige Akzentfarbe.');
        }

        $stmt = Database::connection()->prepare('UPDATE user_profiles SET display_name = :display_name, headline = :headline, bio = :bio, city = :city, favorite_activity = :favorite_activity, avatar_icon = :avatar_icon, accent_color = :accent_color, weekly_goal = :weekly_goal WHERE user_id = :user_id');
        $stmt->execute([
            'user_id' => $userId,
            'display_name' => $displayName !== '' ? $displayName : null,
            'headline' => $headline !== '' ? $headline : null,
            'bio' => $bio !== '' ? $bio : null,
            'city' => $city !== '' ? $city : null,
            'favorite_activity' => $favoriteActivity !== '' ? $favoriteActivity : null,
            'avatar_icon' => $avatarIcon,
            'accent_color' => $accentColor,
            'weekly_goal' => $weeklyGoal,
        ]);
    }
}

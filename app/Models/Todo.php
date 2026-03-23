<?php
declare(strict_types=1);

final class Todo
{
    public static function latestAssignedToUser(int $userId, int $limit = 10): array
    {
        $limit = max(1, $limit);
        $stmt = Database::connection()->prepare('SELECT t.*, creator.username AS created_by_username, profile.display_name AS created_by_display_name FROM todos t INNER JOIN users creator ON creator.id = t.created_by_user_id LEFT JOIN user_profiles profile ON profile.user_id = creator.id WHERE t.user_id = :user_id ORDER BY t.is_completed ASC, t.created_at DESC, t.id DESC LIMIT ' . $limit);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function createForPartner(int $currentUserId, string $title, ?string $details, int $pointsReward): void
    {
        $currentUser = User::findById($currentUserId);
        $partnerId = !empty($currentUser['partner_user_id']) ? (int) $currentUser['partner_user_id'] : 0;
        if ($partnerId <= 0) {
            throw new InvalidArgumentException('Verbinde zuerst einen Partner, bevor du To-dos erstellen kannst.');
        }

        $title = trim($title);
        $details = $details !== null ? trim($details) : null;
        if ($title === '') {
            throw new InvalidArgumentException('Bitte gib einen Titel für das To-do ein.');
        }
        if ($pointsReward <= 0) {
            throw new InvalidArgumentException('Die HP-Belohnung muss größer als 0 sein.');
        }

        $stmt = Database::connection()->prepare('INSERT INTO todos (user_id, created_by_user_id, title, details, points_reward) VALUES (:user_id, :created_by_user_id, :title, :details, :points_reward)');
        $stmt->execute([
            'user_id' => $partnerId,
            'created_by_user_id' => $currentUserId,
            'title' => $title,
            'details' => $details !== '' ? $details : null,
            'points_reward' => $pointsReward,
        ]);
    }

    public static function toggle(int $userId, int $todoId): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = :id AND user_id = :user_id LIMIT 1 FOR UPDATE');
            $stmt->execute(['id' => $todoId, 'user_id' => $userId]);
            $todo = $stmt->fetch();
            if (!$todo) {
                throw new InvalidArgumentException('Das To-do wurde nicht gefunden.');
            }

            $isCompleted = (int) $todo['is_completed'] === 1;
            if ($isCompleted) {
                $pdo->prepare('UPDATE todos SET is_completed = 0, completed_at = NULL WHERE id = :id')->execute(['id' => $todoId]);
                Account::adjustBalance($userId, -((int) $todo['points_reward']), 'To-do rückgängig', 'Belohnung für „' . (string) $todo['title'] . '“ entfernt');
            } else {
                $pdo->prepare('UPDATE todos SET is_completed = 1, completed_at = CURRENT_TIMESTAMP WHERE id = :id')->execute(['id' => $todoId]);
                Account::adjustBalance($userId, (int) $todo['points_reward'], 'Partner-To-do erledigt', 'Belohnung für „' . (string) $todo['title'] . '“');
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}

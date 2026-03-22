<?php
declare(strict_types=1);

final class Transaction
{
    public static function create(int $userId, string $type, int $points, string $title, ?string $note, int $balanceAfter, ?\PDO $pdo = null): void
    {
        if (!in_array($type, ['credit', 'debit'], true)) {
            throw new InvalidArgumentException('Ungültiger Transaktionstyp.');
        }
        if ($points <= 0) {
            throw new InvalidArgumentException('Die Punkte müssen größer als 0 sein.');
        }

        $stmt = ($pdo ?? Database::connection())->prepare('INSERT INTO transactions (user_id, type, points, title, note, balance_after) VALUES (:user_id, :type, :points, :title, :note, :balance_after)');
        $stmt->execute([
            'user_id' => $userId,
            'type' => $type,
            'points' => $points,
            'title' => trim($title),
            'note' => $note !== null && trim($note) !== '' ? trim($note) : null,
            'balance_after' => $balanceAfter,
        ]);
    }

    public static function latestByUserId(int $userId, int $limit = 20): array
    {
        $limit = max(1, $limit);
        $stmt = Database::connection()->prepare('SELECT * FROM transactions WHERE user_id = :user_id ORDER BY created_at DESC, id DESC LIMIT ' . $limit);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function chartSeriesByUserId(int $userId, int $limit = 8): array
    {
        $limit = max(2, $limit);
        $stmt = Database::connection()->prepare('SELECT balance_after, created_at FROM (SELECT balance_after, created_at, id FROM transactions WHERE user_id = :user_id ORDER BY created_at DESC, id DESC LIMIT ' . $limit . ') recent ORDER BY created_at ASC, id ASC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}

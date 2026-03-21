<?php
declare(strict_types=1);

final class Transaction
{
    public static function create(int $userId, string $type, int $points, string $title, ?string $note, int $balanceAfter): void
    {
        if (!in_array($type, ['credit', 'debit'], true)) throw new InvalidArgumentException('Ungültiger Transaktionstyp.');
        if ($points <= 0) throw new InvalidArgumentException('Die Punkte müssen größer als 0 sein.');
        $stmt = Database::connection()->prepare('INSERT INTO transactions (user_id, type, points, title, note, balance_after) VALUES (:user_id, :type, :points, :title, :note, :balance_after)');
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
}

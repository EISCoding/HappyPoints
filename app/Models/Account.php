<?php
declare(strict_types=1);

final class Account
{
    public static function getUserWithAccount(int $userId): ?array
    {
        Profile::ensureForUser($userId);
        $stmt = Database::connection()->prepare(
            'SELECT u.id, u.email, u.username, u.partner_code, u.partner_user_id, u.email_verified_at, u.created_at, u.updated_at, a.balance,
                    up.display_name, up.headline, up.bio, up.city, up.favorite_activity, up.avatar_icon, up.accent_color, up.weekly_goal
             FROM users u
             INNER JOIN accounts a ON a.user_id = u.id
             LEFT JOIN user_profiles up ON up.user_id = u.id
             WHERE u.id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public static function adjustBalance(int $userId, int $delta, string $title, ?string $note = null, ?\PDO $pdo = null): void
    {
        if ($delta === 0) {
            throw new InvalidArgumentException('Die Änderung darf nicht 0 sein.');
        }

        $connection = $pdo ?? Database::connection();
        $startedTransaction = false;
        if (!$connection->inTransaction()) {
            $connection->beginTransaction();
            $startedTransaction = true;
        }

        try {
            $stmt = $connection->prepare('SELECT balance FROM accounts WHERE user_id = :user_id FOR UPDATE');
            $stmt->execute(['user_id' => $userId]);
            $currentBalance = (int) $stmt->fetchColumn();
            $newBalance = $currentBalance + $delta;
            if ($newBalance < 0) {
                throw new InvalidArgumentException('Der Kontostand darf nicht negativ werden.');
            }

            $connection->prepare('UPDATE accounts SET balance = :balance WHERE user_id = :user_id')->execute([
                'balance' => $newBalance,
                'user_id' => $userId,
            ]);
            Transaction::create($userId, $delta > 0 ? 'credit' : 'debit', abs($delta), $title, $note, $newBalance, $connection);

            if ($startedTransaction) {
                $connection->commit();
            }
        } catch (Throwable $e) {
            if ($startedTransaction && $connection->inTransaction()) {
                $connection->rollBack();
            }
            throw $e;
        }
    }
}

<?php
declare(strict_types=1);

final class EmailVerification
{
    public static function issueForUser(int $userId): string
    {
        $pdo = Database::connection();
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        $pdo->prepare('DELETE FROM email_verifications WHERE user_id = :user_id')->execute(['user_id' => $userId]);
        $pdo->prepare('INSERT INTO email_verifications (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, DATE_ADD(NOW(), INTERVAL 24 HOUR))')->execute([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
        ]);

        return $token;
    }

    public static function verify(string $token): ?int
    {
        $token = trim($token);
        if ($token === '') {
            return null;
        }

        $stmt = Database::connection()->prepare('SELECT * FROM email_verifications WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at >= NOW() LIMIT 1');
        $stmt->execute(['token_hash' => hash('sha256', $token)]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $userId = (int) $row['user_id'];
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $pdo->prepare('UPDATE users SET email_verified_at = NOW() WHERE id = :id')->execute(['id' => $userId]);
            $pdo->prepare('UPDATE email_verifications SET used_at = NOW() WHERE id = :id')->execute(['id' => (int) $row['id']]);
            $pdo->commit();
            return $userId;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

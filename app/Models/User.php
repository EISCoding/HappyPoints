<?php
declare(strict_types=1);

final class User
{
    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => mb_strtolower(trim($email))]);
        return $stmt->fetch() ?: null;
    }

    public static function findByPartnerCode(string $partnerCode): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE partner_code = :partner_code LIMIT 1');
        $stmt->execute(['partner_code' => strtoupper(trim($partnerCode))]);
        return $stmt->fetch() ?: null;
    }

    public static function create(string $email, string $username, string $password): int
    {
        $pdo = Database::connection();
        $email = mb_strtolower(trim($email));
        $username = trim($username);
        $password = trim($password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Bitte eine gültige E-Mail-Adresse eingeben.');
        }
        if (mb_strlen($username) < 3) {
            throw new InvalidArgumentException('Der Benutzername muss mindestens 3 Zeichen lang sein.');
        }
        if (mb_strlen($password) < 8) {
            throw new InvalidArgumentException('Das Passwort muss mindestens 8 Zeichen lang sein.');
        }
        if (self::findByEmail($email)) {
            throw new InvalidArgumentException('Diese E-Mail-Adresse ist bereits registriert.');
        }

        $partnerCode = self::generateUniquePartnerCode();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('INSERT INTO users (email, username, password_hash, partner_code, email_verified_at) VALUES (:email, :username, :password_hash, :partner_code, NULL)');
            $stmt->execute([
                'email' => $email,
                'username' => $username,
                'password_hash' => $passwordHash,
                'partner_code' => $partnerCode,
            ]);
            $userId = (int) $pdo->lastInsertId();
            $pdo->prepare('INSERT INTO accounts (user_id, balance) VALUES (:user_id, 0)')->execute(['user_id' => $userId]);
            Profile::ensureForUser($userId);
            $pdo->commit();
            return $userId;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function verifyPassword(array $user, string $password): bool
    {
        return password_verify($password, (string) ($user['password_hash'] ?? ''));
    }

    public static function generateUniquePartnerCode(): string
    {
        do {
            $code = self::randomPartnerCode(8);
        } while (self::findByPartnerCode($code));
        return $code;
    }

    public static function connectPartnersByCode(int $currentUserId, string $partnerCode): void
    {
        $pdo = Database::connection();
        $partnerCode = strtoupper(trim($partnerCode));
        if ($partnerCode === '') {
            throw new InvalidArgumentException('Bitte einen Partnercode eingeben.');
        }

        $currentUser = self::findById($currentUserId);
        if (!$currentUser) {
            throw new RuntimeException('Benutzer wurde nicht gefunden.');
        }
        if (!empty($currentUser['partner_user_id'])) {
            throw new InvalidArgumentException('Du bist bereits mit einem Partner verbunden.');
        }

        $partner = self::findByPartnerCode($partnerCode);
        if (!$partner) {
            throw new InvalidArgumentException('Partnercode nicht gefunden.');
        }
        if ((int) $partner['id'] === $currentUserId) {
            throw new InvalidArgumentException('Du kannst dich nicht mit dir selbst verbinden.');
        }
        if (!empty($partner['partner_user_id'])) {
            throw new InvalidArgumentException('Dieser Benutzer ist bereits mit einem anderen Partner verbunden.');
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('UPDATE users SET partner_user_id = :partner_user_id WHERE id = :id');
            $stmt->execute(['partner_user_id' => (int) $partner['id'], 'id' => $currentUserId]);
            $stmt->execute(['partner_user_id' => $currentUserId, 'id' => (int) $partner['id']]);
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function disconnectPartners(int $currentUserId): void
    {
        $pdo = Database::connection();
        $currentUser = self::findById($currentUserId);
        if (!$currentUser) {
            throw new RuntimeException('Benutzer wurde nicht gefunden.');
        }

        $partnerId = !empty($currentUser['partner_user_id']) ? (int) $currentUser['partner_user_id'] : null;
        if ($partnerId === null) {
            throw new InvalidArgumentException('Es ist kein Partner verbunden.');
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('UPDATE users SET partner_user_id = NULL WHERE id = :id');
            $stmt->execute(['id' => $currentUserId]);
            $stmt->execute(['id' => $partnerId]);
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function getPartnerForUser(int $userId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT partner_user_id FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $partnerUserId = $stmt->fetchColumn();
        if (!$partnerUserId) {
            return null;
        }

        Profile::ensureForUser((int) $partnerUserId);
        $stmt = Database::connection()->prepare(
            'SELECT u.id, u.email, u.username, u.partner_code, u.partner_user_id, u.created_at, u.updated_at, a.balance,
                    up.display_name, up.headline, up.bio, up.city, up.favorite_activity, up.avatar_icon, up.accent_color, up.weekly_goal
             FROM users u
             INNER JOIN accounts a ON a.user_id = u.id
             LEFT JOIN user_profiles up ON up.user_id = u.id
             WHERE u.id = :partner_user_id
             LIMIT 1'
        );
        $stmt->execute(['partner_user_id' => (int) $partnerUserId]);
        return $stmt->fetch() ?: null;
    }

    private static function randomPartnerCode(int $length = 8): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $maxIndex = strlen($chars) - 1;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $maxIndex)];
        }
        return $code;
    }
}

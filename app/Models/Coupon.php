<?php
declare(strict_types=1);

final class Coupon
{
    public static function latestAssignedToUser(int $userId, int $limit = 12): array
    {
        $limit = max(1, $limit);
        $stmt = Database::connection()->prepare('SELECT c.*, creator.username AS created_by_username, profile.display_name AS created_by_display_name FROM coupons c INNER JOIN users creator ON creator.id = c.created_by_user_id LEFT JOIN user_profiles profile ON profile.user_id = creator.id WHERE c.user_id = :user_id ORDER BY c.is_redeemed ASC, c.created_at DESC, c.id DESC LIMIT ' . $limit);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function createForPartner(int $currentUserId, string $title, ?string $description, int $cost): void
    {
        $currentUser = User::findById($currentUserId);
        $partnerId = !empty($currentUser['partner_user_id']) ? (int) $currentUser['partner_user_id'] : 0;
        if ($partnerId <= 0) {
            throw new InvalidArgumentException('Verbinde zuerst einen Partner, bevor du Coupons erstellen kannst.');
        }

        $title = trim($title);
        $description = $description !== null ? trim($description) : null;
        if ($title === '') {
            throw new InvalidArgumentException('Bitte gib einen Titel für den Coupon ein.');
        }
        if ($cost <= 0) {
            throw new InvalidArgumentException('Die Kosten müssen größer als 0 sein.');
        }

        $stmt = Database::connection()->prepare('INSERT INTO coupons (user_id, created_by_user_id, title, description, cost) VALUES (:user_id, :created_by_user_id, :title, :description, :cost)');
        $stmt->execute([
            'user_id' => $partnerId,
            'created_by_user_id' => $currentUserId,
            'title' => $title,
            'description' => $description !== '' ? $description : null,
            'cost' => $cost,
        ]);
    }

    public static function redeem(int $userId, int $couponId): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT * FROM coupons WHERE id = :id AND user_id = :user_id LIMIT 1 FOR UPDATE');
            $stmt->execute(['id' => $couponId, 'user_id' => $userId]);
            $coupon = $stmt->fetch();
            if (!$coupon) {
                throw new InvalidArgumentException('Der Coupon wurde nicht gefunden.');
            }
            if ((int) $coupon['is_redeemed'] === 1) {
                throw new InvalidArgumentException('Dieser Coupon wurde bereits eingelöst.');
            }

            Account::adjustBalance($userId, -((int) $coupon['cost']), 'Partner-Coupon eingelöst', (string) $coupon['title']);
            $pdo->prepare('UPDATE coupons SET is_redeemed = 1, redeemed_at = CURRENT_TIMESTAMP WHERE id = :id')->execute(['id' => $couponId]);
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}

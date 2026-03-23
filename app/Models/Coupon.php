<?php
declare(strict_types=1);

final class Coupon
{
    public static function latestByUserId(int $userId, int $limit = 12): array
    {
        $limit = max(1, $limit);
        $stmt = Database::connection()->prepare('SELECT * FROM coupons WHERE user_id = :user_id ORDER BY is_redeemed ASC, created_at DESC, id DESC LIMIT ' . $limit);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function create(int $userId, string $title, ?string $description, int $cost): void
    {
        $title = trim($title);
        $description = $description !== null ? trim($description) : null;
        if ($title === '') {
            throw new InvalidArgumentException('Bitte gib einen Titel für den Coupon ein.');
        }
        if ($cost <= 0) {
            throw new InvalidArgumentException('Die Kosten müssen größer als 0 sein.');
        }

        $stmt = Database::connection()->prepare('INSERT INTO coupons (user_id, title, description, cost) VALUES (:user_id, :title, :description, :cost)');
        $stmt->execute([
            'user_id' => $userId,
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

            Account::adjustBalance($userId, -((int) $coupon['cost']), 'Coupon eingelöst', (string) $coupon['title']);
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

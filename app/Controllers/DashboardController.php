<?php
declare(strict_types=1);

final class DashboardController
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
        $transactions = Transaction::latestByUserId($userId, 12);
        $todos = Todo::latestAssignedToUser($userId, 8);
        $coupons = Coupon::latestAssignedToUser($userId, 8);
        $chartPoints = Transaction::chartSeriesByUserId($userId, 8);

        $chartLabels = [];
        $chartValues = [];
        foreach ($chartPoints as $point) {
            $timestamp = strtotime((string) $point['created_at']);
            $chartLabels[] = $timestamp ? date('d.m.', $timestamp) : (string) $point['created_at'];
            $chartValues[] = (int) $point['balance_after'];
        }
        if ($chartLabels === []) {
            $chartLabels = ['Heute'];
            $chartValues = [(int) ($user['balance'] ?? 0)];
        }

        View::render('dashboard.index', [
            'user' => $user,
            'partner' => $partner,
            'transactions' => $transactions,
            'todos' => $todos,
            'coupons' => $coupons,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ]);
    }

    public function adjustBalance(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $mode = (string) ($_POST['mode'] ?? 'plus');
        $amount = (int) ($_POST['amount'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? 'Manuelle Buchung'));
        $note = trim((string) ($_POST['note'] ?? ''));

        try {
            if ($amount <= 0) {
                throw new InvalidArgumentException('Bitte einen Betrag größer als 0 eingeben.');
            }
            $delta = $mode === 'minus' ? -$amount : $amount;
            Account::adjustBalance($userId, $delta, $title, $note !== '' ? $note : null);
            flashMessage('success', 'Der Kontostand wurde aktualisiert.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
            withOldInput(['mode' => $mode, 'amount' => $amount, 'title' => $title, 'note' => $note]);
        }

        Redirect::to('/dashboard');
    }

    public function createTodo(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $title = (string) ($_POST['title'] ?? '');
        $details = (string) ($_POST['details'] ?? '');
        $pointsReward = (int) ($_POST['points_reward'] ?? 5);

        try {
            Todo::createForPartner($userId, $title, $details, $pointsReward);
            flashMessage('success', 'Partner-To-do gespeichert.');
        } catch (Throwable $e) {
            withOldInput(['todo_title' => $title, 'todo_details' => $details, 'todo_points_reward' => $pointsReward]);
            flashMessage('error', $e->getMessage());
        }

        Redirect::to('/dashboard#todos');
    }

    public function toggleTodo(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $todoId = (int) ($_POST['todo_id'] ?? 0);
        try {
            Todo::toggle($userId, $todoId);
            flashMessage('success', 'To-do Status aktualisiert.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
        }

        Redirect::to('/dashboard#todos');
    }

    public function createCoupon(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $title = (string) ($_POST['title'] ?? '');
        $description = (string) ($_POST['description'] ?? '');
        $cost = (int) ($_POST['cost'] ?? 10);

        try {
            Coupon::createForPartner($userId, $title, $description, $cost);
            flashMessage('success', 'Partner-Coupon gespeichert.');
        } catch (Throwable $e) {
            withOldInput(['coupon_title' => $title, 'coupon_description' => $description, 'coupon_cost' => $cost]);
            flashMessage('error', $e->getMessage());
        }

        Redirect::to('/dashboard#coupons');
    }

    public function redeemCoupon(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $couponId = (int) ($_POST['coupon_id'] ?? 0);
        try {
            Coupon::redeem($userId, $couponId);
            flashMessage('success', 'Coupon eingelöst.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
        }

        Redirect::to('/dashboard#coupons');
    }
}

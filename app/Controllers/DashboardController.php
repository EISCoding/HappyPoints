<?php
declare(strict_types=1);

final class DashboardController
{
    public function index(): void
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) Redirect::to('/login');
        $user = Account::getUserWithAccount($userId);
        $transactions = Transaction::latestByUserId($userId, 15);
        View::render('dashboard.index', ['user' => $user, 'transactions' => $transactions]);
    }

    public function adjustBalance(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) Redirect::to('/login');
        $mode = (string) ($_POST['mode'] ?? 'plus');
        $amount = (int) ($_POST['amount'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? 'Manuelle Buchung'));
        $note = trim((string) ($_POST['note'] ?? ''));
        try {
            if ($amount <= 0) throw new InvalidArgumentException('Bitte einen Betrag größer als 0 eingeben.');
            $delta = $mode === 'minus' ? -$amount : $amount;
            Account::adjustBalance($userId, $delta, $title, $note !== '' ? $note : null);
            flashMessage('success', 'Der Kontostand wurde aktualisiert.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
            withOldInput(['mode' => $mode, 'amount' => $amount, 'title' => $title, 'note' => $note]);
        }
        Redirect::to('/dashboard');
    }
}

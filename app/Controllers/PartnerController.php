<?php
declare(strict_types=1);

final class PartnerController
{
    public function index(): void
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) Redirect::to('/login');
        $user = Account::getUserWithAccount($userId);
        $partner = User::getPartnerForUser($userId);
        View::render('partner.index', ['user' => $user, 'partner' => $partner]);
    }

    public function connect(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) Redirect::to('/login');
        $partnerCode = (string) ($_POST['partner_code'] ?? '');
        try {
            User::connectPartnersByCode($userId, $partnerCode);
            flashMessage('success', 'Partner erfolgreich verbunden.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
            withOldInput(['partner_code' => $partnerCode]);
        }
        Redirect::to('/partner');
    }

    public function disconnect(): never
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) Redirect::to('/login');
        try {
            User::disconnectPartners($userId);
            flashMessage('success', 'Partnerverbindung wurde getrennt.');
        } catch (Throwable $e) {
            flashMessage('error', $e->getMessage());
        }
        Redirect::to('/partner');
    }
}

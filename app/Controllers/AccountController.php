<?php
declare(strict_types=1);

final class AccountController
{
    public function index(): void
    {
        Auth::requireLogin();
        $userId = Auth::id();
        if ($userId === null) Redirect::to('/login');
        $user = Account::getUserWithAccount($userId);
        $partner = User::getPartnerForUser($userId);
        View::render('account.index', ['user' => $user, 'partner' => $partner]);
    }
}

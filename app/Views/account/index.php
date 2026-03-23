<?php
declare(strict_types=1);

function accountDate(string $value): string
{
    $time = strtotime($value);
    return $time ? date('d.m.Y H:i', $time) : $value;
}

$displayName = (string) (($user['display_name'] ?? '') !== '' ? $user['display_name'] : $user['username']);
$headline = (string) (($user['headline'] ?? '') !== '' ? $user['headline'] : 'Gemeinsam Punkte sammeln & schöne Momente planen');
$bio = (string) (($user['bio'] ?? '') !== '' ? $user['bio'] : 'Passe dein Profil an, damit Dashboard und Partneransicht persönlicher wirken.');
$avatarIcon = (string) ($user['avatar_icon'] ?? 'bx-happy-heart-eyes');
$accentColor = (string) ($user['accent_color'] ?? '#7c9cff');
$verificationStatus = !empty($user['email_verified_at']) ? 'Bestätigt' : 'Offen';
?>

<section class="grid gap-4 sm:gap-5 xl:grid-cols-[1.02fr_0.98fr]">
    <div class="rounded-[32px] border border-white/10 bg-gradient-to-br from-slate-800/90 via-slate-900/90 to-slate-950 p-5 shadow-glow sm:p-8">
        <div class="flex items-start gap-4">
            <div class="flex h-20 w-20 items-center justify-center rounded-[28px] text-4xl text-white shadow-card" style="background: linear-gradient(135deg, <?= e($accentColor) ?>, #ff8cc6);"><i class='bx <?= e($avatarIcon) ?>'></i></div>
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.24em] text-slate-300"><i class='bx bx-user-circle text-brand text-base'></i>Konto</div>
                <h1 class="mt-4 text-4xl font-black tracking-tight text-white"><?= e($displayName) ?></h1>
                <p class="mt-2 text-sm text-slate-300">@<?= e((string) $user['username']) ?> · <?= (int) $user['balance'] ?> HP</p>
            </div>
        </div>
        <p class="mt-6 text-lg font-semibold text-white"><?= e($headline) ?></p>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-400"><?= e($bio) ?></p>
        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">E-Mail</div><div class="mt-2 text-sm font-bold text-white"><?= e((string) $user['email']) ?></div></div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Verifizierung</div><div class="mt-2 text-sm font-bold <?= !empty($user['email_verified_at']) ? 'text-emerald-300' : 'text-amber-300' ?>"><?= e($verificationStatus) ?></div></div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Partnercode</div><div class="mt-2 text-lg font-black text-white"><?= e((string) $user['partner_code']) ?></div></div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Registriert</div><div class="mt-2 text-sm font-bold text-white"><?= e(accountDate((string) $user['created_at'])) ?></div></div>
        </div>
    </div>

    <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-edit-alt'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Konto anpassen</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Farben, Icon, Bio und Ziele sind direkt anpassbar.</p>
        <form method="post" action="/account/profile" class="mt-7 space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="display_name" type="text" value="<?= e((string) old('display_name', $user['display_name'] ?? '')) ?>" placeholder="Anzeigename">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="headline" type="text" value="<?= e((string) old('headline', $user['headline'] ?? '')) ?>" placeholder="Headline">
            </div>
            <textarea name="bio" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" placeholder="Bio"><?= e((string) old('bio', $user['bio'] ?? '')) ?></textarea>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="city" type="text" value="<?= e((string) old('city', $user['city'] ?? '')) ?>" placeholder="Stadt">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="favorite_activity" type="text" value="<?= e((string) old('favorite_activity', $user['favorite_activity'] ?? '')) ?>" placeholder="Aktivität">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="avatar_icon" type="text" value="<?= e((string) old('avatar_icon', $user['avatar_icon'] ?? 'bx-happy-heart-eyes')) ?>" placeholder="bx-happy-heart-eyes">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="weekly_goal" type="number" min="1" value="<?= e((string) old('weekly_goal', (string) ($user['weekly_goal'] ?? '50'))) ?>" placeholder="Wochenziel">
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Akzentfarbe</label>
                <input name="accent_color" type="color" value="<?= e((string) old('accent_color', $accentColor)) ?>" class="h-10 w-20 rounded-xl border border-white/10 bg-transparent">
            </div>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card" type="submit"><i class='bx bx-save text-lg'></i> Profil speichern</button>
        </form>
    </div>
</section>

<section class="mt-4 grid gap-4 sm:mt-5 sm:gap-5 xl:grid-cols-2">
    <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-id-card'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Kontoübersicht</h2>
        <div class="mt-6 space-y-3">
            <?php foreach ([
                'Benutzername' => (string) $user['username'],
                'E-Mail' => (string) $user['email'],
                'Kontostand' => (int) $user['balance'] . ' HP',
                'Partnercode' => (string) $user['partner_code'],
                'Verifiziert am' => !empty($user['email_verified_at']) ? accountDate((string) $user['email_verified_at']) : 'Noch nicht bestätigt',
            ] as $label => $value): ?>
                <div class="flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-4"><div class="text-sm text-slate-400"><?= e($label) ?></div><div class="text-right text-sm font-bold text-white"><?= e((string) $value) ?></div></div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-pinksoft/15 text-2xl text-pinksoft"><i class='bx bx-link-alt'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Partnerübersicht</h2>
        <?php if ($partner): ?>
            <div class="mt-6 rounded-[26px] border border-white/10 bg-white/5 p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-[24px] text-3xl text-white shadow-card" style="background: linear-gradient(135deg, <?= e((string) ($partner['accent_color'] ?? '#7c9cff')) ?>, #ff8cc6);"><i class='bx <?= e((string) ($partner['avatar_icon'] ?? 'bx-user')) ?>'></i></div>
                    <div><div class="text-xl font-black text-white"><?= e((string) (($partner['display_name'] ?? '') !== '' ? $partner['display_name'] : $partner['username'])) ?></div><div class="text-sm text-slate-400"><?= e((string) $partner['email']) ?></div></div>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4"><div class="text-xs uppercase tracking-[0.2em] text-slate-400">Kontostand</div><div class="mt-2 text-lg font-black text-white"><?= (int) $partner['balance'] ?> HP</div></div>
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4"><div class="text-xs uppercase tracking-[0.2em] text-slate-400">Status</div><div class="mt-2 text-lg font-black text-white">Verbunden</div></div>
                </div>
            </div>
        <?php else: ?>
            <div class="mt-6 rounded-[26px] border border-dashed border-white/10 bg-white/5 p-4 text-sm text-slate-400">Aktuell ist kein Partner verbunden.</div>
        <?php endif; ?>
    </div>
</section>

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
$weeklyGoal = (int) ($user['weekly_goal'] ?? 50);
$city = (string) ($user['city'] ?? '');
$favActivity = (string) ($user['favorite_activity'] ?? '');
$profileCompletion = 40;
foreach ([$displayName, $headline, $bio, $city, $favActivity] as $fieldValue) {
    if (trim((string) $fieldValue) !== '') {
        $profileCompletion += 12;
    }
}
$profileCompletion = min(100, $profileCompletion);
?>

<section class="mt-5 grid gap-5 xl:grid-cols-[0.95fr_1.05fr]">
    <div class="rounded-[34px] border border-white/10 bg-gradient-to-br from-slate-800/90 via-slate-900/90 to-slate-950 p-6 shadow-glow sm:p-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-20 w-20 items-center justify-center rounded-[28px] text-4xl text-white shadow-card" style="background: linear-gradient(135deg, <?= e($accentColor) ?>, #ff8cc6);">
                    <i class='bx <?= e($avatarIcon) ?>'></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.25em] text-slate-300">
                        <i class='bx bx-user-circle text-brand text-base'></i>
                        Profil & Einstellungen
                    </div>
                    <h1 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl"><?= e($displayName) ?></h1>
                    <p class="mt-2 text-base text-slate-300">@<?= e((string) $user['username']) ?> · <?= (int) $user['balance'] ?> HP</p>
                </div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 px-4 py-4 sm:min-w-[220px]">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Profilstatus</div>
                <div class="mt-2 text-2xl font-black text-white"><?= $profileCompletion ?>%</div>
                <div class="mt-3 h-3 rounded-full bg-white/10">
                    <div class="h-3 rounded-full" style="width: <?= $profileCompletion ?>%; background: linear-gradient(90deg, <?= e($accentColor) ?>, #ff8cc6);"></div>
                </div>
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

        <p class="mt-6 text-lg font-semibold text-white"><?= e($headline) ?></p>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-400"><?= e($bio) ?></p>

        <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Partnercode</div>
                <div class="mt-2 text-xl font-black text-white"><?= e((string) $user['partner_code']) ?></div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Wochenziel</div>
                <div class="mt-2 text-xl font-black text-white"><?= $weeklyGoal ?> HP</div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Stadt</div>
                <div class="mt-2 text-xl font-black text-white"><?= e($city !== '' ? $city : 'Offen') ?></div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Lieblingsaktivität</div>
                <div class="mt-2 text-xl font-black text-white"><?= e($favActivity !== '' ? $favActivity : 'Offen') ?></div>
            </div>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card" type="submit"><i class='bx bx-save text-lg'></i> Profil speichern</button>
        </form>
    </div>

    <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-edit-alt'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Profil anpassen</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Farben, Icon, Texte und Ziele sind jetzt speicherbar und wirken sich direkt auf die Oberfläche aus.</p>

        <form method="post" action="/account/profile" class="mt-8 space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="display_name">Anzeigename</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="display_name" name="display_name" type="text" value="<?= e((string) old('display_name', $user['display_name'] ?? '')) ?>" placeholder="Wie soll dein Name erscheinen?">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="headline">Headline</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="headline" name="headline" type="text" value="<?= e((string) old('headline', $user['headline'] ?? '')) ?>" placeholder="Kurzer Profil-Satz">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" placeholder="Was ist euch wichtig?"><?= e((string) old('bio', $user['bio'] ?? '')) ?></textarea>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="city">Stadt</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="city" name="city" type="text" value="<?= e((string) old('city', $user['city'] ?? '')) ?>">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="favorite_activity">Aktivität</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="favorite_activity" name="favorite_activity" type="text" value="<?= e((string) old('favorite_activity', $user['favorite_activity'] ?? '')) ?>">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="avatar_icon">Boxicon Klasse</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="avatar_icon" name="avatar_icon" type="text" value="<?= e((string) old('avatar_icon', $user['avatar_icon'] ?? 'bx-happy-heart-eyes')) ?>" placeholder="bx-happy-heart-eyes">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="weekly_goal">Wochenziel</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="weekly_goal" name="weekly_goal" type="number" min="1" value="<?= e((string) old('weekly_goal', (string) ($user['weekly_goal'] ?? '50'))) ?>">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="accent_color">Akzentfarbe</label>
                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3">
                    <input id="accent_color" name="accent_color" type="color" value="<?= e((string) old('accent_color', $accentColor)) ?>" class="h-10 w-16 rounded-xl border border-white/10 bg-transparent">
                    <span class="text-sm text-slate-300">Die Farbe wirkt sich auf Header, Buttons und Profilakzente aus.</span>
                </div>
            </div>

            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card transition hover:-translate-y-0.5" type="submit">
                <i class='bx bx-save text-lg'></i> Profil speichern
            </button>
        </form>
    </div>
</section>

<section class="mt-5 grid gap-5 xl:grid-cols-2">
    <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-id-card'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Kontodaten</h2>
        <div class="mt-6 space-y-3">
            <?php foreach ([
                'Benutzername' => (string) $user['username'],
                'E-Mail' => (string) $user['email'],
                'Partnercode' => (string) $user['partner_code'],
                'Kontostand' => (int) $user['balance'] . ' HP',
                'Registriert am' => accountDate((string) $user['created_at']),
            ] as $label => $value): ?>
                <div class="flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                    <div class="text-sm text-slate-400"><?= e($label) ?></div>
                    <div class="text-right text-sm font-bold text-white"><?= e((string) $value) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-pinksoft/15 text-2xl text-pinksoft"><i class='bx bx-link-alt'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Partnerübersicht</h2>
        <?php if ($partner): ?>
            <div class="mt-6 rounded-[28px] border border-white/10 bg-white/5 p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-[24px] text-3xl text-white shadow-card" style="background: linear-gradient(135deg, <?= e((string) ($partner['accent_color'] ?? '#7c9cff')) ?>, #ff8cc6);">
                        <i class='bx <?= e((string) ($partner['avatar_icon'] ?? 'bx-user')) ?>'></i>
                    </div>
                    <div>
                        <div class="text-xl font-black text-white"><?= e((string) (($partner['display_name'] ?? '') !== '' ? $partner['display_name'] : $partner['username'])) ?></div>
                        <div class="text-sm text-slate-400"><?= e((string) $partner['email']) ?></div>
                    </div>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Kontostand</div>
                        <div class="mt-2 text-lg font-black text-white"><?= (int) $partner['balance'] ?> HP</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Partnercode</div>
                        <div class="mt-2 text-lg font-black text-white"><?= e((string) $partner['partner_code']) ?></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="mt-6 rounded-[28px] border border-dashed border-white/10 bg-white/5 p-5 text-sm text-slate-400">Aktuell ist kein Partner verbunden.</div>
        <?php endif; ?>
    </div>
</section>

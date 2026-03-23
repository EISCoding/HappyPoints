<?php
declare(strict_types=1);

$balance = (int) ($user['balance'] ?? 0);
$username = (string) ($user['username'] ?? 'Benutzer');
$displayName = (string) (($user['display_name'] ?? '') !== '' ? $user['display_name'] : $username);
$headline = (string) (($user['headline'] ?? '') !== '' ? $user['headline'] : 'Gemeinsam Ziele erreichen und Punkte sammeln.');
$bio = (string) (($user['bio'] ?? '') !== '' ? $user['bio'] : 'Dein personalisiertes Happypoints Dashboard bündelt Profil, Aufgaben, Coupons und Verlauf.');
$avatarIcon = (string) ($user['avatar_icon'] ?? 'bx-happy-heart-eyes');
$weeklyGoal = max(1, (int) ($user['weekly_goal'] ?? 50));
$transactionsCount = is_array($transactions) ? count($transactions) : 0;
$completedTodos = 0;
$openTodos = 0;
$redeemedCoupons = 0;
$availableCoupons = 0;
$creditCount = 0;
$debitCount = 0;
$completedTodos = 0;
$openTodos = 0;
$redeemedCoupons = 0;
$availableCoupons = 0;

foreach ($transactions as $transaction) {
    (($transaction['type'] ?? '') === 'credit') ? $creditCount++ : $debitCount++;
}
foreach ($todos as $todo) {
    ((int) ($todo['is_completed'] ?? 0) === 1) ? $completedTodos++ : $openTodos++;
}
foreach ($coupons as $coupon) {
    ((int) ($coupon['is_redeemed'] ?? 0) === 1) ? $redeemedCoupons++ : $availableCoupons++;
}
foreach ($todos as $todo) {
    ((int) ($todo['is_completed'] ?? 0) === 1) ? $completedTodos++ : $openTodos++;
}
foreach ($coupons as $coupon) {
    ((int) ($coupon['is_redeemed'] ?? 0) === 1) ? $redeemedCoupons++ : $availableCoupons++;
}
$progressPercent = min(100, (int) round(($balance / $weeklyGoal) * 100));

function hpFormat(int $value): string
{
    return number_format($value, 0, ',', '.') . ' HP';
}

function dashboardDate(string $value): string
{
    $time = strtotime($value);
    return $time ? date('d.m.Y H:i', $time) : $value;
}
?>

<section class="mt-5 grid gap-5 xl:grid-cols-[0.95fr_1.05fr]">
    <div class="overflow-hidden rounded-[34px] border border-white/10 bg-gradient-to-br from-indigo-950/90 via-slate-900/95 to-slate-950 p-6 shadow-glow sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.25em] text-slate-300">
            <i class='bx <?= e($avatarIcon) ?> text-brand text-base'></i>
            Mein Bereich
        </div>

        <div class="mt-6 flex items-start justify-between gap-4">
            <div>
                <div class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Aktueller Stand</div>
                <h1 class="mt-2 text-4xl font-black tracking-tight text-white sm:text-6xl"><?= hpFormat($balance) ?></h1>
            </div>
            <div class="hidden h-16 w-16 items-center justify-center rounded-[22px] bg-white/10 text-3xl text-white sm:flex">
                <i class='bx <?= e($avatarIcon) ?>'></i>
            </div>
        </div>

        <p class="mt-4 text-base leading-7 text-slate-300"><?= e($headline) ?></p>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400"><?= e($bio) ?></p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="#buchung" class="inline-flex items-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card transition hover:-translate-y-0.5">
                <i class='bx bx-wallet text-lg'></i> Punkte anpassen
            </a>
            <a href="#chart" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-100 transition hover:-translate-y-0.5 hover:bg-white/10">
                <i class='bx bx-line-chart text-lg'></i> Verlauf ansehen
            </a>
        </div>

        <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Fortschritt</div>
                <div class="mt-2 text-xl font-black text-white"><?= $progressPercent ?>%</div>
                <div class="mt-3 h-3 rounded-full bg-white/10">
                    <div class="h-3 rounded-full bg-gradient-to-r from-brand to-pinksoft" style="width: <?= $progressPercent ?>%"></div>
                </div>
                <div class="mt-2 text-xs text-slate-400">Wochenziel: <?= $weeklyGoal ?> HP</div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                    <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Offene To-dos</div>
                    <div class="mt-2 text-2xl font-black text-white"><?= $openTodos ?></div>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                    <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Coupons</div>
                    <div class="mt-2 text-2xl font-black text-white"><?= $availableCoupons ?></div>
                </div>
            </div>
            <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" id="title" name="title" type="text" value="<?= e((string) old('title', 'Manuelle Buchung')) ?>" required>
            <textarea id="note" name="note" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" placeholder="Kurze Notiz zur Buchung"><?= e((string) old('note')) ?></textarea>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card" type="submit"><i class='bx bx-save text-lg'></i> Buchung speichern</button>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Benutzer</div>
            <div class="mt-3 text-2xl font-black text-white"><?= e($displayName) ?></div>
            <div class="mt-1 text-sm text-slate-400">@<?= e($username) ?></div>
        </div>
        <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Manuelle Buchungen</div>
            <div class="mt-3 text-2xl font-black text-white"><?= $transactionsCount ?></div>
            <div class="mt-1 text-sm text-slate-400">Letzte Einträge im Verlauf</div>
        </div>
        <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Erledigte To-dos</div>
            <div class="mt-3 text-2xl font-black text-emerald-300"><?= $completedTodos ?></div>
            <div class="mt-1 text-sm text-slate-400">Belohnungen automatisch verbucht</div>
        </div>
        <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Einlösungen</div>
            <div class="mt-3 text-2xl font-black text-amber-300"><?= $redeemedCoupons ?></div>
            <div class="mt-1 text-sm text-slate-400">Bereits genutzte Belohnungen</div>
        </div>
    </div>

<section class="mt-5 grid gap-5 xl:grid-cols-[0.95fr_1.05fr]">
    <div id="buchung" class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-wallet'></i></div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Punktestand anpassen</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Passe den Kontostand direkt an – passend zum Referenzlayout mit großem Fokus auf HP.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 px-4 py-3 text-right">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Aktuell</div>
                <div class="mt-1 text-2xl font-black text-white"><?= hpFormat($balance) ?></div>
            </div>
        </div>

        <form method="post" action="/dashboard/balance" class="mt-8 space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="mode">Aktion</label>
                    <select id="mode" name="mode" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand">
                        <option value="plus" <?= old('mode', 'plus') === 'plus' ? 'selected' : '' ?>>Punkte hinzufügen</option>
                        <option value="minus" <?= old('mode') === 'minus' ? 'selected' : '' ?>>Punkte abziehen</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="amount">Betrag</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="amount" name="amount" type="number" min="1" value="<?= e((string) old('amount', '5')) ?>" required>
                </div>
                <div class="text-right text-xs text-slate-400"><div>+ <?= $creditCount ?></div><div>- <?= $debitCount ?></div></div>
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="title">Titel</label>
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="title" name="title" type="text" value="<?= e((string) old('title', 'Manuelle Buchung')) ?>" required>
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="note">Notiz</label>
                <textarea id="note" name="note" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" placeholder="Kurze Notiz zur Buchung"><?= e((string) old('note')) ?></textarea>
            </div>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card transition hover:-translate-y-0.5" type="submit">
                <i class='bx bx-save text-lg'></i> Buchung speichern
            </button>
        </form>
    </div>

    <div class="grid gap-5">
        <div id="chart" class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/15 text-2xl text-emerald-300"><i class='bx bx-line-chart'></i></div>
                    <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Kontostand Verlauf</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">Visualisiert mit Chart.js auf Basis der gespeicherten Transaktionen.</p>
                </div>
                <div class="text-right text-sm text-slate-400">
                    <div><?= $creditCount ?> Gutschriften</div>
                    <div><?= $debitCount ?> Abbuchungen</div>
                </div>
            </div>
            <div class="mt-6 rounded-[28px] border border-white/10 bg-slate-950/70 p-4">
                <canvas id="balanceChart" height="190"></canvas>
            </div>
        </div>

        <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-pinksoft/15 text-2xl text-pinksoft"><i class='bx bx-badge-check'></i></div>
                    <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Schnellstatus</h2>
                </div>
            </div>
            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Profilziel</div>
                    <div class="mt-2 text-lg font-black text-white"><?= $weeklyGoal ?> HP / Woche</div>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Offene Coupons</div>
                    <div class="mt-2 text-lg font-black text-white"><?= $availableCoupons ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mt-5 grid gap-5 xl:grid-cols-2">
    <div id="todos" class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/15 text-2xl text-emerald-300"><i class='bx bx-task'></i></div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white">To-dos</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Jedes To-do wird in der Datenbank gespeichert. Beim Erledigen werden HP automatisch gutgeschrieben.</p>
            </div>
        </div>

        <form method="post" action="/dashboard/todos" class="mt-8 space-y-4 rounded-[28px] border border-white/10 bg-slate-950/60 p-4">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="todo-title">Titel</label>
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="todo-title" name="title" type="text" value="<?= e((string) old('todo_title')) ?>" placeholder="z. B. Küche aufräumen" required>
            </div>
            <div class="grid gap-4 sm:grid-cols-[1fr_140px]">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="todo-details">Details</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="todo-details" name="details" type="text" value="<?= e((string) old('todo_details')) ?>" placeholder="Optionaler Kontext">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="todo-points">HP</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="todo-points" name="points_reward" type="number" min="1" value="<?= e((string) old('todo_points_reward', '10')) ?>" required>
                </div>
            </div>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-bold text-slate-950 shadow-card transition hover:-translate-y-0.5" type="submit">
                <i class='bx bx-plus text-lg'></i> To-do hinzufügen
            </button>
        </form>

        <div class="mt-6 space-y-3">
            <?php if (empty($todos)): ?>
                <div class="rounded-[28px] border border-dashed border-white/10 bg-white/5 p-5 text-sm text-slate-400">Noch keine To-dos vorhanden.</div>
            <?php else: ?>
                <?php foreach ($todos as $todo): ?>
                    <?php $isCompleted = (int) ($todo['is_completed'] ?? 0) === 1; ?>
                    <div class="rounded-[28px] border border-white/10 <?= $isCompleted ? 'bg-emerald-500/10' : 'bg-white/5' ?> p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl <?= $isCompleted ? 'bg-emerald-500 text-slate-950' : 'bg-slate-800 text-slate-200' ?>"><i class='bx <?= $isCompleted ? 'bx-check-double' : 'bx-circle' ?> text-xl'></i></span>
                                    <div>
                                        <div class="text-base font-bold text-white"><?= e((string) $todo['title']) ?></div>
                                        <div class="text-xs text-slate-400"><?= e((string) ($todo['details'] ?? 'Ohne Zusatzinfo')) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="rounded-2xl bg-slate-950/70 px-3 py-2 text-sm font-bold text-white">+<?= (int) $todo['points_reward'] ?> HP</div>
                                <form method="post" action="/dashboard/todos/toggle">
                                    <input type="hidden" name="todo_id" value="<?= (int) $todo['id'] ?>">
                                    <button class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-bold transition <?= $isCompleted ? 'bg-rose-400/90 text-slate-950' : 'bg-emerald-500 text-slate-950' ?>" type="submit">
                                        <i class='bx <?= $isCompleted ? 'bx-undo' : 'bx-check' ?> text-lg'></i>
                                        <?= $isCompleted ? 'Rückgängig' : 'Erledigt' ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="coupons" class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amberish/15 text-2xl text-amberish"><i class='bx bx-gift'></i></div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Coupons</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Auch Coupons werden persistiert. Beim Einlösen werden HP direkt vom Konto abgezogen.</p>
            </div>
        </div>

        <form method="post" action="/dashboard/coupons" class="mt-8 space-y-4 rounded-[28px] border border-white/10 bg-slate-950/60 p-4">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="coupon-title">Belohnung</label>
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="coupon-title" name="title" type="text" value="<?= e((string) old('coupon_title')) ?>" placeholder="z. B. Kinoabend" required>
            </div>
            <div class="grid gap-4 sm:grid-cols-[1fr_140px]">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="coupon-description">Beschreibung</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="coupon-description" name="description" type="text" value="<?= e((string) old('coupon_description')) ?>" placeholder="Optionaler Text">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="coupon-cost">Kosten</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="coupon-cost" name="cost" type="number" min="1" value="<?= e((string) old('coupon_cost', '20')) ?>" required>
                </div>
            </div>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-amberish px-4 py-3 text-sm font-bold text-slate-950 shadow-card transition hover:-translate-y-0.5" type="submit">
                <i class='bx bx-purchase-tag text-lg'></i> Coupon hinzufügen
            </button>
        </form>

        <div class="mt-6 space-y-3">
            <?php if (empty($coupons)): ?>
                <div class="rounded-[28px] border border-dashed border-white/10 bg-white/5 p-5 text-sm text-slate-400">Noch keine Coupons vorhanden.</div>
            <?php else: ?>
                <?php foreach ($coupons as $coupon): ?>
                    <?php $isRedeemed = (int) ($coupon['is_redeemed'] ?? 0) === 1; ?>
                    <div class="rounded-[28px] border border-white/10 <?= $isRedeemed ? 'bg-rose-500/10' : 'bg-white/5' ?> p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl <?= $isRedeemed ? 'bg-rose-400 text-slate-950' : 'bg-amberish text-slate-950' ?>"><i class='bx <?= $isRedeemed ? 'bx-check-shield' : 'bx-gift' ?> text-xl'></i></span>
                                    <div>
                                        <div class="text-base font-bold text-white"><?= e((string) $coupon['title']) ?></div>
                                        <div class="text-xs text-slate-400"><?= e((string) ($coupon['description'] ?? 'Ohne Beschreibung')) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="rounded-2xl bg-slate-950/70 px-3 py-2 text-sm font-bold text-white">-<?= (int) $coupon['cost'] ?> HP</div>
                                <?php if ($isRedeemed): ?>
                                    <span class="inline-flex items-center gap-2 rounded-2xl bg-rose-400/90 px-4 py-2 text-sm font-bold text-slate-950">
                                        <i class='bx bx-lock-alt text-lg'></i> Eingelöst
                                    </span>
                                <?php else: ?>
                                    <form method="post" action="/dashboard/coupons/redeem">
                                        <input type="hidden" name="coupon_id" value="<?= (int) $coupon['id'] ?>">
                                        <button class="inline-flex items-center gap-2 rounded-2xl bg-amberish px-4 py-2 text-sm font-bold text-slate-950 transition hover:-translate-y-0.5" type="submit">
                                            <i class='bx bx-cart-download text-lg'></i> Einlösen
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="mt-5 rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-receipt'></i></div>
            <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Transaktionen</h2>
            <p class="mt-2 text-sm leading-6 text-slate-400">Alle Buchungen bleiben erhalten und speisen auch den Verlauf.</p>
        </div>
    </div>
</section>

    <div class="mt-6 space-y-3">
        <?php if (empty($transactions)): ?>
            <div class="rounded-[28px] border border-dashed border-white/10 bg-white/5 p-5 text-sm text-slate-400">Noch keine Transaktionen vorhanden.</div>
        <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
                <?php $type = (string) ($transaction['type'] ?? 'credit'); ?>
                <div class="rounded-[28px] border border-white/10 bg-white/5 p-4">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl <?= $type === 'credit' ? 'bg-emerald-500 text-slate-950' : 'bg-rose-400 text-slate-950' ?> text-2xl">
                                <i class='bx <?= $type === 'credit' ? 'bx-trending-up' : 'bx-trending-down' ?>'></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white"><?= e((string) $transaction['title']) ?></div>
                                <div class="text-xs text-slate-400"><?= e((string) ($transaction['note'] ?? 'Ohne Notiz')) ?></div>
                            </div>
                        </div>
                        <div class="grid gap-2 text-sm text-slate-300 sm:grid-cols-3 sm:items-center sm:gap-5 lg:min-w-[420px] lg:text-right">
                            <div><?= e(dashboardDate((string) $transaction['created_at'])) ?></div>
                            <div class="font-bold <?= $type === 'credit' ? 'text-emerald-300' : 'text-rose-300' ?>"><?= $type === 'credit' ? '+' : '-' ?><?= (int) $transaction['points'] ?> HP</div>
                            <div class="font-bold text-white">Kontostand: <?= hpFormat((int) $transaction['balance_after']) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<script>
    const balanceChartCanvas = document.getElementById('balanceChart');
    if (balanceChartCanvas) {
        new Chart(balanceChartCanvas, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartLabels, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                datasets: [{
                    label: 'HP Verlauf',
                    data: <?= json_encode($chartValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                    borderColor: '<?= e((string) ($user['accent_color'] ?? '#7c9cff')) ?>',
                    backgroundColor: 'rgba(124, 156, 255, 0.16)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    y: {
                        ticks: { color: '#94a3b8' },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    }
                }
            }
        });
    }
</script>

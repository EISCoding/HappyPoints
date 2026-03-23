<?php
declare(strict_types=1);

$balance = (int) ($user['balance'] ?? 0);
$username = (string) ($user['username'] ?? 'Benutzer');
$displayName = (string) (($user['display_name'] ?? '') !== '' ? $user['display_name'] : $username);
$headline = (string) (($user['headline'] ?? '') !== '' ? $user['headline'] : 'Gemeinsam Ziele erreichen und Punkte sammeln.');
$avatarIcon = (string) ($user['avatar_icon'] ?? 'bx-happy-heart-eyes');
$weeklyGoal = max(1, (int) ($user['weekly_goal'] ?? 50));
$partnerName = $partner ? (string) (($partner['display_name'] ?? '') !== '' ? $partner['display_name'] : ($partner['username'] ?? 'Partner')) : null;
$progressPercent = min(100, (int) round(($balance / $weeklyGoal) * 100));
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

<section class="grid gap-4 sm:gap-5 xl:grid-cols-[1.05fr_0.95fr]">
    <div class="rounded-[32px] border border-white/10 bg-gradient-to-br from-indigo-950/80 via-slate-900/90 to-slate-950 p-5 shadow-glow sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.24em] text-slate-300">
                    <i class='bx <?= e($avatarIcon) ?> text-brand text-base'></i>
                    Dashboard
                </div>
                <h1 class="mt-5 text-4xl font-black tracking-tight text-white sm:text-5xl"><?= hpFormat($balance) ?></h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base"><?= e($headline) ?></p>
            </div>
            <div class="hidden h-16 w-16 items-center justify-center rounded-[24px] bg-white/10 text-3xl text-white sm:flex"><i class='bx <?= e($avatarIcon) ?>'></i></div>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Fortschritt</div>
                <div class="mt-2 text-2xl font-black text-white"><?= $progressPercent ?>%</div>
                <div class="mt-3 h-3 rounded-full bg-white/10"><div class="h-3 rounded-full bg-gradient-to-r from-brand to-pinksoft" style="width: <?= $progressPercent ?>%"></div></div>
                <div class="mt-2 text-xs text-slate-400">Wochenziel: <?= $weeklyGoal ?> HP</div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Partner</div>
                <div class="mt-2 text-xl font-black text-white"><?= e($partnerName ?? 'Nicht verbunden') ?></div>
                <div class="mt-2 text-xs text-slate-400"><?= $partner ? 'To-dos und Coupons werden für euren Partner angelegt.' : 'Verbinde einen Partner, um Aufgaben und Belohnungen zu teilen.' ?></div>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="#buchung" class="inline-flex items-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card"><i class='bx bx-wallet text-lg'></i> Punkte anpassen</a>
            <a href="#partner-tools" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-100"><i class='bx bx-heart text-lg'></i> Partner-Tools</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-[28px] border border-white/10 bg-slate-900/80 p-5 shadow-card"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Benutzer</div><div class="mt-3 text-2xl font-black text-white"><?= e($displayName) ?></div><div class="mt-1 text-sm text-slate-400">@<?= e($username) ?></div></div>
        <div class="rounded-[28px] border border-white/10 bg-slate-900/80 p-5 shadow-card"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Transaktionen</div><div class="mt-3 text-2xl font-black text-white"><?= count($transactions) ?></div><div class="mt-1 text-sm text-slate-400">Letzte Kontoeinträge</div></div>
        <div class="rounded-[28px] border border-white/10 bg-slate-900/80 p-5 shadow-card"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Erledigte Partner-To-dos</div><div class="mt-3 text-2xl font-black text-emerald-300"><?= $completedTodos ?></div><div class="mt-1 text-sm text-slate-400">Offen: <?= $openTodos ?></div></div>
        <div class="rounded-[28px] border border-white/10 bg-slate-900/80 p-5 shadow-card"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Partner-Coupons</div><div class="mt-3 text-2xl font-black text-amber-300"><?= $availableCoupons ?></div><div class="mt-1 text-sm text-slate-400">Eingelöst: <?= $redeemedCoupons ?></div></div>
    </div>
</section>

<section class="mt-4 grid gap-4 sm:mt-5 sm:gap-5 xl:grid-cols-[0.95fr_1.05fr]">
    <div id="buchung" class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-wallet'></i></div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Kontostand anpassen</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Manuelle Buchung für dein Konto.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 px-4 py-3 text-right"><div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Aktuell</div><div class="mt-1 text-xl font-black text-white"><?= hpFormat($balance) ?></div></div>
        </div>

        <form method="post" action="/dashboard/balance" class="mt-7 space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <select id="mode" name="mode" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand"><option value="plus" <?= old('mode', 'plus') === 'plus' ? 'selected' : '' ?>>Punkte hinzufügen</option><option value="minus" <?= old('mode') === 'minus' ? 'selected' : '' ?>>Punkte abziehen</option></select>
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" id="amount" name="amount" type="number" min="1" value="<?= e((string) old('amount', '5')) ?>" required>
            </div>
            <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" id="title" name="title" type="text" value="<?= e((string) old('title', 'Manuelle Buchung')) ?>" required>
            <textarea id="note" name="note" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" placeholder="Kurze Notiz zur Buchung"><?= e((string) old('note')) ?></textarea>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card" type="submit"><i class='bx bx-save text-lg'></i> Buchung speichern</button>
        </form>
    </div>

    <div class="grid gap-4">
        <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/15 text-2xl text-emerald-300"><i class='bx bx-line-chart'></i></div>
                    <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Verlauf</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">Chart.js Übersicht über deinen HP-Verlauf.</p>
                </div>
                <div class="text-right text-xs text-slate-400"><div>+ <?= $creditCount ?></div><div>- <?= $debitCount ?></div></div>
            </div>
            <div class="mt-5 rounded-[26px] border border-white/10 bg-slate-950/70 p-3 sm:p-4"><div class="h-[230px] sm:h-[260px]"><canvas id="balanceChart"></canvas></div></div>
        </div>
        <div class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Konto-Snapshot</div>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><div class="text-xs uppercase tracking-[0.2em] text-slate-400">HP</div><div class="mt-2 text-xl font-black text-white"><?= hpFormat($balance) ?></div></div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><div class="text-xs uppercase tracking-[0.2em] text-slate-400">Wochenziel</div><div class="mt-2 text-xl font-black text-white"><?= $weeklyGoal ?> HP</div></div>
            </div>
        </div>
    </div>
</section>

<section id="partner-tools" class="mt-4 grid gap-4 sm:mt-5 sm:gap-5 xl:grid-cols-2">
    <div id="todos" class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/15 text-2xl text-emerald-300"><i class='bx bx-task'></i></div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Partner-To-dos</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Du siehst To-dos, die dir dein Partner erstellt hat. Neue To-dos legst du für deinen Partner an.</p>
            </div>
        </div>

        <?php if ($partner): ?>
            <form method="post" action="/dashboard/todos" class="mt-7 space-y-4 rounded-[26px] border border-white/10 bg-slate-950/60 p-4">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="title" type="text" value="<?= e((string) old('todo_title')) ?>" placeholder="Neues To-do für <?= e($partnerName ?? 'deinen Partner') ?>" required>
                <div class="grid gap-4 sm:grid-cols-[1fr_120px]">
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="details" type="text" value="<?= e((string) old('todo_details')) ?>" placeholder="Details">
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="points_reward" type="number" min="1" value="<?= e((string) old('todo_points_reward', '10')) ?>" required>
                </div>
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-bold text-slate-950 shadow-card" type="submit"><i class='bx bx-plus text-lg'></i> Für Partner speichern</button>
            </form>
        <?php else: ?>
            <div class="mt-6 rounded-[26px] border border-dashed border-white/10 bg-white/5 p-4 text-sm text-slate-400">Verbinde zuerst einen Partner, bevor du gemeinsame To-dos anlegst.</div>
        <?php endif; ?>

        <div class="mt-6 space-y-3">
            <?php if (empty($todos)): ?>
                <div class="rounded-[26px] border border-dashed border-white/10 bg-white/5 p-4 text-sm text-slate-400">Aktuell keine offenen oder erledigten Partner-To-dos für dich.</div>
            <?php else: ?>
                <?php foreach ($todos as $todo): ?>
                    <?php $isCompleted = (int) ($todo['is_completed'] ?? 0) === 1; $creatorName = (string) (($todo['created_by_display_name'] ?? '') !== '' ? $todo['created_by_display_name'] : ($todo['created_by_username'] ?? 'Partner')); ?>
                    <div class="rounded-[26px] border border-white/10 <?= $isCompleted ? 'bg-emerald-500/10' : 'bg-white/5' ?> p-4">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-base font-bold text-white"><?= e((string) $todo['title']) ?></div>
                                    <div class="mt-1 text-xs text-slate-400">Von <?= e($creatorName) ?> · <?= e((string) ($todo['details'] ?? 'Ohne Zusatzinfo')) ?></div>
                                </div>
                                <div class="rounded-2xl bg-slate-950/70 px-3 py-2 text-sm font-bold text-white">+<?= (int) $todo['points_reward'] ?> HP</div>
                            </div>
                            <form method="post" action="/dashboard/todos/toggle">
                                <input type="hidden" name="todo_id" value="<?= (int) $todo['id'] ?>">
                                <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-bold <?= $isCompleted ? 'bg-rose-400 text-slate-950' : 'bg-emerald-500 text-slate-950' ?>" type="submit"><i class='bx <?= $isCompleted ? 'bx-undo' : 'bx-check' ?> text-lg'></i><?= $isCompleted ? 'Rückgängig' : 'Erledigt' ?></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="coupons" class="rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amberish/15 text-2xl text-amberish"><i class='bx bx-gift'></i></div>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Partner-Coupons</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Du siehst Belohnungen, die dir dein Partner erstellt hat. Eigene Coupons gibt es nicht mehr.</p>
            </div>
        </div>

        <?php if ($partner): ?>
            <form method="post" action="/dashboard/coupons" class="mt-7 space-y-4 rounded-[26px] border border-white/10 bg-slate-950/60 p-4">
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="title" type="text" value="<?= e((string) old('coupon_title')) ?>" placeholder="Neuer Coupon für <?= e($partnerName ?? 'deinen Partner') ?>" required>
                <div class="grid gap-4 sm:grid-cols-[1fr_120px]">
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="description" type="text" value="<?= e((string) old('coupon_description')) ?>" placeholder="Beschreibung">
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-brand" name="cost" type="number" min="1" value="<?= e((string) old('coupon_cost', '20')) ?>" required>
                </div>
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-amberish px-4 py-3 text-sm font-bold text-slate-950 shadow-card" type="submit"><i class='bx bx-plus text-lg'></i> Für Partner speichern</button>
            </form>
        <?php else: ?>
            <div class="mt-6 rounded-[26px] border border-dashed border-white/10 bg-white/5 p-4 text-sm text-slate-400">Verbinde zuerst einen Partner, bevor du gemeinsame Coupons anlegst.</div>
        <?php endif; ?>

        <div class="mt-6 space-y-3">
            <?php if (empty($coupons)): ?>
                <div class="rounded-[26px] border border-dashed border-white/10 bg-white/5 p-4 text-sm text-slate-400">Aktuell keine Partner-Coupons für dich.</div>
            <?php else: ?>
                <?php foreach ($coupons as $coupon): ?>
                    <?php $isRedeemed = (int) ($coupon['is_redeemed'] ?? 0) === 1; $creatorName = (string) (($coupon['created_by_display_name'] ?? '') !== '' ? $coupon['created_by_display_name'] : ($coupon['created_by_username'] ?? 'Partner')); ?>
                    <div class="rounded-[26px] border border-white/10 <?= $isRedeemed ? 'bg-rose-500/10' : 'bg-white/5' ?> p-4">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-base font-bold text-white"><?= e((string) $coupon['title']) ?></div>
                                    <div class="mt-1 text-xs text-slate-400">Von <?= e($creatorName) ?> · <?= e((string) ($coupon['description'] ?? 'Ohne Beschreibung')) ?></div>
                                </div>
                                <div class="rounded-2xl bg-slate-950/70 px-3 py-2 text-sm font-bold text-white">-<?= (int) $coupon['cost'] ?> HP</div>
                            </div>
                            <?php if ($isRedeemed): ?>
                                <div class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-rose-400 px-4 py-3 text-sm font-bold text-slate-950"><i class='bx bx-lock-alt text-lg'></i>Eingelöst</div>
                            <?php else: ?>
                                <form method="post" action="/dashboard/coupons/redeem"><input type="hidden" name="coupon_id" value="<?= (int) $coupon['id'] ?>"><button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-amberish px-4 py-3 text-sm font-bold text-slate-950" type="submit"><i class='bx bx-cart-download text-lg'></i> Einlösen</button></form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="mt-4 rounded-[30px] border border-white/10 bg-slate-900/80 p-5 shadow-card sm:mt-5 sm:p-7">
    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-receipt'></i></div>
    <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Transaktionen</h2>
    <div class="mt-6 space-y-3">
        <?php if (empty($transactions)): ?>
            <div class="rounded-[26px] border border-dashed border-white/10 bg-white/5 p-4 text-sm text-slate-400">Noch keine Transaktionen vorhanden.</div>
        <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
                <?php $type = (string) ($transaction['type'] ?? 'credit'); ?>
                <div class="rounded-[26px] border border-white/10 bg-white/5 p-4">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl <?= $type === 'credit' ? 'bg-emerald-500 text-slate-950' : 'bg-rose-400 text-slate-950' ?> text-xl"><i class='bx <?= $type === 'credit' ? 'bx-trending-up' : 'bx-trending-down' ?>'></i></div>
                            <div><div class="text-sm font-bold text-white"><?= e((string) $transaction['title']) ?></div><div class="text-xs text-slate-400"><?= e((string) ($transaction['note'] ?? 'Ohne Notiz')) ?></div></div>
                        </div>
                        <div class="grid gap-1 text-sm text-slate-300 lg:min-w-[360px] lg:grid-cols-3 lg:text-right">
                            <div><?= e(dashboardDate((string) $transaction['created_at'])) ?></div>
                            <div class="font-bold <?= $type === 'credit' ? 'text-emerald-300' : 'text-rose-300' ?>"><?= $type === 'credit' ? '+' : '-' ?><?= (int) $transaction['points'] ?> HP</div>
                            <div class="font-bold text-white"><?= hpFormat((int) $transaction['balance_after']) ?></div>
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
                    data: <?= json_encode($chartValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                    borderColor: '<?= e((string) ($user['accent_color'] ?? '#7c9cff')) ?>',
                    backgroundColor: 'rgba(124, 156, 255, 0.18)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148, 163, 184, 0.08)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148, 163, 184, 0.08)' } }
                }
            }
        });
    }
</script>

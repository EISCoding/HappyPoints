<?php
declare(strict_types=1);

$displayName = (string) (($user['display_name'] ?? '') !== '' ? $user['display_name'] : $user['username']);
?>

<section class="mt-5 grid gap-5 xl:grid-cols-[0.95fr_1.05fr]">
    <div class="rounded-[34px] border border-white/10 bg-gradient-to-br from-slate-800/90 via-slate-900/90 to-slate-950 p-6 shadow-glow sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.25em] text-slate-300">
            <i class='bx bx-link-alt text-pinksoft text-base'></i>
            Partnerbereich
        </div>
        <h1 class="mt-5 text-4xl font-black tracking-tight text-white sm:text-5xl"><?= e((string) $user['partner_code']) ?></h1>
        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300">Teile diesen Code mit <?= e($displayName) ?>s Partner, um euch miteinander zu verbinden.</p>

        <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Code</div>
                <div class="mt-2 text-xl font-black text-white"><?= e((string) $user['partner_code']) ?></div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Kontostand</div>
                <div class="mt-2 text-xl font-black text-white"><?= (int) $user['balance'] ?> HP</div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Status</div>
                <div class="mt-2 text-xl font-black text-white"><?= $partner ? 'Verbunden' : 'Offen' ?></div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Partner</div>
                <div class="mt-2 text-xl font-black text-white"><?= $partner ? e((string) (($partner['display_name'] ?? '') !== '' ? $partner['display_name'] : $partner['username'])) : '—' ?></div>
            </div>
        </div>
    </div>

    <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-pinksoft/15 text-2xl text-pinksoft"><i class='bx bx-transfer-alt'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Verbindungsstatus</h2>
        <?php if ($partner): ?>
            <div class="mt-6 rounded-[28px] border border-white/10 bg-white/5 p-5">
                <div class="text-sm text-slate-400">Verbunden mit</div>
                <div class="mt-2 text-2xl font-black text-white"><?= e((string) (($partner['display_name'] ?? '') !== '' ? $partner['display_name'] : $partner['username'])) ?></div>
                <div class="mt-1 text-sm text-slate-400"><?= e((string) $partner['email']) ?></div>
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
                <form method="post" action="/partner/disconnect" onsubmit="return confirm('Partnerverbindung wirklich trennen?');" class="mt-5">
                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-rose-400 px-4 py-3 text-sm font-bold text-slate-950 shadow-card transition hover:-translate-y-0.5" type="submit">
                        <i class='bx bx-unlink text-lg'></i> Verbindung trennen
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="mt-6 rounded-[28px] border border-dashed border-white/10 bg-white/5 p-5 text-sm text-slate-400">Aktuell ist kein Partner verbunden.</div>
        <?php endif; ?>
    </div>
</section>

<?php if (!$partner): ?>
    <section class="mt-5 rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand/15 text-2xl text-brand"><i class='bx bx-link'></i></div>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Partner verbinden</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Gib hier den Partnercode der anderen Person ein.</p>

        <form method="post" action="/partner/connect" class="mt-8 grid gap-4 sm:grid-cols-[1fr_auto]">
            <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="partner_code" name="partner_code" type="text" value="<?= e((string) old('partner_code')) ?>" placeholder="z. B. A7K9P2QX" required>
            <button class="inline-flex items-center justify-center gap-2 rounded-2xl bg-brand px-5 py-3 text-sm font-bold text-white shadow-card transition hover:-translate-y-0.5" type="submit">
                <i class='bx bx-check-circle text-lg'></i> Partner verbinden
            </button>
        </form>
    </section>
<?php endif; ?>

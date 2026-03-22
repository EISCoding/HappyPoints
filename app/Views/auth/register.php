<?php
declare(strict_types=1);
?>

<section class="mt-5 grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
    <div class="rounded-[32px] border border-white/10 bg-gradient-to-br from-slate-800/90 via-slate-900/90 to-slate-950/90 p-6 shadow-glow sm:p-8 lg:p-10">
        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.25em] text-slate-300">
            <i class='bx bx-star text-brand text-base'></i>
            Neues Konto
        </div>
        <h1 class="mt-5 max-w-xl text-4xl font-black tracking-tight text-white sm:text-5xl">Registriere euer Happypoints Konto im neuen Tailwind-Design.</h1>
        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
            Nach der Registrierung kannst du Profilfarben anpassen, Aufgaben definieren, Coupons anlegen
            und euren Partner per Code verbinden.
        </p>
        <div class="mt-8 grid gap-3 sm:grid-cols-3">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">TailwindCSS Layout</div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Boxicons überall</div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Persistente Daten</div>
        </div>
    </div>

    <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6 shadow-card sm:p-8">
        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-pinksoft to-brand text-3xl text-white shadow-card">
            <i class='bx bx-user-plus'></i>
        </div>
        <h2 class="mt-6 text-3xl font-black tracking-tight text-white">Registrierung</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Lege Benutzername, E-Mail-Adresse und Passwort fest.</p>

        <form method="post" action="/register" class="mt-8 space-y-5">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="email">E-Mail</label>
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="email" name="email" type="email" value="<?= e((string) old('email')) ?>" required>
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="username">Benutzername</label>
                <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="username" name="username" type="text" value="<?= e((string) old('username')) ?>" required>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="password">Passwort</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="password" name="password" type="password" required>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400" for="password_confirmation">Wiederholen</label>
                    <input class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none transition focus:border-brand" id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-card transition hover:-translate-y-0.5" type="submit">
                <i class='bx bx-check-circle text-lg'></i>
                Registrieren
            </button>
            <a href="/login" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-100 transition hover:-translate-y-0.5 hover:bg-white/10">
                <i class='bx bx-arrow-back text-lg'></i>
                Zum Login
            </a>
        </form>
    </div>
</section>

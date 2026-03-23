<?php
declare(strict_types=1);
?>

<section class="w-full rounded-[32px] border border-white/10 bg-slate-900/85 p-6 shadow-glow sm:p-8">
    <div class="mb-8 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[26px] bg-gradient-to-br from-pinksoft to-brand text-3xl text-white shadow-card"><i class='bx bx-user-plus'></i></div>
        <h1 class="mt-5 text-3xl font-black tracking-tight text-white">Registrierung</h1>
        <p class="mt-2 text-sm leading-6 text-slate-400">Lege dein Konto an. Danach bestätigst du deine E-Mail per SMTP-Link.</p>
    </div>

    <form method="post" action="/register" class="space-y-5">
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
            <i class='bx bx-check-circle text-lg'></i> Registrieren
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-400">
        Bereits registriert?
        <a href="/login" class="font-bold text-white underline decoration-brand/60 underline-offset-4">Zum Login</a>
    </div>
</section>

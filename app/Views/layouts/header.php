<?php
declare(strict_types=1);

$message = getFlashMessage();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$displayUser = $user ?? null;
$displayName = (string) (($displayUser['display_name'] ?? '') !== '' ? $displayUser['display_name'] : ($displayUser['username'] ?? 'HappyPoints'));
$accentColor = (string) ($displayUser['accent_color'] ?? '#7c9cff');

function navIsActive(string $path, string $currentPath): bool
{
    return $path === $currentPath;
}

function navItem(string $path, string $label, string $icon, string $currentPath): string
{
    $isActive = navIsActive($path, $currentPath);
    $classes = $isActive
        ? 'border-white/15 bg-white text-slate-950 shadow-card'
        : 'border-white/10 bg-white/5 text-slate-200 hover:border-white/20 hover:bg-white/10';

    return '<a href="' . e($path) . '" class="flex items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-semibold transition ' . $classes . '"><i class="bx ' . e($icon) . ' text-xl"></i><span>' . e($label) . '</span></a>';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b1120">
    <title><?= e(appName()) ?></title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        midnight: '#0b1120',
                        panel: '#121a2b',
                        panelSoft: '#18233a',
                        panelMute: '#22314f',
                        line: 'rgba(148, 163, 184, 0.18)',
                        brand: '<?= e($accentColor) ?>',
                        brandSoft: '#8aa4ff',
                        pinksoft: '#ff8cc6',
                        mint: '#55d6a1',
                        amberish: '#f7bf66',
                        roseish: '#f2889b'
                    },
                    boxShadow: {
                        glow: '0 20px 60px rgba(15, 23, 42, 0.45)',
                        card: '0 18px 45px rgba(2, 6, 23, 0.38)'
                    },
                    borderRadius: {
                        '4xl': '2rem',
                        '5xl': '2.5rem'
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,.28); border-radius: 999px; }
        ::-webkit-scrollbar-track { background: rgba(15,23,42,.15); }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased" style="background-image: radial-gradient(circle at top left, rgba(124,156,255,0.22), transparent 24%), radial-gradient(circle at top right, rgba(255,140,198,0.12), transparent 24%), linear-gradient(180deg, #111827 0%, #0f172a 45%, #020617 100%);">
<div class="mx-auto w-full max-w-[1500px] px-3 pb-28 pt-3 sm:px-5 lg:px-6">
    <header class="sticky top-3 z-40 overflow-hidden rounded-[28px] border border-white/10 bg-slate-950/70 shadow-glow backdrop-blur-xl">
        <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-pinksoft to-brand text-3xl text-white shadow-card">
                    <i class='bx bxs-heart-circle'></i>
                </div>
                <div class="min-w-0">
                    <div class="truncate text-base font-black tracking-tight sm:text-lg"><?= e(appName()) ?></div>
                    <div class="truncate text-sm text-slate-400">
                        <?= Auth::check() ? 'Hallo, ' . e($displayName) . ' – alles in einer modernen Tailwind-Oberfläche.' : 'Punkte, Belohnungen und gemeinsame Ziele im neuen Look.' ?>
                    </div>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-2">
                <?php if (Auth::check()): ?>
                    <a href="/dashboard" class="inline-flex h-11 items-center gap-2 rounded-2xl border px-4 text-sm font-semibold transition <?= navIsActive('/dashboard', $currentPath) ? 'border-brand/70 bg-brand text-white shadow-card' : 'border-white/10 bg-white/5 text-slate-200 hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/10' ?>">
                        <i class='bx bx-home-heart text-lg'></i> Dashboard
                    </a>
                    <a href="/partner" class="inline-flex h-11 items-center gap-2 rounded-2xl border px-4 text-sm font-semibold transition <?= navIsActive('/partner', $currentPath) ? 'border-brand/70 bg-brand text-white shadow-card' : 'border-white/10 bg-white/5 text-slate-200 hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/10' ?>">
                        <i class='bx bx-link-alt text-lg'></i> Partner
                    </a>
                    <a href="/account" class="inline-flex h-11 items-center gap-2 rounded-2xl border px-4 text-sm font-semibold transition <?= navIsActive('/account', $currentPath) ? 'border-brand/70 bg-brand text-white shadow-card' : 'border-white/10 bg-white/5 text-slate-200 hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/10' ?>">
                        <i class='bx bx-user text-lg'></i> Profil
                    </a>
                    <a href="/logout" class="inline-flex h-11 items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 text-sm font-semibold text-slate-200 transition hover:-translate-y-0.5 hover:border-rose-300/30 hover:bg-rose-300/10 hover:text-white">
                        <i class='bx bx-log-out text-lg'></i> Logout
                    </a>
                <?php else: ?>
                    <a href="/login" class="inline-flex h-11 items-center gap-2 rounded-2xl border px-4 text-sm font-semibold transition <?= navIsActive('/login', $currentPath) ? 'border-brand/70 bg-brand text-white shadow-card' : 'border-white/10 bg-white/5 text-slate-200 hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/10' ?>">
                        <i class='bx bx-log-in text-lg'></i> Login
                    </a>
                    <a href="/register" class="inline-flex h-11 items-center gap-2 rounded-2xl border px-4 text-sm font-semibold transition <?= navIsActive('/register', $currentPath) ? 'border-brand/70 bg-brand text-white shadow-card' : 'border-white/10 bg-white/5 text-slate-200 hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/10' ?>">
                        <i class='bx bx-user-plus text-lg'></i> Registrieren
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <?php if ($message): ?>
        <?php $tone = $message['type'] === 'success' ? 'border-emerald-400/35 bg-emerald-500/15 text-emerald-100' : 'border-rose-400/35 bg-rose-500/15 text-rose-100'; ?>
        <div class="flash mt-4 rounded-[26px] border px-4 py-4 shadow-card <?= $tone ?>">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-2xl"><i class='bx <?= $message['type'] === 'success' ? 'bx-check-circle' : 'bx-error-circle' ?>'></i></div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-white/70"><?= $message['type'] === 'success' ? 'Erfolg' : 'Hinweis' ?></div>
                    <p class="mt-1 text-sm sm:text-base"><?= e((string) $message['text']) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

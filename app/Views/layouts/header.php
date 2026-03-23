<?php
declare(strict_types=1);

$message = getFlashMessage();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$displayUser = $user ?? null;
$displayName = (string) (($displayUser['display_name'] ?? '') !== '' ? $displayUser['display_name'] : ($displayUser['username'] ?? 'HappyPoints'));
$accentColor = (string) ($displayUser['accent_color'] ?? '#7c9cff');
$isAuthenticated = Auth::check();

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
                        brand: '<?= e($accentColor) ?>',
                        pinksoft: '#ff8cc6',
                        amberish: '#f7bf66',
                        roseish: '#f2889b'
                    },
                    boxShadow: {
                        glow: '0 24px 64px rgba(15, 23, 42, 0.42)',
                        card: '0 16px 36px rgba(2, 6, 23, 0.34)'
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,.28); border-radius: 999px; }
        ::-webkit-scrollbar-track { background: rgba(15,23,42,.14); }
        summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased" style="background-image: radial-gradient(circle at top left, rgba(124,156,255,0.18), transparent 24%), radial-gradient(circle at top right, rgba(255,140,198,0.10), transparent 20%), linear-gradient(180deg, #111827 0%, #0f172a 48%, #020617 100%);">
<?php if ($isAuthenticated): ?>
    <aside class="fixed inset-y-0 left-0 z-30 hidden w-72 border-r border-white/10 bg-slate-950/90 px-5 py-6 backdrop-blur-xl lg:flex lg:flex-col">
        <div class="flex items-center gap-3 px-1">
            <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-pinksoft to-brand text-3xl text-white shadow-card"><i class='bx bxs-heart-circle'></i></div>
            <div>
                <div class="text-base font-black tracking-tight text-white"><?= e(appName()) ?></div>
                <div class="text-sm text-slate-400"><?= e($displayName) ?></div>
            </div>
        </div>
        <div class="mt-8 space-y-3">
            <?= navItem('/dashboard', 'Dashboard', 'bx-home-heart', $currentPath) ?>
            <?= navItem('/partner', 'Partner', 'bx-link-alt', $currentPath) ?>
            <?= navItem('/account', 'Konto', 'bx-user', $currentPath) ?>
        </div>
        <div class="mt-auto rounded-[28px] border border-white/10 bg-white/5 p-4">
            <div class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Navigation</div>
            <p class="mt-3 text-sm leading-6 text-slate-300">Ohne obere Navbar, optimiert für Handy und Desktop mit Sidebar + verbesserter Bottom-Navigation.</p>
            <a href="/logout" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/15"><i class='bx bx-log-out text-lg'></i> Logout</a>
        </div>
    </aside>

    <details class="fixed left-3 top-3 z-40 lg:hidden">
        <summary class="flex h-12 w-12 cursor-pointer items-center justify-center rounded-2xl border border-white/10 bg-slate-950/85 text-2xl text-white shadow-card backdrop-blur-xl"><i class='bx bx-menu-alt-left'></i></summary>
        <div class="mt-3 w-[84vw] max-w-sm rounded-[30px] border border-white/10 bg-slate-950/95 p-4 shadow-glow backdrop-blur-xl">
            <div class="flex items-center gap-3 rounded-3xl border border-white/10 bg-white/5 p-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-pinksoft to-brand text-2xl text-white"><i class='bx bxs-heart-circle'></i></div>
                <div>
                    <div class="font-black text-white"><?= e(appName()) ?></div>
                    <div class="text-sm text-slate-400"><?= e($displayName) ?></div>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                <?= navItem('/dashboard', 'Dashboard', 'bx-home-heart', $currentPath) ?>
                <?= navItem('/partner', 'Partner', 'bx-link-alt', $currentPath) ?>
                <?= navItem('/account', 'Konto', 'bx-user', $currentPath) ?>
                <a href="/logout" class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10"><i class='bx bx-log-out text-xl'></i><span>Logout</span></a>
            </div>
        </div>
    </details>

    <main class="min-h-screen px-3 pb-28 pt-16 sm:px-4 lg:ml-72 lg:px-6 lg:pt-6">
        <div class="mx-auto w-full max-w-[1320px]">
<?php else: ?>
    <main class="min-h-screen px-4 py-8 sm:px-6">
        <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-md items-center justify-center">
<?php endif; ?>

            <?php if ($message): ?>
                <?php $tone = $message['type'] === 'success' ? 'border-emerald-400/35 bg-emerald-500/15 text-emerald-100' : 'border-rose-400/35 bg-rose-500/15 text-rose-100'; ?>
                <div class="flash mb-4 rounded-[26px] border px-4 py-4 shadow-card <?= $tone ?>">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 text-2xl"><i class='bx <?= $message['type'] === 'success' ? 'bx-check-circle' : 'bx-error-circle' ?>'></i></div>
                        <div>
                            <div class="text-sm font-semibold uppercase tracking-[0.2em] text-white/70"><?= $message['type'] === 'success' ? 'Erfolg' : 'Hinweis' ?></div>
                            <p class="mt-1 text-sm sm:text-base"><?= e((string) $message['text']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

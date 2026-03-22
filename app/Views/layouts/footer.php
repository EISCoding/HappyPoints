<?php
declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
    <nav class="fixed inset-x-3 bottom-3 z-40 rounded-[28px] border border-white/10 bg-slate-950/85 shadow-glow backdrop-blur-xl md:hidden">
        <div class="grid grid-cols-4 gap-1 p-2 text-xs font-semibold text-slate-300">
            <?php if (Auth::check()): ?>
                <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/dashboard' ? 'bg-brand text-white' : 'bg-transparent' ?>" href="/dashboard">
                    <i class='bx bx-home-alt-2 text-[22px]'></i><span>Home</span>
                </a>
                <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/partner' ? 'bg-brand text-white' : 'bg-transparent' ?>" href="/partner">
                    <i class='bx bx-link-alt text-[22px]'></i><span>Partner</span>
                </a>
                <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/account' ? 'bg-brand text-white' : 'bg-transparent' ?>" href="/account">
                    <i class='bx bx-user text-[22px]'></i><span>Profil</span>
                </a>
                <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2" href="/logout">
                    <i class='bx bx-log-out text-[22px]'></i><span>Logout</span>
                </a>
            <?php else: ?>
                <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/login' ? 'bg-brand text-white' : 'bg-transparent' ?>" href="/login">
                    <i class='bx bx-log-in text-[22px]'></i><span>Login</span>
                </a>
                <a class="col-span-3 flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/register' ? 'bg-brand text-white' : 'bg-transparent' ?>" href="/register">
                    <i class='bx bx-user-plus text-[22px]'></i><span>Registrieren</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.setTimeout(() => {
        document.querySelectorAll('.flash').forEach((el) => {
            el.style.transition = 'opacity 300ms ease, transform 300ms ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            window.setTimeout(() => el.remove(), 300);
        });
    }, 3500);
</script>
</body>
</html>

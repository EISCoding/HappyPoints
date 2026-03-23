<?php
declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
        </div>
    </main>

<?php if (Auth::check()): ?>
    <nav class="fixed inset-x-3 bottom-3 z-40 rounded-[30px] border border-white/10 bg-slate-950/90 p-2 shadow-glow backdrop-blur-xl lg:hidden">
        <div class="grid grid-cols-4 gap-2 text-[11px] font-semibold text-slate-300">
            <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/dashboard' ? 'bg-brand text-white shadow-card' : 'bg-white/5' ?>" href="/dashboard"><i class='bx bx-home-heart text-[22px]'></i><span>Home</span></a>
            <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/partner' ? 'bg-brand text-white shadow-card' : 'bg-white/5' ?>" href="/partner"><i class='bx bx-link-alt text-[22px]'></i><span>Partner</span></a>
            <a class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 <?= $currentPath === '/account' ? 'bg-brand text-white shadow-card' : 'bg-white/5' ?>" href="/account"><i class='bx bx-user text-[22px]'></i><span>Konto</span></a>
            <a class="flex flex-col items-center gap-1 rounded-2xl bg-white/5 px-2 py-2" href="/logout"><i class='bx bx-log-out text-[22px]'></i><span>Logout</span></a>
        </div>
    </nav>
<?php endif; ?>

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

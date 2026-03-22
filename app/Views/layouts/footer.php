<?php
declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
    <nav class="mobile-nav">
        <div class="mobile-nav-inner">
            <?php if (Auth::check()): ?>
                <a class="mobile-link <?= $currentPath === '/dashboard' ? 'active' : '' ?>" href="/dashboard">
                    <i class='bx bx-home-alt-2' style="font-size:22px;"></i>
                    <span>Home</span>
                </a>
                <a class="mobile-link <?= $currentPath === '/partner' ? 'active' : '' ?>" href="/partner">
                    <i class='bx bx-link-alt' style="font-size:22px;"></i>
                    <span>Partner</span>
                </a>
                <a class="mobile-link <?= $currentPath === '/account' ? 'active' : '' ?>" href="/account">
                    <i class='bx bx-user' style="font-size:22px;"></i>
                    <span>Konto</span>
                </a>
                <a class="mobile-link" href="/logout">
                    <i class='bx bx-log-out' style="font-size:22px;"></i>
                    <span>Logout</span>
                </a>
            <?php else: ?>
                <a class="mobile-link <?= $currentPath === '/login' ? 'active' : '' ?>" href="/login">
                    <i class='bx bx-log-in' style="font-size:22px;"></i>
                    <span>Login</span>
                </a>
                <a class="mobile-link <?= $currentPath === '/register' ? 'active' : '' ?>" href="/register">
                    <i class='bx bx-user-plus' style="font-size:22px;"></i>
                    <span>Registrieren</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</div>

<script>
    window.setTimeout(() => {
        document.querySelectorAll('.flash').forEach((el) => {
            el.style.transition = 'opacity 300ms ease, transform 300ms ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            setTimeout(() => el.remove(), 300);
        });
    }, 3500);
</script>
</body>
</html>
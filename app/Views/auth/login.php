<?php
declare(strict_types=1);
?>

<section class="hero-card" style="max-width:860px;margin-left:auto;margin-right:auto;">
    <div class="hero-grid" style="grid-template-columns:1fr 1fr;">
        <div>
            <div class="hero-chip">
                <i class='bx bx-heart-circle' style="font-size:16px;color:var(--pink);"></i>
                Happypoints Login
            </div>
            <h1 class="hero-title" style="font-size:clamp(2.2rem,4vw,3.4rem);">Willkommen zurück</h1>
            <p class="hero-copy">
                Melde dich mit deinem Happypoints-Konto an und verwalte dein Konto im modernen
                Design deiner bisherigen Seite.
            </p>
        </div>

        <div class="auth-wrap" style="max-width:none;margin:0;">
            <div class="auth-card">
                <div class="auth-logo">
                    <i class='bx bxs-heart'></i>
                </div>

                <h2 class="auth-title">Login</h2>
                <p class="auth-subtitle">Melde dich mit deiner E-Mail-Adresse und deinem Passwort an.</p>

                <form method="post" action="/login" class="stack" style="margin-top:22px;">
                    <div>
                        <label class="section-label" for="email">E-Mail</label>
                        <input class="input" id="email" name="email" type="email" value="<?= e((string) old('email')) ?>" required>
                    </div>

                    <div>
                        <label class="section-label" for="password">Passwort</label>
                        <input class="input" id="password" name="password" type="password" required>
                    </div>

                    <button class="button" type="submit">
                        <i class='bx bx-log-in-circle'></i>
                        Einloggen
                    </button>

                    <a href="/register" class="button-muted" style="width:100%;">
                        <i class='bx bx-user-plus'></i>
                        Neues Konto erstellen
                    </a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
declare(strict_types=1);
?>

<section class="hero-card" style="max-width:980px;margin-left:auto;margin-right:auto;">
    <div class="hero-grid" style="grid-template-columns:1fr 1fr;">
        <div>
            <div class="hero-chip">
                <i class='bx bx-star' style="font-size:16px;color:var(--accent);"></i>
                Happypoints Registrierung
            </div>
            <h1 class="hero-title" style="font-size:clamp(2.2rem,4vw,3.4rem);">Neues Konto erstellen</h1>
            <p class="hero-copy">
                Registriere dich für dein neues Happypoints-Konto und verbinde dich danach per Partnercode.
            </p>
        </div>

        <div class="auth-wrap" style="max-width:none;margin:0;">
            <div class="auth-card">
                <div class="auth-logo">
                    <i class='bx bx-user-plus'></i>
                </div>

                <h2 class="auth-title">Registrierung</h2>
                <p class="auth-subtitle">Lege Benutzername, E-Mail-Adresse und Passwort fest.</p>

                <form method="post" action="/register" class="stack" style="margin-top:22px;">
                    <div>
                        <label class="section-label" for="email">E-Mail</label>
                        <input class="input" id="email" name="email" type="email" value="<?= e((string) old('email')) ?>" required>
                    </div>

                    <div>
                        <label class="section-label" for="username">Benutzername</label>
                        <input class="input" id="username" name="username" type="text" value="<?= e((string) old('username')) ?>" required>
                    </div>

                    <div>
                        <label class="section-label" for="password">Passwort</label>
                        <input class="input" id="password" name="password" type="password" required>
                    </div>

                    <div>
                        <label class="section-label" for="password_confirmation">Passwort wiederholen</label>
                        <input class="input" id="password_confirmation" name="password_confirmation" type="password" required>
                    </div>

                    <button class="button" type="submit">
                        <i class='bx bx-check-circle'></i>
                        Registrieren
                    </button>

                    <a href="/login" class="button-muted" style="width:100%;">
                        <i class='bx bx-arrow-back'></i>
                        Zum Login
                    </a>
                </form>
            </div>
        </div>
    </div>
</section>
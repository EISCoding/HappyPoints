<div class="auth-wrap">
    <div class="card">
        <h1>Login</h1>
        <p class="muted">Melde dich mit deinem Happypoints-Konto an.</p>
        <form method="post" action="/login" class="stack">
            <div>
                <label for="email">E-Mail</label>
                <input class="input" id="email" name="email" type="email" value="<?= e((string) old('email')) ?>" required>
            </div>
            <div>
                <label for="password">Passwort</label>
                <input class="input" id="password" name="password" type="password" required>
            </div>
            <button class="button" type="submit">Einloggen</button>
        </form>
    </div>
</div>

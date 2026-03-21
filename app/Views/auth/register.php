<div class="auth-wrap">
    <div class="card">
        <h1>Registrierung</h1>
        <p class="muted">Erstelle dein Happypoints-Konto.</p>
        <form method="post" action="/register" class="stack">
            <div>
                <label for="email">E-Mail</label>
                <input class="input" id="email" name="email" type="email" value="<?= e((string) old('email')) ?>" required>
            </div>
            <div>
                <label for="username">Benutzername</label>
                <input class="input" id="username" name="username" type="text" value="<?= e((string) old('username')) ?>" required>
            </div>
            <div>
                <label for="password">Passwort</label>
                <input class="input" id="password" name="password" type="password" required>
            </div>
            <div>
                <label for="password_confirmation">Passwort wiederholen</label>
                <input class="input" id="password_confirmation" name="password_confirmation" type="password" required>
            </div>
            <button class="button" type="submit">Registrieren</button>
        </form>
    </div>
</div>

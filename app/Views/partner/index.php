<div class="card">
    <h1>Partner</h1>
    <p><strong>Dein Partnercode:</strong></p>
    <div class="big"><?= e((string) $user['partner_code']) ?></div>
    <p class="muted">Diesen Code kann dein Partner eingeben, um sich mit dir zu verbinden.</p>
</div>
<div class="card">
    <h2>Verbindungsstatus</h2>
    <?php if ($partner): ?>
        <p><strong>Verbunden mit:</strong> <?= e((string) $partner['username']) ?></p>
        <p><strong>E-Mail:</strong> <?= e((string) $partner['email']) ?></p>
        <p><strong>Kontostand:</strong> <?= (int) $partner['balance'] ?> HP</p>
        <form method="post" action="/partner/disconnect" onsubmit="return confirm('Partnerverbindung wirklich trennen?');">
            <button class="button danger" type="submit">Verbindung trennen</button>
        </form>
    <?php else: ?>
        <p class="muted">Aktuell ist kein Partner verbunden.</p>
    <?php endif; ?>
</div>
<?php if (!$partner): ?>
<div class="card">
    <h2>Partner verbinden</h2>
    <form method="post" action="/partner/connect" class="stack">
        <div>
            <label for="partner_code">Partnercode</label>
            <input class="input" id="partner_code" name="partner_code" type="text" value="<?= e((string) old('partner_code')) ?>" placeholder="z. B. A7K9P2QX" required>
        </div>
        <button class="button" type="submit">Partner verbinden</button>
    </form>
</div>
<?php endif; ?>

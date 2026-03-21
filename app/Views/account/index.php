<div class="card">
    <h1>Mein Konto</h1>
    <p><strong>Benutzername:</strong> <?= e((string) $user['username']) ?></p>
    <p><strong>E-Mail:</strong> <?= e((string) $user['email']) ?></p>
    <p><strong>Partnercode:</strong> <?= e((string) $user['partner_code']) ?></p>
    <p><strong>Kontostand:</strong> <?= (int) $user['balance'] ?> HP</p>
    <p><strong>Registriert am:</strong> <?= e((string) $user['created_at']) ?></p>
</div>
<div class="card">
    <h2>Partner</h2>
    <?php if ($partner): ?>
        <p><strong>Benutzername:</strong> <?= e((string) $partner['username']) ?></p>
        <p><strong>E-Mail:</strong> <?= e((string) $partner['email']) ?></p>
        <p><strong>Partnercode:</strong> <?= e((string) $partner['partner_code']) ?></p>
        <p><strong>Kontostand:</strong> <?= (int) $partner['balance'] ?> HP</p>
    <?php else: ?>
        <p class="muted">Aktuell ist kein Partner verbunden.</p>
    <?php endif; ?>
</div>

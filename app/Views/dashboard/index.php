<div class="card">
    <h1>Dashboard</h1>
    <p class="muted">Willkommen zurück, <?= e((string) $user['username']) ?>.</p>
    <div class="big"><?= (int) $user['balance'] ?> HP</div>
    <p class="muted">Aktueller Kontostand</p>
</div>

<div class="card">
    <h2>Konto anpassen</h2>
    <form method="post" action="/dashboard/balance" class="stack">
        <div class="row">
            <div>
                <label for="mode">Aktion</label>
                <select id="mode" name="mode">
                    <option value="plus" <?= old('mode', 'plus') === 'plus' ? 'selected' : '' ?>>Punkte hinzufügen</option>
                    <option value="minus" <?= old('mode') === 'minus' ? 'selected' : '' ?>>Punkte abziehen</option>
                </select>
            </div>
            <div>
                <label for="amount">Betrag</label>
                <input class="input" id="amount" name="amount" type="number" min="1" value="<?= e((string) old('amount', '5')) ?>" required>
            </div>
        </div>
        <div>
            <label for="title">Titel</label>
            <input class="input" id="title" name="title" type="text" value="<?= e((string) old('title', 'Manuelle Buchung')) ?>" required>
        </div>
        <div>
            <label for="note">Notiz</label>
            <input class="input" id="note" name="note" type="text" value="<?= e((string) old('note')) ?>">
        </div>
        <button class="button" type="submit">Speichern</button>
    </form>
</div>

<div class="card">
    <h2>Letzte Transaktionen</h2>
    <?php if (empty($transactions)): ?>
        <p class="muted">Noch keine Transaktionen vorhanden.</p>
    <?php else: ?>
        <table class="table">
            <thead>
            <tr>
                <th>Datum</th>
                <th>Titel</th>
                <th>Punkte</th>
                <th>Kontostand danach</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= e((string) $transaction['created_at']) ?></td>
                    <td><strong><?= e((string) $transaction['title']) ?></strong><br><span class="muted"><?= e((string) ($transaction['note'] ?? '')) ?></span></td>
                    <td><span class="badge <?= e((string) $transaction['type']) ?>"><?= $transaction['type'] === 'credit' ? '+' : '-' ?><?= (int) $transaction['points'] ?> HP</span></td>
                    <td><?= (int) $transaction['balance_after'] ?> HP</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

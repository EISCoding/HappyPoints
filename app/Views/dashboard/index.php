<?php
declare(strict_types=1);

$balance = (int) ($user['balance'] ?? 0);
$username = (string) ($user['username'] ?? 'Benutzer');
$transactionsCount = is_array($transactions) ? count($transactions) : 0;
$creditCount = 0;
$debitCount = 0;

foreach ($transactions as $transaction) {
    if (($transaction['type'] ?? '') === 'credit') {
        $creditCount++;
    } else {
        $debitCount++;
    }
}

function hpFormat(int $value): string
{
    return number_format($value, 0, ',', '.') . ' HP';
}

function txDate(string $value): string
{
    $time = strtotime($value);
    return $time ? date('d.m.Y H:i', $time) : $value;
}
?>

<section class="hero-card">
    <div class="hero-grid">
        <div>
            <div class="hero-chip">
                <i class='bx bx-sparkles' style="font-size:16px;color:var(--accent);"></i>
                Happypoints Dashboard
            </div>

            <h1 class="hero-title"><?= hpFormat($balance) ?></h1>

            <p class="hero-copy">
                Willkommen zurück, <?= e($username) ?>. Verwalte deinen Kontostand, buche Punkte
                manuell und behalte die letzten Transaktionen in einer Oberfläche im Stil deiner alten Happypoints-Seite im Blick.
            </p>

            <div class="hero-actions">
                <a href="#buchung" class="button">
                    <i class='bx bx-wallet'></i>
                    Punkte anpassen
                </a>
                <a href="#transaktionen" class="button-muted">
                    <i class='bx bx-line-chart'></i>
                    Verlauf ansehen
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="metric">
                <div class="metric-label">Benutzer</div>
                <div class="metric-value"><?= e($username) ?></div>
                <div class="metric-sub">Aktives Happypoints-Konto</div>
            </div>

            <div class="metric">
                <div class="metric-label">Kontostand</div>
                <div class="metric-value"><?= $balance ?></div>
                <div class="metric-sub">Verfügbare Punkte</div>
            </div>

            <div class="metric">
                <div class="metric-label">Gutschriften</div>
                <div class="metric-value"><?= $creditCount ?></div>
                <div class="metric-sub">Positive Buchungen</div>
            </div>

            <div class="metric">
                <div class="metric-label">Letzte Einträge</div>
                <div class="metric-value"><?= $transactionsCount ?></div>
                <div class="metric-sub">Im aktuellen Verlauf</div>
            </div>
        </div>
    </div>
</section>

<div class="page-grid grid-2">
    <section id="buchung" class="card">
        <div class="card-head">
            <div class="card-head-left">
                <div class="card-icon icon-accent">
                    <i class='bx bx-wallet'></i>
                </div>
                <div>
                    <h2 class="card-title">Punktestand anpassen</h2>
                    <div class="card-subtitle">Direkte Buchung auf dein Happypoints-Konto</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="balance-box">
                <div class="balance-label">Aktueller Kontostand</div>
                <div class="balance-value"><?= hpFormat($balance) ?></div>
            </div>

            <form method="post" action="/dashboard/balance" class="stack" style="margin-top:18px;">
                <div class="form-grid">
                    <div>
                        <label class="section-label" for="mode">Aktion</label>
                        <select id="mode" name="mode">
                            <option value="plus" <?= old('mode', 'plus') === 'plus' ? 'selected' : '' ?>>Punkte hinzufügen</option>
                            <option value="minus" <?= old('mode') === 'minus' ? 'selected' : '' ?>>Punkte abziehen</option>
                        </select>
                    </div>

                    <div>
                        <label class="section-label" for="amount">Betrag</label>
                        <input class="input" id="amount" name="amount" type="number" min="1" value="<?= e((string) old('amount', '5')) ?>" required>
                    </div>
                </div>

                <div>
                    <label class="section-label" for="title">Titel</label>
                    <input class="input" id="title" name="title" type="text" value="<?= e((string) old('title', 'Manuelle Buchung')) ?>" required>
                </div>

                <div>
                    <label class="section-label" for="note">Notiz</label>
                    <textarea id="note" name="note" placeholder="Kurze Notiz zur Buchung"><?= e((string) old('note')) ?></textarea>
                </div>

                <button class="button" type="submit">
                    <i class='bx bx-save'></i>
                    Buchung speichern
                </button>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <div class="card-head-left">
                <div class="card-icon icon-pink">
                    <i class='bx bx-info-circle'></i>
                </div>
                <div>
                    <h2 class="card-title">Schnellübersicht</h2>
                    <div class="card-subtitle">Die wichtigsten Informationen auf einen Blick</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="info-list">
                <div class="info-row">
                    <div class="info-key">Benutzername</div>
                    <div class="info-value"><?= e($username) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-key">Kontostand</div>
                    <div class="info-value"><?= hpFormat($balance) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-key">Letzte Buchungen</div>
                    <div class="info-value"><?= $transactionsCount ?> Einträge</div>
                </div>

                <div class="info-row">
                    <div class="info-key">Abzüge</div>
                    <div class="info-value"><?= $debitCount ?> Buchungen</div>
                </div>
            </div>
        </div>
    </section>
</div>

<section id="transaktionen" class="card" style="margin-top:20px;">
    <div class="card-head">
        <div class="card-head-left">
            <div class="card-icon icon-success">
                <i class='bx bx-receipt'></i>
            </div>
            <div>
                <h2 class="card-title">Letzte Transaktionen</h2>
                <div class="card-subtitle">Buchungen mit Datum, Typ und Kontostand danach</div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($transactions)): ?>
            <div class="card-soft" style="padding:18px;text-align:center;color:var(--text-soft);">
                Noch keine Transaktionen vorhanden.
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Titel</th>
                        <th>Notiz</th>
                        <th>Punkte</th>
                        <th>Kontostand</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <?php
                        $type = (string) ($transaction['type'] ?? 'credit');
                        $points = (int) ($transaction['points'] ?? 0);
                        $balanceAfter = (int) ($transaction['balance_after'] ?? 0);
                        ?>
                        <tr>
                            <td><?= e(txDate((string) $transaction['created_at'])) ?></td>
                            <td>
                                <strong><?= e((string) $transaction['title']) ?></strong>
                            </td>
                            <td class="muted"><?= e((string) ($transaction['note'] ?? '')) ?></td>
                            <td>
                                <span class="badge <?= e($type) ?>">
                                    <?= $type === 'credit' ? '+' : '-' ?><?= $points ?> HP
                                </span>
                            </td>
                            <td><strong style="color:var(--accent);"><?= hpFormat($balanceAfter) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
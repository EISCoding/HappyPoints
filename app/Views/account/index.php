<?php
declare(strict_types=1);

function accountDate(string $value): string
{
    $time = strtotime($value);
    return $time ? date('d.m.Y H:i', $time) : $value;
}
?>

<section class="hero-card">
    <div class="hero-grid">
        <div>
            <div class="hero-chip">
                <i class='bx bx-user-circle' style="font-size:16px;color:var(--accent);"></i>
                Mein Konto
            </div>

            <h1 class="hero-title" style="font-size:clamp(2.2rem,4vw,3.6rem);"><?= (int) $user['balance'] ?> HP</h1>

            <p class="hero-copy">
                Hier findest du alle wichtigen Informationen zu deinem Benutzerkonto,
                deinem Partnercode und dem aktuellen Kontostand.
            </p>
        </div>

        <div class="stats-grid">
            <div class="metric">
                <div class="metric-label">Benutzername</div>
                <div class="metric-value"><?= e((string) $user['username']) ?></div>
                <div class="metric-sub">Dein Anzeigename</div>
            </div>

            <div class="metric">
                <div class="metric-label">Partnercode</div>
                <div class="metric-value"><?= e((string) $user['partner_code']) ?></div>
                <div class="metric-sub">Zum Verbinden mit deinem Partner</div>
            </div>

            <div class="metric">
                <div class="metric-label">Kontostand</div>
                <div class="metric-value"><?= (int) $user['balance'] ?></div>
                <div class="metric-sub">Aktuelle Happypoints</div>
            </div>

            <div class="metric">
                <div class="metric-label">Registriert</div>
                <div class="metric-value" style="font-size:1.1rem;line-height:1.35;"><?= e(accountDate((string) $user['created_at'])) ?></div>
                <div class="metric-sub">Erstellungsdatum</div>
            </div>
        </div>
    </div>
</section>

<div class="page-grid grid-2">
    <section class="card">
        <div class="card-head">
            <div class="card-head-left">
                <div class="card-icon icon-accent">
                    <i class='bx bx-id-card'></i>
                </div>
                <div>
                    <h2 class="card-title">Meine Daten</h2>
                    <div class="card-subtitle">Basisinformationen deines Kontos</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="info-list">
                <div class="info-row">
                    <div class="info-key">Benutzername</div>
                    <div class="info-value"><?= e((string) $user['username']) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-key">E-Mail</div>
                    <div class="info-value"><?= e((string) $user['email']) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-key">Partnercode</div>
                    <div class="info-value"><?= e((string) $user['partner_code']) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-key">Kontostand</div>
                    <div class="info-value"><?= (int) $user['balance'] ?> HP</div>
                </div>

                <div class="info-row">
                    <div class="info-key">Registriert am</div>
                    <div class="info-value"><?= e(accountDate((string) $user['created_at'])) ?></div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <div class="card-head-left">
                <div class="card-icon icon-pink">
                    <i class='bx bx-link-alt'></i>
                </div>
                <div>
                    <h2 class="card-title">Partnerübersicht</h2>
                    <div class="card-subtitle">Verbundene Person und deren Kontoinformationen</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php if ($partner): ?>
                <div class="info-list">
                    <div class="info-row">
                        <div class="info-key">Benutzername</div>
                        <div class="info-value"><?= e((string) $partner['username']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-key">E-Mail</div>
                        <div class="info-value"><?= e((string) $partner['email']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-key">Partnercode</div>
                        <div class="info-value"><?= e((string) $partner['partner_code']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-key">Kontostand</div>
                        <div class="info-value"><?= (int) $partner['balance'] ?> HP</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card-soft" style="padding:18px;text-align:center;color:var(--text-soft);">
                    Aktuell ist kein Partner verbunden.
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
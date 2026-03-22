<?php
declare(strict_types=1);
?>

<section class="hero-card">
    <div class="hero-grid">
        <div>
            <div class="hero-chip">
                <i class='bx bx-link-alt' style="font-size:16px;color:var(--pink);"></i>
                Partnerbereich
            </div>

            <h1 class="hero-title" style="font-size:clamp(2.1rem,4vw,3.5rem);"><?= e((string) $user['partner_code']) ?></h1>

            <p class="hero-copy">
                Dies ist dein persönlicher Partnercode. Dein Partner kann ihn verwenden,
                um sich mit deinem Happypoints-Konto zu verbinden.
            </p>
        </div>

        <div class="stats-grid">
            <div class="metric">
                <div class="metric-label">Dein Code</div>
                <div class="metric-value"><?= e((string) $user['partner_code']) ?></div>
                <div class="metric-sub">Zum Teilen mit deinem Partner</div>
            </div>

            <div class="metric">
                <div class="metric-label">Kontostand</div>
                <div class="metric-value"><?= (int) $user['balance'] ?></div>
                <div class="metric-sub">Deine Happypoints</div>
            </div>

            <div class="metric">
                <div class="metric-label">Status</div>
                <div class="metric-value" style="font-size:1.2rem;line-height:1.35;"><?= $partner ? 'Verbunden' : 'Nicht verbunden' ?></div>
                <div class="metric-sub">Aktueller Verbindungsstatus</div>
            </div>

            <div class="metric">
                <div class="metric-label">Partner</div>
                <div class="metric-value" style="font-size:1.2rem;line-height:1.35;"><?= $partner ? e((string) $partner['username']) : '—' ?></div>
                <div class="metric-sub">Verknüpfte Person</div>
            </div>
        </div>
    </div>
</section>

<div class="page-grid grid-2">
    <section class="card">
        <div class="card-head">
            <div class="card-head-left">
                <div class="card-icon icon-pink">
                    <i class='bx bx-transfer-alt'></i>
                </div>
                <div>
                    <h2 class="card-title">Verbindungsstatus</h2>
                    <div class="card-subtitle">Aktuelle Partnerverbindung verwalten</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php if ($partner): ?>
                <div class="info-list">
                    <div class="info-row">
                        <div class="info-key">Verbunden mit</div>
                        <div class="info-value"><?= e((string) $partner['username']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-key">E-Mail</div>
                        <div class="info-value"><?= e((string) $partner['email']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-key">Kontostand</div>
                        <div class="info-value"><?= (int) $partner['balance'] ?> HP</div>
                    </div>
                </div>

                <form method="post" action="/partner/disconnect" onsubmit="return confirm('Partnerverbindung wirklich trennen?');" style="margin-top:18px;">
                    <button class="button-danger" type="submit" style="width:100%;">
                        <i class='bx bx-unlink'></i>
                        Verbindung trennen
                    </button>
                </form>
            <?php else: ?>
                <div class="card-soft" style="padding:18px;text-align:center;color:var(--text-soft);">
                    Aktuell ist kein Partner verbunden.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!$partner): ?>
        <section class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-icon icon-accent">
                        <i class='bx bx-link'></i>
                    </div>
                    <div>
                        <h2 class="card-title">Partner verbinden</h2>
                        <div class="card-subtitle">Gib hier den Partnercode der anderen Person ein</div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="post" action="/partner/connect" class="stack">
                    <div>
                        <label class="section-label" for="partner_code">Partnercode</label>
                        <input
                            class="input"
                            id="partner_code"
                            name="partner_code"
                            type="text"
                            value="<?= e((string) old('partner_code')) ?>"
                            placeholder="z. B. A7K9P2QX"
                            required
                        >
                    </div>

                    <button class="button" type="submit">
                        <i class='bx bx-check-circle'></i>
                        Partner verbinden
                    </button>
                </form>
            </div>
        </section>
    <?php endif; ?>
</div>
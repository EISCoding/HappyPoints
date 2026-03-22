<?php
declare(strict_types=1);

$message = getFlashMessage();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

function navIsActive(string $path, string $currentPath): bool
{
    return $path === $currentPath;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#161b26">
    <title><?= e(appName()) ?></title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        :root {
            color-scheme: dark;
            --bg: #161b26;
            --bg2: #1d2432;
            --surface: #202838;
            --surface2: #283245;
            --surface3: #313d53;
            --line: rgba(255,255,255,0.08);
            --text: #f2f5fb;
            --text-soft: #9ca8bc;
            --text-muted: #7e8aa0;
            --accent: #7c9cff;
            --accent2: #9c7cff;
            --success: #43c59e;
            --warn: #f0b257;
            --danger: #f26f86;
            --pink: #ff8fc7;
            --shadow: 0 10px 30px rgba(0,0,0,0.20);
            --shadow-strong: 0 20px 60px rgba(0,0,0,0.22);
            --radius-xl: 30px;
            --radius-lg: 24px;
            --radius-md: 20px;
            --radius-sm: 16px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(124,156,255,0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(255,143,199,0.10), transparent 22%),
                linear-gradient(180deg, #1a2130 0%, #161b26 46%, #141923 100%);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1400px, calc(100% - 24px));
            margin: 12px auto 24px;
        }

        .topbar {
            position: sticky;
            top: 12px;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 16px 18px;
            border-radius: 28px;
            background: rgba(22, 27, 38, 0.76);
            border: 1px solid var(--line);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 14px 40px rgba(0,0,0,0.24);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--pink) 0%, var(--accent) 100%);
            color: white;
            font-size: 28px;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.03), 0 18px 50px rgba(0,0,0,0.22);
            flex-shrink: 0;
        }

        .brand-copy {
            min-width: 0;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: var(--text-soft);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-link,
        .button,
        .button-muted,
        .button-danger,
        .button-success,
        .button-warn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            border-radius: 18px;
            font-weight: 700;
            transition: 180ms ease;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .nav-link:hover,
        .button:hover,
        .button-muted:hover,
        .button-danger:hover,
        .button-success:hover,
        .button-warn:hover {
            transform: translateY(-1px);
        }

        .nav-link {
            height: 44px;
            padding: 0 16px;
            font-size: 14px;
            background: rgba(255,255,255,0.05);
            color: var(--text);
            border-color: rgba(255,255,255,0.07);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--accent) 0%, #6c8df7 100%);
            color: white;
            box-shadow: 0 12px 30px rgba(108,141,247,0.28);
        }

        .hero-card {
            margin-top: 18px;
            overflow: hidden;
            border-radius: 34px;
            padding: 28px;
            background:
                radial-gradient(circle at top right, rgba(124,156,255,0.22), transparent 30%),
                radial-gradient(circle at bottom left, rgba(255,143,199,0.12), transparent 25%),
                linear-gradient(145deg, #2a3450 0%, #212a3b 55%, #1b2332 100%);
            border: 1px solid var(--line);
            box-shadow: var(--shadow-strong);
        }

        .hero-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: minmax(0, 1.4fr) minmax(320px, .9fr);
            align-items: center;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.06);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--text-soft);
        }

        .hero-title {
            margin: 16px 0 0;
            font-size: clamp(2.6rem, 5vw, 4.3rem);
            line-height: 1;
            letter-spacing: -0.05em;
            font-weight: 900;
            color: var(--text);
        }

        .hero-copy {
            margin: 16px 0 0;
            max-width: 760px;
            font-size: 15px;
            line-height: 1.8;
            color: var(--text-soft);
        }

        .hero-actions {
            margin-top: 24px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .metric {
            padding: 18px;
            border-radius: 24px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.03);
        }

        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--text-muted);
            font-weight: 700;
        }

        .metric-value {
            margin-top: 8px;
            font-size: 1.7rem;
            line-height: 1.1;
            font-weight: 900;
            color: var(--text);
        }

        .metric-sub {
            margin-top: 6px;
            font-size: 13px;
            color: var(--text-soft);
        }

        .page-grid {
            margin-top: 20px;
            display: grid;
            gap: 20px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .card {
            background: linear-gradient(180deg, rgba(40,50,69,0.96) 0%, rgba(31,39,55,0.96) 100%);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-soft {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 24px;
        }

        .card-head {
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .card-head-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .icon-accent { background: rgba(124,156,255,0.15); color: var(--accent); }
        .icon-pink { background: rgba(255,143,199,0.15); color: var(--pink); }
        .icon-success { background: rgba(67,197,158,0.15); color: var(--success); }
        .icon-warn { background: rgba(240,178,87,0.15); color: var(--warn); }

        .card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
        }

        .card-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: var(--text-soft);
        }

        .card-body {
            padding: 20px;
        }

        .section-label {
            display: block;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--text-muted);
        }

        .input,
        select,
        textarea {
            width: 100%;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(13, 17, 25, 0.34);
            color: var(--text);
            padding: 14px 16px;
            font-size: 15px;
            transition: 180ms ease;
            outline: none;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .input::placeholder,
        textarea::placeholder {
            color: var(--text-muted);
        }

        .input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(124,156,255,0.85);
            box-shadow: 0 0 0 4px rgba(124,156,255,0.12);
        }

        .button {
            height: 52px;
            padding: 0 18px;
            background: linear-gradient(135deg, var(--accent) 0%, #6c8df7 100%);
            color: white;
            box-shadow: 0 12px 30px rgba(108,141,247,0.28);
            border: none;
        }

        .button-success {
            height: 52px;
            padding: 0 18px;
            background: linear-gradient(135deg, #43c59e 0%, #33b38d 100%);
            color: #0d241d;
            box-shadow: 0 12px 30px rgba(67,197,158,0.22);
            border: none;
        }

        .button-warn {
            height: 52px;
            padding: 0 18px;
            background: linear-gradient(135deg, #f0b257 0%, #e6a649 100%);
            color: #2f230d;
            box-shadow: 0 12px 30px rgba(240,178,87,0.22);
            border: none;
        }

        .button-muted {
            height: 52px;
            padding: 0 18px;
            background: rgba(255,255,255,0.05);
            color: var(--text);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .button-danger {
            height: 52px;
            padding: 0 18px;
            background: rgba(242,111,134,0.14);
            color: #f89aad;
            border: 1px solid rgba(242,111,134,0.18);
        }

        .stack {
            display: grid;
            gap: 16px;
        }

        .form-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .info-list {
            display: grid;
            gap: 14px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            padding: 16px;
            border-radius: 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .info-key {
            font-size: 13px;
            color: var(--text-soft);
            min-width: 120px;
        }

        .info-value {
            text-align: right;
            font-weight: 700;
            color: var(--text);
            word-break: break-word;
        }

        .big {
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: var(--text);
        }

        .muted {
            color: var(--text-soft);
        }

        .balance-box {
            padding: 16px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.05);
        }

        .balance-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--text-muted);
        }

        .balance-value {
            margin-top: 6px;
            font-size: 2rem;
            font-weight: 900;
            color: var(--accent);
            letter-spacing: -0.04em;
        }

        .table-wrap {
            overflow-x: auto;
            border-radius: 26px;
            border: 1px solid var(--line);
            background: rgba(0,0,0,0.10);
        }

        .table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 16px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--text-muted);
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid var(--line);
        }

        .table td {
            padding: 16px 14px;
            border-top: 1px solid rgba(255,255,255,0.06);
            color: var(--text);
            vertical-align: top;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 800;
        }

        .badge.credit {
            background: rgba(67,197,158,0.15);
            color: var(--success);
        }

        .badge.debit {
            background: rgba(242,111,134,0.15);
            color: var(--danger);
        }

        .flash {
            margin-top: 14px;
            padding: 16px 18px;
            border-radius: 22px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .flash.success {
            background: rgba(67,197,158,0.14);
            border-color: rgba(67,197,158,0.22);
            color: #c9ffef;
        }

        .flash.error {
            background: rgba(242,111,134,0.14);
            border-color: rgba(242,111,134,0.22);
            color: #ffd4dd;
        }

        .auth-wrap {
            max-width: 560px;
            margin: 0 auto;
        }

        .auth-card {
            padding: 26px;
            border-radius: 32px;
            background:
                radial-gradient(circle at top right, rgba(124,156,255,0.20), transparent 28%),
                linear-gradient(180deg, rgba(40,50,69,0.98) 0%, rgba(31,39,55,0.98) 100%);
            border: 1px solid var(--line);
            box-shadow: var(--shadow-strong);
        }

        .auth-title {
            margin: 0;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .auth-subtitle {
            margin: 10px 0 0;
            color: var(--text-soft);
            line-height: 1.7;
        }

        .auth-logo {
            width: 62px;
            height: 62px;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--pink) 0%, var(--accent) 100%);
            color: white;
            font-size: 30px;
            margin-bottom: 18px;
        }

        .mobile-nav {
            display: none;
        }

        @media (max-width: 1080px) {
            .hero-grid,
            .grid-2,
            .grid-3,
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .shell {
                width: min(100% - 16px, 1400px);
                margin: 8px auto 90px;
            }

            .topbar {
                top: 8px;
                padding: 14px;
                border-radius: 24px;
            }

            .nav-links {
                display: none;
            }

            .hero-card {
                padding: 20px;
                border-radius: 28px;
            }

            .card {
                border-radius: 26px;
            }

            .card-head,
            .card-body {
                padding: 18px;
            }

            .mobile-nav {
                display: block;
                position: fixed;
                left: 8px;
                right: 8px;
                bottom: 8px;
                z-index: 60;
                padding-bottom: env(safe-area-inset-bottom);
            }

            .mobile-nav-inner {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 6px;
                padding: 8px;
                border-radius: 26px;
                background: rgba(22, 27, 38, 0.92);
                border: 1px solid var(--line);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
                box-shadow: 0 14px 40px rgba(0,0,0,0.24);
            }

            .mobile-link {
                min-height: 58px;
                border-radius: 18px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                font-size: 11px;
                font-weight: 700;
                color: var(--text-soft);
                background: transparent;
            }

            .mobile-link.active {
                background: rgba(255,255,255,0.08);
                color: var(--text);
            }
        }
    </style>
</head>
<body>
<div class="shell">
    <header class="topbar">
        <div class="brand">
            <div class="brand-icon">
                <i class='bx bxs-heart'></i>
            </div>
            <div class="brand-copy">
                <div class="brand-title"><?= e(appName()) ?></div>
                <div class="brand-subtitle">Modernes Dashboard für Konto, Partner und Transaktionen</div>
            </div>
        </div>

        <nav class="nav-links">
            <?php if (Auth::check()): ?>
                <a class="nav-link <?= navIsActive('/dashboard', $currentPath) ? 'active' : '' ?>" href="/dashboard">Dashboard</a>
                <a class="nav-link <?= navIsActive('/partner', $currentPath) ? 'active' : '' ?>" href="/partner">Partner</a>
                <a class="nav-link <?= navIsActive('/account', $currentPath) ? 'active' : '' ?>" href="/account">Konto</a>
                <a class="nav-link" href="/logout">Logout</a>
            <?php else: ?>
                <a class="nav-link <?= navIsActive('/login', $currentPath) ? 'active' : '' ?>" href="/login">Login</a>
                <a class="nav-link <?= navIsActive('/register', $currentPath) ? 'active' : '' ?>" href="/register">Registrierung</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php if ($message): ?>
        <div class="flash <?= e((string) $message['type']) ?>">
            <?= e((string) $message['text']) ?>
        </div>
    <?php endif; ?>
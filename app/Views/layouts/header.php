<?php
declare(strict_types=1);
$message = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(appName()) ?></title>
    <style>
        :root { color-scheme: dark; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:Inter,Arial,sans-serif; background:linear-gradient(180deg,#151922 0%,#10141c 100%); color:#eef2f7; }
        .container { max-width:980px; margin:0 auto; padding:24px 16px 48px; }
        .nav { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:24px; padding:16px 18px; border:1px solid rgba(255,255,255,0.08); border-radius:18px; background:rgba(255,255,255,0.04); }
        .nav a { color:#eef2f7; text-decoration:none; margin-left:12px; }
        .card { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08); border-radius:20px; padding:20px; margin-bottom:18px; }
        .input,.button,select { width:100%; border-radius:14px; border:1px solid rgba(255,255,255,0.10); padding:14px 15px; font-size:15px; }
        .input,select { background:rgba(0,0,0,0.18); color:#eef2f7; }
        .button { background:#7c9cff; color:white; border:none; cursor:pointer; font-weight:700; }
        .button.danger { background:#d85d73; }
        .flash { padding:14px 16px; border-radius:14px; margin-bottom:18px; font-weight:600; }
        .flash.success { background:rgba(72,201,176,0.16); border:1px solid rgba(72,201,176,0.22); }
        .flash.error { background:rgba(216,93,115,0.16); border:1px solid rgba(216,93,115,0.22); }
        .muted { color:#9aa7ba; }
        .big { font-size:34px; font-weight:800; }
        .row { display:flex; gap:12px; flex-wrap:wrap; }
        .row > * { flex:1; min-width:180px; }
        .table { width:100%; border-collapse:collapse; }
        .table th,.table td { text-align:left; padding:12px 10px; border-bottom:1px solid rgba(255,255,255,0.08); }
        .badge { display:inline-block; padding:6px 10px; border-radius:999px; font-size:13px; font-weight:700; }
        .badge.credit { background:rgba(72,201,176,0.16); color:#76e1c4; }
        .badge.debit { background:rgba(216,93,115,0.16); color:#ff9aaa; }
        h1,h2,h3,p { margin-top:0; }
        form { margin:0; }
        label { display:block; margin-bottom:8px; font-weight:600; }
        .stack { display:grid; gap:14px; }
        .auth-wrap { max-width:520px; margin:0 auto; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <div><strong><?= e(appName()) ?></strong></div>
        <div>
            <?php if (Auth::check()): ?>
                <a href="/dashboard">Dashboard</a>
                <a href="/partner">Partner</a>
                <a href="/account">Konto</a>
                <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Registrierung</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="flash <?= e((string) $message['type']) ?>">
            <?= e((string) $message['text']) ?>
        </div>
    <?php endif; ?>

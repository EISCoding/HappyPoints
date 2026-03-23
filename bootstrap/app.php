<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/Views');

$appConfig = require BASE_PATH . '/config/app.php';
$dbConfig = require BASE_PATH . '/config/database.php';

require_once APP_PATH . '/Core/Session.php';
require_once APP_PATH . '/Core/Helpers.php';
require_once APP_PATH . '/Core/Redirect.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/View.php';
require_once APP_PATH . '/Core/Auth.php';
require_once APP_PATH . '/Core/Mailer.php';

Session::start((string)($appConfig['session_name'] ?? 'app_session'));
Database::init($dbConfig);
date_default_timezone_set((string)($appConfig['timezone'] ?? 'Europe/Berlin'));

set_exception_handler(function (Throwable $e) use ($appConfig): void {
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    $debug = (bool)($appConfig['debug'] ?? false);
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><title>Fehler</title></head><body style="font-family:Arial,sans-serif;padding:32px;background:#111827;color:#f9fafb">';
    echo '<h1>Interner Fehler</h1><p>Die Anwendung konnte die Anfrage nicht verarbeiten.</p>';
    if ($debug) {
        echo '<pre style="white-space:pre-wrap;background:#1f2937;padding:16px;border-radius:12px;">';
        echo htmlspecialchars($e->getMessage() . "\n\n" . $e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
        echo '</pre>';
    }
    echo '</body></html>';
});

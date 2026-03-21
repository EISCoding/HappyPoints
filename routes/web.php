<?php
declare(strict_types=1);

require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Models/Account.php';
require_once APP_PATH . '/Models/Transaction.php';
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/DashboardController.php';
require_once APP_PATH . '/Controllers/AccountController.php';
require_once APP_PATH . '/Controllers/PartnerController.php';

$authController = new AuthController();
$dashboardController = new DashboardController();
$accountController = new AccountController();
$partnerController = new PartnerController();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

switch ([$method, $uri]) {
    case ['GET', '/']:
        if (Auth::check()) Redirect::to('/dashboard');
        Redirect::to('/login');
        break;
    case ['GET', '/login']:
        $authController->showLogin();
        break;
    case ['POST', '/login']:
        $authController->login();
        break;
    case ['GET', '/register']:
        $authController->showRegister();
        break;
    case ['POST', '/register']:
        $authController->register();
        break;
    case ['GET', '/logout']:
        $authController->logout();
        break;
    case ['GET', '/dashboard']:
        $dashboardController->index();
        break;
    case ['POST', '/dashboard/balance']:
        $dashboardController->adjustBalance();
        break;
    case ['GET', '/account']:
        $accountController->index();
        break;
    case ['GET', '/partner']:
        $partnerController->index();
        break;
    case ['POST', '/partner/connect']:
        $partnerController->connect();
        break;
    case ['POST', '/partner/disconnect']:
        $partnerController->disconnect();
        break;
    default:
        http_response_code(404);
        echo '404 - Seite nicht gefunden';
        break;
}

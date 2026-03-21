<?php
declare(strict_types=1);

final class View
{
    public static function render(string $view, array $data = []): void
    {
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View nicht gefunden: ' . e($view);
            exit;
        }
        extract($data, EXTR_SKIP);
        require VIEW_PATH . '/layouts/header.php';
        require $viewFile;
        require VIEW_PATH . '/layouts/footer.php';
    }
}

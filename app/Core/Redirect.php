<?php
declare(strict_types=1);

final class Redirect
{
    public static function to(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }
}

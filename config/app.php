<?php
declare(strict_types=1);

return [
    'name' => 'Happypoints',
    'session_name' => 'happypoints_session',
    'debug' => true,
    'timezone' => 'Europe/Berlin',
    'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost',
];

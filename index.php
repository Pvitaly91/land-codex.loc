<?php
declare(strict_types=1);

$credentials = [
    'username' => 'admin',
    'password' => 'StrongPass123',
];

$authenticated = isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
    && hash_equals($credentials['username'], $_SERVER['PHP_AUTH_USER'])
    && hash_equals($credentials['password'], $_SERVER['PHP_AUTH_PW']);

if (!$authenticated) {
    header('WWW-Authenticate: Basic realm="Secure Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Доступ заборонено: необхідна авторизація.';
    exit;
}

$documentPath = __DIR__ . '/index.html';

if (!is_readable($documentPath)) {
    http_response_code(500);
    echo 'Основний файл сторінки недоступний.';
    exit;
}

readfile($documentPath);

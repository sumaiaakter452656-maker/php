<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function require_user_role(string $role): void
{
    if (!isset($_SESSION['user_id'], $_SESSION['user_role'])) {
        header('Location: index.php');
        exit;
    }

    if ($_SESSION['user_role'] !== $role) {
        http_response_code(403);
        exit('You do not have permission to access this page.');
    }
}

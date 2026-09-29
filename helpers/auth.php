<?php
declare(strict_types=1);

function require_auth(): void
{
    if (!current_user_id()) {
        redirect('/login');
    }
}

function require_guest(): void
{
    if (current_user_id()) {
        redirect('/dashboard');
    }
}

function login_user(int $userId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
}

function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool)$params['secure'], (bool)$params['httponly']);
    }

    session_destroy();
}

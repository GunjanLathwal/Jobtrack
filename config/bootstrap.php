<?php
declare(strict_types=1);

$configFile = __DIR__ . '/env.php';
if (!file_exists($configFile)) {
    $configFile = __DIR__ . '/env.example.php';
}
$config = require $configFile;

date_default_timezone_set('Asia/Kolkata');

if (session_status() === PHP_SESSION_NONE) {
    session_name($config['app']['session_name']);
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../database/Database.php';

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/ApplicationEvent.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ApplicationController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';

$db = Database::getInstance($config['db']);

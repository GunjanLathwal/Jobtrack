<?php
declare(strict_types=1);

require __DIR__ . '/config/bootstrap.php';

function render(string $view, array $data = []): void
{
    global $config, $db;
    extract($data, EXTR_SKIP);
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . '/views/' . $view . '.php';
    require __DIR__ . '/views/layout/footer.php';
}

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $path = rtrim($path, '/') ?: '/';

    // API routing is intentionally kept in api/index.php.
    if (str_starts_with($path, '/api/')) {
        require __DIR__ . '/api/index.php';
        exit;
    }

    $users = new User($db);
    $applications = new Application($db);
    $events = new ApplicationEvent($db);

    $authController = new AuthController($users);
    $applicationController = new ApplicationController($applications, $events);
    $dashboardController = new DashboardController($applications);

    switch (true) {
        case $path === '/' && current_user_id():
            redirect('/dashboard');
        case $path === '/':
            redirect('/login');

        case $path === '/login' && $method === 'GET':
        case $path === '/login' && $method === 'POST':
            $authController->login();
            break;

        case $path === '/register' && in_array($method, ['GET', 'POST'], true):
            $authController->register();
            break;

        case $path === '/logout':
            $authController->logout();
            break;

        case $path === '/dashboard':
            $dashboardController->index();
            break;

        case $path === '/analytics':
            $dashboardController->analytics();
            break;

        case $path === '/applications':
            $applicationController->index();
            break;

        case $path === '/applications/new':
            $applicationController->create();
            break;

        case $path === '/applications/kanban':
            $applicationController->kanban();
            break;

        case preg_match('#^/applications/(\d+)/edit$#', $path, $m):
            $applicationController->edit((int)$m[1]);
            break;

        case preg_match('#^/applications/(\d+)/events$#', $path, $m) && $method === 'POST':
            $applicationController->addEvent((int)$m[1]);
            break;

        case preg_match('#^/applications/(\d+)/delete$#', $path, $m) && $method === 'POST':
            $applicationController->delete((int)$m[1]);
            break;

        case preg_match('#^/applications/(\d+)$#', $path, $m):
            $applicationController->show((int)$m[1]);
            break;

        default:
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
    }
} catch (Throwable $e) {
    error_log((string)$e);
    http_response_code(500);

    $isDev = ($config['app']['environment'] ?? 'production') === 'development';
    if ($isDev) {
        echo '<pre style="padding:2rem;font-family:monospace">';
        echo e($e->getMessage());
        echo '</pre>';
    } else {
        render('errors/404', ['title' => 'Something went wrong']);
    }
}

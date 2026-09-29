<?php
declare(strict_types=1);

header('Cache-Control: no-store');

if (!current_user_id()) {
    json_response(false, null, 'Authentication required.', 401);
}

$userId = current_user_id();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$applications = new Application($db);
$events = new ApplicationEvent($db);

try {
    if ($path === '/api/dashboard/stats' && $method === 'GET') {
        json_response(true, $applications->dashboardStats($userId));
    }

    if ($path === '/api/applications' && $method === 'GET') {
        $filters = [
            'search' => trim((string)($_GET['search'] ?? '')),
            'status' => $_GET['status'] ?? '',
            'location' => trim((string)($_GET['location'] ?? '')),
            'employment_type' => $_GET['employment_type'] ?? '',
            'sort' => $_GET['sort'] ?? '',
        ];
        json_response(true, $applications->findAllForUser($userId, $filters));
    }

    if ($path === '/api/applications' && $method === 'POST') {
        $data = request_json();
        $errors = validate_application($data);
        if ($errors) json_response(false, ['errors' => $errors], 'Validation failed.', 422);

        $id = $applications->create($userId, $data);
        json_response(true, ['id' => $id], 'Application created.', 201);
    }

    if (preg_match('#^/api/applications/(\d+)$#', $path, $m)) {
        $id = (int)$m[1];
        $application = $applications->findForUser($id, $userId);

        if (!$application) json_response(false, null, 'Application not found.', 404);

        if ($method === 'GET') {
            json_response(true, [
                'application' => $application,
                'events' => $events->forApplication($id),
            ]);
        }

        if ($method === 'PUT') {
            $data = request_json();
            $errors = validate_application($data);
            if ($errors) json_response(false, ['errors' => $errors], 'Validation failed.', 422);

            $applications->update($id, $userId, $data);
            json_response(true, ['id' => $id], 'Application updated.');
        }

        if ($method === 'DELETE') {
            $applications->delete($id, $userId);
            json_response(true, null, 'Application deleted.');
        }

        json_response(false, null, 'Method not allowed.', 400);
    }

    if (preg_match('#^/api/applications/(\d+)/status$#', $path, $m) && $method === 'PATCH') {
        $id = (int)$m[1];
        $data = request_json();
        $status = (string)($data['status'] ?? '');

        if (!in_array($status, APPLICATION_STATUSES, true)) {
            json_response(false, null, 'Invalid status.', 422);
        }

        if (!$applications->findForUser($id, $userId)) {
            json_response(false, null, 'Application not found.', 404);
        }

        $applications->updateStatus($id, $userId, $status);
        json_response(true, ['id' => $id, 'status' => $status], 'Status updated.');
    }

    json_response(false, null, 'Endpoint not found.', 404);
} catch (Throwable $e) {
    error_log((string)$e);
    json_response(false, null, 'An internal server error occurred.', 500);
}

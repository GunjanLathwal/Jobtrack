<?php
declare(strict_types=1);

final class ApplicationController
{
    public function __construct(
        private Application $applications,
        private ApplicationEvent $events
    ) {}

    public function index(): void
    {
        require_auth();
        $filters = [
            'search' => trim((string)($_GET['search'] ?? '')),
            'status' => $_GET['status'] ?? '',
            'location' => trim((string)($_GET['location'] ?? '')),
            'employment_type' => $_GET['employment_type'] ?? '',
            'sort' => $_GET['sort'] ?? '',
        ];

        render('applications/index', [
            'title' => 'Applications',
            'applications' => $this->applications->findAllForUser(current_user_id(), $filters),
            'filters' => $filters,
            'statuses' => APPLICATION_STATUSES,
            'employmentTypes' => EMPLOYMENT_TYPES,
        ]);
    }

    public function create(): void
    {
        require_auth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save($_POST);
            return;
        }

        render('applications/form', [
            'title' => 'Add application',
            'application' => [],
            'statuses' => APPLICATION_STATUSES,
            'employmentTypes' => EMPLOYMENT_TYPES,
        ]);
    }

    public function edit(int $id): void
    {
        require_auth();
        $application = $this->applications->findForUser($id, current_user_id());

        if (!$application) {
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save($_POST, $id);
            return;
        }

        render('applications/form', [
            'title' => 'Edit application',
            'application' => $application,
            'statuses' => APPLICATION_STATUSES,
            'employmentTypes' => EMPLOYMENT_TYPES,
        ]);
    }

    public function show(int $id): void
    {
        require_auth();
        $application = $this->applications->findForUser($id, current_user_id());

        if (!$application) {
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
            return;
        }

        render('applications/show', [
            'title' => $application['company_name'] . ' — ' . $application['job_title'],
            'application' => $application,
            'events' => $this->events->forApplication($id),
        ]);
    }

    public function delete(int $id): void
    {
        require_auth();

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request token.');
            redirect('/applications');
        }

        $deleted = $this->applications->delete($id, current_user_id());
        flash($deleted ? 'success' : 'error', $deleted ? 'Application deleted.' : 'Application not found.');
        redirect('/applications');
    }

    public function addEvent(int $id): void
    {
        require_auth();
        $application = $this->applications->findForUser($id, current_user_id());

        if (!$application) {
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
            return;
        }

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request token.');
            redirect('/applications/' . $id);
        }

        $type = trim((string)($_POST['event_type'] ?? ''));
        $date = trim((string)($_POST['event_date'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if ($type === '' || $date === '') {
            flash('error', 'Event type and date are required.');
            redirect('/applications/' . $id);
        }

        $this->events->create($id, $type, $date, $description ?: null);
        flash('success', 'Timeline event added.');
        redirect('/applications/' . $id);
    }

    public function kanban(): void
    {
        require_auth();
        render('applications/kanban', [
            'title' => 'Kanban board',
            'applications' => $this->applications->findAllForUser(current_user_id()),
            'statuses' => APPLICATION_STATUSES,
        ]);
    }

    private function save(array $input, ?int $id = null): void
    {
        if (!verify_csrf($input['csrf_token'] ?? null)) {
            flash('error', 'Invalid request token.');
            redirect($id ? '/applications/' . $id . '/edit' : '/applications/new');
        }

        $errors = validate_application($input);
        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old'] = $input;
            redirect($id ? '/applications/' . $id . '/edit' : '/applications/new');
        }

        if ($id) {
            $this->applications->update($id, current_user_id(), $input);
            flash('success', 'Application updated.');
            redirect('/applications/' . $id);
        }

        $newId = $this->applications->create(current_user_id(), $input);
        flash('success', 'Application added.');
        redirect('/applications/' . $newId);
    }
}

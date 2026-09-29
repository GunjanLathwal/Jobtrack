<?php
declare(strict_types=1);

final class DashboardController
{
    public function __construct(private Application $applications) {}

    public function index(): void
    {
        require_auth();

        render('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $this->applications->dashboardStats(current_user_id()),
            'statusCounts' => $this->applications->statusCounts(current_user_id()),
            'recent' => array_slice($this->applications->findAllForUser(current_user_id()), 0, 5),
            'dueFollowUps' => $this->applications->dueFollowUps(current_user_id()),
        ]);
    }

    public function analytics(): void
    {
        require_auth();

        render('dashboard/analytics', [
            'title' => 'Analytics',
            'stats' => $this->applications->dashboardStats(current_user_id()),
            'statusCounts' => $this->applications->statusCounts(current_user_id()),
            'monthlyCounts' => $this->applications->monthlyCounts(current_user_id()),
        ]);
    }
}

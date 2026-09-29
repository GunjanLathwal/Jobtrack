<?php
declare(strict_types=1);

final class Application
{
    public function __construct(private PDO $db) {}

    public function findAllForUser(int $userId, array $filters = []): array
    {
        $where = ['user_id = :user_id'];
        $params = ['user_id' => $userId];

        if (!empty($filters['search'])) {
            $where[] = '(company_name LIKE :search OR job_title LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['status']) && in_array($filters['status'], APPLICATION_STATUSES, true)) {
            $where[] = 'status = :status';
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['location'])) {
            $where[] = 'location LIKE :location';
            $params['location'] = '%' . $filters['location'] . '%';
        }
        if (!empty($filters['employment_type']) && in_array($filters['employment_type'], EMPLOYMENT_TYPES, true)) {
            $where[] = 'employment_type = :employment_type';
            $params['employment_type'] = $filters['employment_type'];
        }

        $sort = match ($filters['sort'] ?? '') {
            'oldest' => 'date_applied ASC, id ASC',
            'followup' => 'follow_up_date IS NULL, follow_up_date ASC, id DESC',
            default => 'date_applied DESC, id DESC',
        };

        $sql = 'SELECT * FROM applications WHERE ' . implode(' AND ', $where) . " ORDER BY $sort";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findForUser(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM applications WHERE id = :id AND user_id = :user_id LIMIT 1');
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $userId, array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO applications
            (user_id, company_name, job_title, job_url, location, employment_type, salary_min, salary_max,
             date_applied, status, recruiter_name, recruiter_email, notes, follow_up_date)
            VALUES
            (:user_id, :company_name, :job_title, :job_url, :location, :employment_type, :salary_min, :salary_max,
             :date_applied, :status, :recruiter_name, :recruiter_email, :notes, :follow_up_date)'
        );

        $stmt->execute($this->payload($userId, $data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $params = $this->payload($userId, $data);
        $params['id'] = $id;

        $stmt = $this->db->prepare(
            'UPDATE applications SET
             company_name = :company_name, job_title = :job_title, job_url = :job_url,
             location = :location, employment_type = :employment_type, salary_min = :salary_min,
             salary_max = :salary_max, date_applied = :date_applied, status = :status,
             recruiter_name = :recruiter_name, recruiter_email = :recruiter_email,
             notes = :notes, follow_up_date = :follow_up_date
             WHERE id = :id AND user_id = :user_id'
        );

        $stmt->execute($params);
        return $stmt->rowCount() >= 0;
    }

    public function updateStatus(int $id, int $userId, string $status): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE applications SET status = :status WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute(['status' => $status, 'id' => $id, 'user_id' => $userId]);
        return $stmt->rowCount() >= 0;
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM applications WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    public function dashboardStats(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(date_applied >= DATE_FORMAT(CURDATE(), '%Y-%m-01')) AS this_month,
                SUM(status = 'Interview') AS interviews,
                SUM(status = 'Offer') AS offers,
                SUM(status = 'Rejected') AS rejections,
                SUM(status NOT IN ('Wishlist')) AS responses,
                SUM(status IN ('Assessment','Interview','Offer','Rejected','Withdrawn')) AS responded,
                SUM(status IN ('Interview','Offer')) AS reached_interview
             FROM applications
             WHERE user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        $stats = $stmt->fetch() ?: [];

        $total = (int)($stats['total'] ?? 0);
        $responded = (int)($stats['responded'] ?? 0);
        $reachedInterview = (int)($stats['reached_interview'] ?? 0);

        $stats['response_rate'] = $total > 0 ? round(($responded / $total) * 100, 1) : 0;
        $stats['interview_rate'] = $total > 0 ? round(($reachedInterview / $total) * 100, 1) : 0;

        return $stats;
    }

    public function statusCounts(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT status, COUNT(*) AS total FROM applications WHERE user_id = :user_id GROUP BY status'
        );
        $stmt->execute(['user_id' => $userId]);

        $result = array_fill_keys(APPLICATION_STATUSES, 0);
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['status']] = (int)$row['total'];
        }
        return $result;
    }

    public function monthlyCounts(int $userId, int $months = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE_FORMAT(date_applied, '%Y-%m') AS month, COUNT(*) AS total
             FROM applications
             WHERE user_id = :user_id
               AND date_applied >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
             GROUP BY month
             ORDER BY month ASC"
        );
        $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue('months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function dueFollowUps(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM applications
             WHERE user_id = :user_id
               AND follow_up_date IS NOT NULL
               AND follow_up_date <= CURDATE()
               AND status NOT IN ('Rejected','Withdrawn','Offer')
             ORDER BY follow_up_date ASC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    private function payload(int $userId, array $data): array
    {
        $nullable = static fn($value) => ($value === '' || $value === null) ? null : trim((string)$value);

        return [
            'user_id' => $userId,
            'company_name' => trim((string)($data['company_name'] ?? '')),
            'job_title' => trim((string)($data['job_title'] ?? '')),
            'job_url' => $nullable($data['job_url'] ?? null),
            'location' => $nullable($data['location'] ?? null),
            'employment_type' => $data['employment_type'] ?? 'Full-time',
            'salary_min' => ($data['salary_min'] ?? '') === '' ? null : (float)$data['salary_min'],
            'salary_max' => ($data['salary_max'] ?? '') === '' ? null : (float)$data['salary_max'],
            'date_applied' => $nullable($data['date_applied'] ?? null),
            'status' => $data['status'] ?? 'Wishlist',
            'recruiter_name' => $nullable($data['recruiter_name'] ?? null),
            'recruiter_email' => $nullable($data['recruiter_email'] ?? null),
            'notes' => $nullable($data['notes'] ?? null),
            'follow_up_date' => $nullable($data['follow_up_date'] ?? null),
        ];
    }
}

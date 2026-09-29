<?php
declare(strict_types=1);

final class ApplicationEvent
{
    public function __construct(private PDO $db) {}

    public function forApplication(int $applicationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM application_events WHERE application_id = :application_id ORDER BY event_date ASC, id ASC'
        );
        $stmt->execute(['application_id' => $applicationId]);
        return $stmt->fetchAll();
    }

    public function create(int $applicationId, string $eventType, string $eventDate, ?string $description): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO application_events (application_id, event_type, event_date, description)
             VALUES (:application_id, :event_type, :event_date, :description)'
        );
        $stmt->execute([
            'application_id' => $applicationId,
            'event_type' => trim($eventType),
            'event_date' => $eventDate,
            'description' => $description ? trim($description) : null,
        ]);
        return (int)$this->db->lastInsertId();
    }
}

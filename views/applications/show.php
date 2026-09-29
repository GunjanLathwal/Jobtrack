<div class="page-head">
    <div>
        <div class="eyebrow"><?= e($application['company_name']) ?></div>
        <h1><?= e($application['job_title']) ?></h1>
        <p class="muted"><?= e($application['location'] ?? 'Location not specified') ?></p>
    </div>
    <div class="actions">
        <a class="btn" href="/applications/<?= (int)$application['id'] ?>/edit">Edit</a>
        <form method="post" action="/applications/<?= (int)$application['id'] ?>/delete" data-confirm="Delete this application?">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <button class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>

<div class="grid-2">
    <section class="panel">
        <div class="panel-head"><h2>Details</h2><span class="badge <?= e(status_class($application['status'])) ?>"><?= e($application['status']) ?></span></div>
        <dl class="details">
            <div><dt>Company</dt><dd><?= e($application['company_name']) ?></dd></div>
            <div><dt>Role</dt><dd><?= e($application['job_title']) ?></dd></div>
            <div><dt>Location</dt><dd><?= e($application['location'] ?? '—') ?></dd></div>
            <div><dt>Employment</dt><dd><?= e($application['employment_type']) ?></dd></div>
            <div><dt>Salary</dt><dd><?= $application['salary_min'] || $application['salary_max'] ? e((string)($application['salary_min'] ?? '')) . ' — ' . e((string)($application['salary_max'] ?? '')) : '—' ?></dd></div>
            <div><dt>Applied</dt><dd><?= e(format_date($application['date_applied'])) ?></dd></div>
            <div><dt>Follow-up</dt><dd><?= e(format_date($application['follow_up_date'])) ?></dd></div>
            <div><dt>Recruiter</dt><dd><?= e($application['recruiter_name'] ?? '—') ?><?= $application['recruiter_email'] ? ' · ' . e($application['recruiter_email']) : '' ?></dd></div>
            <div><dt>Job URL</dt><dd><?= $application['job_url'] ? '<a href="' . e($application['job_url']) . '" target="_blank" rel="noopener noreferrer">Open posting ↗</a>' : '—' ?></dd></div>
        </dl>
        <div class="notes"><strong>Notes</strong><p><?= nl2br(e($application['notes'] ?? 'No notes yet.')) ?></p></div>
    </section>

    <section class="panel">
        <div class="panel-head"><h2>Timeline</h2></div>
        <?php if (!$events): ?><div class="empty">No events yet. Add the first milestone below.</div><?php endif; ?>
        <div class="timeline">
            <?php foreach ($events as $event): ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div><strong><?= e($event['event_type']) ?></strong><span><?= e(format_date($event['event_date'])) ?></span><p><?= e($event['description'] ?? '') ?></p></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="post" action="/applications/<?= (int)$application['id'] ?>/events" class="event-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label>Event type <input name="event_type" required placeholder="Technical interview"></label>
            <label>Date <input type="date" name="event_date" required value="<?= date('Y-m-d') ?>"></label>
            <label>Description <textarea name="description" rows="3" placeholder="What happened?"></textarea></label>
            <button class="btn btn-primary">Add event</button>
        </form>
    </section>
</div>

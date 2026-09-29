<div class="page-head">
    <div><div class="eyebrow">APPLICATIONS</div><h1>Application tracker</h1></div>
    <a class="btn btn-primary" href="/applications/new">+ Add application</a>
</div>

<form class="filters" method="get" action="/applications">
    <input type="search" name="search" placeholder="Search company or role..." value="<?= e($filters['search']) ?>">
    <select name="status"><option value="">All statuses</option><?php foreach ($statuses as $s): ?><option <?= $filters['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
    <input type="text" name="location" placeholder="Location" value="<?= e($filters['location']) ?>">
    <select name="employment_type"><option value="">All types</option><?php foreach ($employmentTypes as $t): ?><option value="<?= e($t) ?>" <?= $filters['employment_type'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select>
    <select name="sort">
        <option value="">Newest</option>
        <option value="oldest" <?= $filters['sort'] === 'oldest' ? 'selected' : '' ?>>Oldest</option>
        <option value="followup" <?= $filters['sort'] === 'followup' ? 'selected' : '' ?>>Upcoming follow-up</option>
    </select>
    <button class="btn">Filter</button>
</form>

<section class="panel">
    <?php if (!$applications): ?>
        <div class="empty large">No applications match your filters.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Company</th><th>Role</th><th>Status</th><th>Applied</th><th>Follow-up</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><a class="table-primary" href="/applications/<?= (int)$app['id'] ?>"><?= e($app['company_name']) ?></a></td>
                        <td><?= e($app['job_title']) ?><div class="muted small"><?= e($app['location'] ?? '') ?></div></td>
                        <td><span class="badge <?= e(status_class($app['status'])) ?>"><?= e($app['status']) ?></span></td>
                        <td><?= e(format_date($app['date_applied'])) ?></td>
                        <td><?= e(format_date($app['follow_up_date'])) ?></td>
                        <td><a class="btn btn-small" href="/applications/<?= (int)$app['id'] ?>/edit">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

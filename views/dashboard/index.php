<div class="page-head">
    <div>
        <div class="eyebrow">OVERVIEW</div>
        <h1>Your job search, at a glance.</h1>
        <p class="muted">Keep momentum visible and follow-ups actionable.</p>
    </div>
    <a class="btn btn-primary" href="/applications/new">+ Add application</a>
</div>

<div class="stats-grid">
    <div class="stat"><span>Total applications</span><strong><?= (int)$stats['total'] ?></strong></div>
    <div class="stat"><span>This month</span><strong><?= (int)$stats['this_month'] ?></strong></div>
    <div class="stat"><span>Interviews</span><strong><?= (int)$stats['interviews'] ?></strong></div>
    <div class="stat"><span>Offers</span><strong><?= (int)$stats['offers'] ?></strong></div>
    <div class="stat"><span>Rejections</span><strong><?= (int)$stats['rejections'] ?></strong></div>
    <div class="stat"><span>Response rate</span><strong><?= e((string)$stats['response_rate']) ?>%</strong></div>
</div>

<div class="grid-2">
    <section class="panel">
        <div class="panel-head"><h2>Recent applications</h2><a href="/applications">View all</a></div>
        <?php if (!$recent): ?>
            <div class="empty">No applications yet. Add your first one.</div>
        <?php else: ?>
            <div class="list">
                <?php foreach ($recent as $app): ?>
                    <a class="list-row" href="/applications/<?= (int)$app['id'] ?>">
                        <div><strong><?= e($app['company_name']) ?></strong><span><?= e($app['job_title']) ?></span></div>
                        <span class="badge <?= e(status_class($app['status'])) ?>"><?= e($app['status']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="panel">
        <div class="panel-head"><h2>Follow-ups due</h2><a href="/applications?sort=followup">Open list</a></div>
        <?php if (!$dueFollowUps): ?>
            <div class="empty">Nothing due today. Nice.</div>
        <?php else: ?>
            <div class="list">
                <?php foreach ($dueFollowUps as $app): ?>
                    <a class="list-row" href="/applications/<?= (int)$app['id'] ?>">
                        <div><strong><?= e($app['company_name']) ?></strong><span><?= e($app['job_title']) ?></span></div>
                        <span class="due"><?= e(format_date($app['follow_up_date'])) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<section class="panel">
    <div class="panel-head"><h2>Pipeline</h2><a href="/applications/kanban">Open Kanban</a></div>
    <div class="pipeline">
        <?php foreach ($statusCounts as $status => $count): ?>
            <div class="pipeline-item">
                <span class="badge <?= e(status_class($status)) ?>"><?= e($status) ?></span>
                <strong><?= (int)$count ?></strong>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="page-head">
    <div><div class="eyebrow">PIPELINE</div><h1>Kanban board</h1><p class="muted">Move applications through your search stages.</p></div>
    <a class="btn btn-primary" href="/applications/new">+ Add application</a>
</div>

<div class="kanban">
<?php foreach (['Wishlist','Applied','Assessment','Interview','Offer','Rejected'] as $status): ?>
    <section class="kanban-column">
        <div class="kanban-head"><span class="badge <?= e(status_class($status)) ?>"><?= e($status) ?></span><strong data-count="<?= e($status) ?>">0</strong></div>
        <div class="kanban-cards" data-status="<?= e($status) ?>">
        <?php foreach ($applications as $app): if ($app['status'] === $status): ?>
            <article class="job-card" data-id="<?= (int)$app['id'] ?>">
                <a href="/applications/<?= (int)$app['id'] ?>"><strong><?= e($app['company_name']) ?></strong><span><?= e($app['job_title']) ?></span></a>
                <small><?= e(format_date($app['date_applied'])) ?> · <?= e($app['location'] ?? 'Remote/Unspecified') ?></small>
                <select class="status-change" data-id="<?= (int)$app['id'] ?>">
                    <?php foreach (APPLICATION_STATUSES as $s): ?><option <?= $s === $status ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
                </select>
            </article>
        <?php endif; endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>
</div>

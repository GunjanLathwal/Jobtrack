<div class="page-head">
    <div><div class="eyebrow">ANALYTICS</div><h1>Search performance</h1><p class="muted">The numbers behind your application pipeline.</p></div>
</div>

<div class="stats-grid">
    <div class="stat"><span>Total applications</span><strong><?= (int)$stats['total'] ?></strong></div>
    <div class="stat"><span>Response rate</span><strong><?= e((string)$stats['response_rate']) ?>%</strong></div>
    <div class="stat"><span>Interview rate</span><strong><?= e((string)$stats['interview_rate']) ?>%</strong></div>
    <div class="stat"><span>Interviews</span><strong><?= (int)$stats['interviews'] ?></strong></div>
    <div class="stat"><span>Offers</span><strong><?= (int)$stats['offers'] ?></strong></div>
    <div class="stat"><span>Rejections</span><strong><?= (int)$stats['rejections'] ?></strong></div>
</div>

<div class="grid-2">
    <section class="panel">
        <div class="panel-head"><h2>Applications by status</h2></div>
        <?php foreach ($statusCounts as $status => $count): ?>
            <div class="bar-row"><span><?= e($status) ?></span><div class="bar"><i style="width:<?= $stats['total'] ? round($count / $stats['total'] * 100) : 0 ?>%"></i></div><strong><?= (int)$count ?></strong></div>
        <?php endforeach; ?>
    </section>
    <section class="panel">
        <div class="panel-head"><h2>Applications by month</h2></div>
        <?php if (!$monthlyCounts): ?><div class="empty">No monthly data yet.</div><?php endif; ?>
        <?php foreach ($monthlyCounts as $row): ?>
            <div class="bar-row"><span><?= e(date('M Y', strtotime($row['month'] . '-01'))) ?></span><div class="bar"><i style="width:<?= min(100, (int)$row['total'] * 10) ?>%"></i></div><strong><?= (int)$row['total'] ?></strong></div>
        <?php endforeach; ?>
    </section>
</div>

<section class="panel">
    <div class="panel-head"><h2>How metrics are defined</h2></div>
    <ul class="explain-list">
        <li><strong>Response rate:</strong> applications that moved beyond Wishlist/Applied divided by total applications.</li>
        <li><strong>Interview rate:</strong> applications that reached Interview or Offer divided by total applications.</li>
        <li><strong>Status counts:</strong> grouped directly in MySQL using aggregation.</li>
    </ul>
</section>

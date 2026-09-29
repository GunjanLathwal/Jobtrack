<?php
$flashes = consume_flash();
$user = current_user_id() ? (new User($db))->findById(current_user_id()) : null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'JobTrack') ?> · JobTrack</title>
    <link rel="stylesheet" href="/public/css/app.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= current_user_id() ? '/dashboard' : '/login' ?>">Job<span>Track</span></a>
    <?php if ($user): ?>
        <nav class="nav">
            <a href="/dashboard">Dashboard</a>
            <a href="/applications">Applications</a>
            <a href="/applications/kanban">Kanban</a>
            <a href="/analytics">Analytics</a>
        </nav>
        <div class="user-menu">
            <span><?= e($user['name']) ?></span>
            <a class="btn btn-ghost" href="/logout">Logout</a>
        </div>
    <?php endif; ?>
</header>
<main class="container">
<?php foreach ($flashes as $flash): ?>
    <div class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endforeach; ?>

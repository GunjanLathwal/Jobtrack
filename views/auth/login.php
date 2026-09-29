<div class="auth-card">
    <div class="eyebrow">JOBTRACK</div>
    <h1>Track the search.<br><span>Own the process.</span></h1>
    <p class="muted">A focused workspace for applications, interviews and follow-ups.</p>

    <form method="post" action="/login" class="form-stack">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Email
            <input type="email" name="email" required autocomplete="email">
        </label>
        <label>Password
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button class="btn btn-primary btn-wide">Login</button>
    </form>

    <p class="auth-link">New here? <a href="/register">Create an account</a></p>
</div>

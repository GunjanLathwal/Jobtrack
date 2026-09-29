<div class="auth-card">
    <div class="eyebrow">JOBTRACK</div>
    <h1>Create your workspace.</h1>
    <p class="muted">Keep every application, follow-up and interview in one place.</p>

    <form method="post" action="/register" class="form-stack">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Name
            <input type="text" name="name" value="<?= e((string)old('name')) ?>" required maxlength="100">
            <?php if (!empty($errors['name'])): ?><small class="field-error"><?= e($errors['name']) ?></small><?php endif; ?>
        </label>
        <label>Email
            <input type="email" name="email" value="<?= e((string)old('email')) ?>" required>
            <?php if (!empty($errors['email'])): ?><small class="field-error"><?= e($errors['email']) ?></small><?php endif; ?>
        </label>
        <label>Password
            <input type="password" name="password" required minlength="8">
            <?php if (!empty($errors['password'])): ?><small class="field-error"><?= e($errors['password']) ?></small><?php endif; ?>
        </label>
        <button class="btn btn-primary btn-wide">Create account</button>
    </form>

    <p class="auth-link">Already have an account? <a href="/login">Login</a></p>
</div>

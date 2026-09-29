<?php
$editing = !empty($application['id']);
$values = $application ?: $_SESSION['old'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old'], $_SESSION['form_errors']);
?>
<div class="page-head">
    <div><div class="eyebrow"><?= $editing ? 'EDIT' : 'NEW APPLICATION' ?></div><h1><?= $editing ? 'Edit application' : 'Add an application' ?></h1></div>
    <a class="btn" href="<?= $editing ? '/applications/' . (int)$application['id'] : '/applications' ?>">Cancel</a>
</div>

<form method="post" class="panel form-grid" action="<?= $editing ? '/applications/' . (int)$application['id'] . '/edit' : '/applications/new' ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>Company name *
        <input name="company_name" required maxlength="150" value="<?= e((string)($values['company_name'] ?? '')) ?>">
        <?php if (!empty($errors['company_name'])): ?><small class="field-error"><?= e($errors['company_name']) ?></small><?php endif; ?>
    </label>
    <label>Job title *
        <input name="job_title" required maxlength="150" value="<?= e((string)($values['job_title'] ?? '')) ?>">
        <?php if (!empty($errors['job_title'])): ?><small class="field-error"><?= e($errors['job_title']) ?></small><?php endif; ?>
    </label>
    <label>Job URL
        <input type="url" name="job_url" value="<?= e((string)($values['job_url'] ?? '')) ?>" placeholder="https://...">
        <?php if (!empty($errors['job_url'])): ?><small class="field-error"><?= e($errors['job_url']) ?></small><?php endif; ?>
    </label>
    <label>Location
        <input name="location" maxlength="150" value="<?= e((string)($values['location'] ?? '')) ?>">
    </label>
    <label>Employment type
        <select name="employment_type"><?php foreach ($employmentTypes as $t): ?><option <?= ($values['employment_type'] ?? 'Full-time') === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select>
    </label>
    <label>Status
        <select name="status"><?php foreach ($statuses as $s): ?><option <?= ($values['status'] ?? 'Wishlist') === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
    </label>
    <label>Salary minimum
        <input type="number" step="0.01" min="0" name="salary_min" value="<?= e((string)($values['salary_min'] ?? '')) ?>">
    </label>
    <label>Salary maximum
        <input type="number" step="0.01" min="0" name="salary_max" value="<?= e((string)($values['salary_max'] ?? '')) ?>">
    </label>
    <label>Date applied
        <input type="date" name="date_applied" value="<?= e((string)($values['date_applied'] ?? '')) ?>">
    </label>
    <label>Follow-up date
        <input type="date" name="follow_up_date" value="<?= e((string)($values['follow_up_date'] ?? '')) ?>">
    </label>
    <label>Recruiter name
        <input name="recruiter_name" value="<?= e((string)($values['recruiter_name'] ?? '')) ?>">
    </label>
    <label>Recruiter email
        <input type="email" name="recruiter_email" value="<?= e((string)($values['recruiter_email'] ?? '')) ?>">
        <?php if (!empty($errors['recruiter_email'])): ?><small class="field-error"><?= e($errors['recruiter_email']) ?></small><?php endif; ?>
    </label>
    <label class="full">Notes
        <textarea name="notes" rows="6"><?= e((string)($values['notes'] ?? '')) ?></textarea>
    </label>

    <div class="form-actions full">
        <button class="btn btn-primary"><?= $editing ? 'Save changes' : 'Create application' ?></button>
    </div>
</form>

<?php
declare(strict_types=1);

const APPLICATION_STATUSES = [
    'Wishlist', 'Applied', 'Assessment', 'Interview', 'Offer', 'Rejected', 'Withdrawn'
];

const EMPLOYMENT_TYPES = [
    'Full-time', 'Part-time', 'Contract', 'Internship', 'Temporary', 'Other'
];

function validate_registration(array $input): array
{
    $errors = [];
    $name = trim((string)($input['name'] ?? ''));
    $email = trim((string)($input['email'] ?? ''));
    $password = (string)($input['password'] ?? '');

    if ($name === '' || mb_strlen($name) > 100) $errors['name'] = 'Name is required and must be under 100 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors['password'] = 'Password must contain at least 8 characters.';

    return $errors;
}

function validate_application(array $input): array
{
    $errors = [];
    $company = trim((string)($input['company_name'] ?? ''));
    $title = trim((string)($input['job_title'] ?? ''));
    $url = trim((string)($input['job_url'] ?? ''));
    $recruiterEmail = trim((string)($input['recruiter_email'] ?? ''));
    $status = (string)($input['status'] ?? 'Wishlist');
    $employment = (string)($input['employment_type'] ?? 'Full-time');

    if ($company === '' || mb_strlen($company) > 150) $errors['company_name'] = 'Company name is required.';
    if ($title === '' || mb_strlen($title) > 150) $errors['job_title'] = 'Job title is required.';
    if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) $errors['job_url'] = 'Enter a valid job URL.';
    if ($recruiterEmail !== '' && !filter_var($recruiterEmail, FILTER_VALIDATE_EMAIL)) $errors['recruiter_email'] = 'Enter a valid recruiter email.';
    if (!in_array($status, APPLICATION_STATUSES, true)) $errors['status'] = 'Invalid application status.';
    if (!in_array($employment, EMPLOYMENT_TYPES, true)) $errors['employment_type'] = 'Invalid employment type.';

    $min = $input['salary_min'] ?? '';
    $max = $input['salary_max'] ?? '';
    if ($min !== '' && (!is_numeric($min) || (float)$min < 0)) $errors['salary_min'] = 'Salary minimum must be a non-negative number.';
    if ($max !== '' && (!is_numeric($max) || (float)$max < 0)) $errors['salary_max'] = 'Salary maximum must be a non-negative number.';
    if ($min !== '' && $max !== '' && (float)$min > (float)$max) $errors['salary_max'] = 'Maximum salary cannot be below minimum salary.';

    foreach (['date_applied', 'follow_up_date'] as $dateField) {
        $value = trim((string)($input[$dateField] ?? ''));
        if ($value !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $value);
            if (!$d || $d->format('Y-m-d') !== $value) {
                $errors[$dateField] = 'Use a valid date.';
            }
        }
    }

    return $errors;
}

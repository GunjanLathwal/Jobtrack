# JobTrack

JobTrack is a Job Application Tracking SaaS built with **Core PHP 8+, PDO, MySQL, HTML/CSS and vanilla JavaScript**.

It demonstrates practical backend skills without relying on Laravel or another PHP framework.

## Features

- Session-based registration/login/logout
- Password hashing with `password_hash()` / `password_verify()`
- CSRF protection for server-rendered forms
- User-scoped application data
- Application CRUD
- Search, filters and sorting
- Application timeline/events
- Follow-up tracking
- Dashboard statistics
- Kanban pipeline
- Analytics
- REST-style JSON API
- PDO prepared statements
- Server-side validation
- Centralized error handling
- Responsive UI
- Git/GitHub-ready structure

## Stack

- PHP 8+
- MySQL 8+
- PDO
- HTML5
- CSS3
- Vanilla JavaScript
- Git

## Architecture

The project uses a small MVC-style separation:

- `models/` — database/data access
- `controllers/` — request/business flow
- `views/` — server-rendered HTML
- `api/` — JSON API routing
- `helpers/` — reusable security/validation/HTTP helpers
- `config/` — application bootstrap and local configuration
- `database/` — SQL schema
- `public/` — CSS/JavaScript
- `index.php` — front controller

This is deliberately lighter than a full framework. The goal is to show that the developer understands the PHP request lifecycle, sessions, PDO, SQL, validation and separation of concerns.

## Database setup

1. Create MySQL database/tables:

```sql
SOURCE database/schema.sql;
```

Or import `database/schema.sql` using phpMyAdmin/MySQL Workbench.

2. Copy:

```text
config/env.example.php
```

to:

```text
config/env.php
```

3. Put your local MySQL credentials in `config/env.php`.

Never commit `config/env.php`.

## Run locally

From the project root:

```bash
php -S localhost:8000
```

Open:

```text
http://localhost:8000
```

The application uses `index.php` as its front controller, so the PHP built-in server is enough for local development.

## API

All API endpoints require a logged-in session.

### GET /api/applications

Optional query parameters:

- `search`
- `status`
- `location`
- `employment_type`
- `sort`

### GET /api/applications/{id}

Returns the application plus its timeline events.

### POST /api/applications

Example JSON:

```json
{
  "company_name": "Acme",
  "job_title": "PHP Developer",
  "job_url": "https://example.com/job",
  "location": "Gurugram",
  "employment_type": "Full-time",
  "salary_min": 500000,
  "salary_max": 800000,
  "date_applied": "2026-09-28",
  "status": "Applied",
  "recruiter_name": "Jane",
  "recruiter_email": "jane@example.com",
  "notes": "Applied through careers page.",
  "follow_up_date": "2026-10-03"
}
```

### PUT /api/applications/{id}

Replace/update an application using the same validation rules as creation.

### DELETE /api/applications/{id}

Deletes an application belonging to the authenticated user.

### PATCH /api/applications/{id}/status

Example:

```json
{
  "status": "Interview"
}
```

### GET /api/dashboard/stats

Returns dashboard metrics calculated from MySQL.

## HTTP status conventions

- `200` success
- `201` resource created
- `400` malformed/invalid request
- `401` unauthenticated
- `404` resource not found
- `422` validation failure
- `500` unexpected server error

## Security notes

- Passwords are never stored directly.
- PDO uses native prepared statements.
- User IDs are taken from the authenticated session, not request parameters.
- Output is escaped using `htmlspecialchars`.
- CSRF tokens protect state-changing HTML forms.
- Session ID is regenerated after login.
- Cookies use HttpOnly and SameSite attributes.
- SQL errors are logged server-side instead of exposed through the UI/API.
- `config/env.php` is ignored by Git.

## Git strategy

Suggested commits:

```text
Initial project setup
Add database schema
Implement authentication
Add application CRUD
Add application timeline
Add dashboard
Add search and filters
Add follow-up tracking
Add Kanban board
Add analytics
Add REST API
Improve validation and security
Improve responsive UI
Add documentation
```

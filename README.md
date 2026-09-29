# JobTrack

JobTrack is a portfolio-quality Job Application Tracking SaaS built with **Core PHP 8+, PDO, MySQL, HTML/CSS and vanilla JavaScript**.

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

## Screenshots

Add screenshots here before publishing:

- Dashboard
- Applications table
- Application detail/timeline
- Kanban board
- Analytics
- Login/register

## Interview talking points

### 60-second explanation

"JobTrack is a Core PHP and MySQL job application tracking SaaS I built to practice production-style backend development without a framework. Users can register and log in securely, then manage their job applications, application stages, recruiter information, follow-up dates and interview timeline. I used PHP sessions for authentication, PDO with prepared statements for database access, server-side validation and CSRF protection for security. The dashboard and analytics are backed by SQL aggregation rather than hard-coded values. I also exposed the main application resources through REST-style JSON endpoints."

### Why PHP?

PHP is well suited to server-rendered web applications and has mature support for sessions, HTTP request handling, MySQL and database access through PDO. For this project, Core PHP makes the request lifecycle explicit instead of hiding it behind a framework.

### Why MySQL?

The data is relational: users own applications, and applications own timeline events. MySQL gives us foreign keys, indexes, transactions and aggregation queries that map naturally to this domain.

### Why PDO?

PDO gives PHP a consistent database abstraction and supports prepared statements. Prepared statements keep user-controlled values separate from SQL instructions, reducing SQL injection risk.

### Authentication flow

1. User submits login form.
2. Server looks up the user by email using a prepared statement.
3. `password_verify()` compares the submitted password with the stored hash.
4. On success, PHP regenerates the session ID and stores the user ID in the session.
5. Protected routes call `require_auth()`.
6. Controllers use that authenticated user ID when querying data.

### Biggest technical challenge

A good interview answer is the ownership boundary: every application query must be scoped by the authenticated `user_id`. That prevents one user from requesting another user's application simply by changing an ID in a URL.

### Future improvements

- Pagination for large application lists
- Rate limiting for login/API endpoints
- Email reminders for follow-ups
- File attachments for resumes/offer letters
- Audit log
- Soft deletes
- Automated tests with PHPUnit
- API tokens for third-party clients
- Docker-based development
- Deployment behind Nginx + PHP-FPM
- Background jobs/queues for notifications

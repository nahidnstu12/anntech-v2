# Application error log (super admin)

## Purpose

When something breaks in production, super admins can open **Admin → Error log** to see persisted server errors: message, exception class, file/line, stack trace, correlated **request ID** (`X-Request-Id` on responses), URL, user (if authenticated), and redacted request context.

## How it works

1. `AssignRequestCorrelationId` middleware assigns or accepts `X-Request-Id` on web + API requests.
2. `ApplicationErrorRecorder` runs on every reported exception via `bootstrap/app.php`.
3. Records are stored in `application_error_logs` when:
   - `ERROR_LOG_DATABASE=true` (default)
   - Exception is not in the ignore list (auth, validation, 404, etc.)
   - HTTP status is **500+** (4xx HTTP exceptions are skipped)

Passwords and tokens in request bodies are redacted per `config/error_log.php`.

## API (super admin only)

| Method | Path |
|--------|------|
| GET | `/api/admin/error-logs` — paginated list (filters: `search`, `request_id`, `from`, `to`) |
| GET | `/api/admin/error-logs/{id}` — full trace + context |

## Operations

- Match user reports to logs using **Request ID** from browser devtools or response headers.
- Disable DB persistence with `ERROR_LOG_DATABASE=false` (Laravel log channel still applies).
- Run `php artisan migrate` to create the table.

# JobTrack architecture notes

## Request lifecycle

Browser
→ HTTP request
→ `index.php` front controller
→ `config/bootstrap.php`
→ route/controller
→ model
→ PDO
→ MySQL
→ model result
→ controller
→ view
→ HTML response

For API requests:

Browser/HTTP client
→ `index.php`
→ `api/index.php`
→ authentication check
→ model
→ PDO/MySQL
→ JSON response

## Why not put everything in index.php?

A single file can work for a tiny demo, but JobTrack separates responsibilities so each part has one main job. Controllers coordinate application flow; models handle database access; views render UI.

## PHP-specific concepts demonstrated

### `declare(strict_types=1)`

Requests stricter scalar type behavior in PHP files and makes function signatures easier to reason about.

### Constructor property promotion

```php
public function __construct(private PDO $db) {}
```

PHP creates the private property and assigns the constructor argument automatically.

### `?PDO`

The question mark makes a type nullable. In this project it is used for the singleton's optional connection.

### `match`

`match` is an expression useful for selecting a value from a finite set. It is used for sorting logic.

### `Throwable`

The top-level exception handler catches both `Exception` and `Error` types.

## OOP mapping

- `Database` encapsulates connection creation.
- `User`, `Application`, `ApplicationEvent` encapsulate database operations.
- Controllers encapsulate request/business flow.
- Helpers provide stateless reusable functions.

Inheritance is intentionally not forced into the design. There is no meaningful "is-a" hierarchy that would improve this small application. Interfaces would become useful if we introduced interchangeable services, such as multiple notification providers.

## SQL injection

Bad:

```php
$sql = "SELECT * FROM applications WHERE company_name = '$company'";
```

The input changes the SQL string itself.

JobTrack instead uses:

```php
$stmt = $pdo->prepare(
    'SELECT * FROM applications WHERE user_id = :user_id'
);

$stmt->execute([
    'user_id' => $userId
]);
```

The SQL structure and the parameter value are handled separately by PDO.

## User ownership

The important pattern is:

```sql
WHERE id = :id AND user_id = :user_id
```

The application ID alone is never treated as sufficient authorization.

## Analytics

The database performs aggregation using `COUNT`, `SUM`, `GROUP BY` and date functions. PHP receives already-aggregated values and formats them for presentation.

## API design

The API returns a stable envelope:

```json
{
  "success": true,
  "data": {}
}
```

or:

```json
{
  "success": false,
  "data": null,
  "message": "Application not found."
}
```

This makes client-side handling predictable.

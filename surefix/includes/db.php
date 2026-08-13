<?php
/**
 * db() — thin accessor for the shared PDO connection config.php already
 * opens as $pdo. Exists because the admin panel was originally written
 * against a db()-style helper; rather than rewrite every admin query, this
 * just bridges to the one real connection instead of duplicating it.
 */
require_once __DIR__ . '/config.php';

function db(): PDO {
    global $pdo;
    if (!$pdo instanceof PDO) {
        http_response_code(500);
        die('Database connection is not available. Check includes/config.php (DB_HOST/DB_NAME/DB_USER/DB_PASS) and that MySQL is running.');
    }
    return $pdo;
}

<?php
/**
 * Site-wide configuration: DB connection + constants used across every page.
 * Included by header.php, so it's automatically available everywhere.
 */

// ---------------------------------------------------------------------------
// Site constants
// ---------------------------------------------------------------------------

// Base URL used for all asset/nav links so pages work regardless of nesting
// depth (e.g. a future /blog/post-slug.php still resolves assets correctly).
// Local XAMPP dev path shown below — update when deploying to production
// (e.g. 'https://surefix.in', no trailing slash).
define('SITE_URL', '/shahid_sites/surefix');

define('SITE_NAME', 'Sure Fix');
define('SITE_PHONE', '+91 1800-456-789');
define('SITE_PHONE_LINK', '+911800456789'); // digits-only, for tel: links
define('SITE_EMAIL', 'contact@surefix.in');
define('SITE_ADDRESS', '221, 100 Feet Road, Indiranagar, Bengaluru, Karnataka 560038');
define('SITE_CITY', 'Bengaluru');

// Used by the Step 2 location picker in the booking flow (Google Maps JS API
// + Geocoding). Restrict this key to your production domain's HTTP referrers
// in Google Cloud Console before going live — client-side keys are public.
define('GOOGLE_MAPS_API_KEY', 'AIzaSyC8c9LSZsKAveYSZq0sqheXyYR4SO5Rm3s');

// ---------------------------------------------------------------------------
// Database connection (PDO)
// Not wired to real leads/blog data yet — Phase 2/3 per todo.md. Left as a
// working pattern so future includes/db-backed pages can just `require` this
// file and use $pdo directly. Wrapped so pages that don't need the DB yet
// (every page right now) don't fail if MySQL isn't running locally.
// ---------------------------------------------------------------------------

define('DB_HOST', 'localhost');
define('DB_NAME', 'surefix');
define('DB_USER', 'root');
define('DB_PASS', '');

$pdo = null;
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // Intentionally silent for now: no page currently reads/writes the DB.
    // Once Phase 2 (booking flow) or Phase 3 (admin) lands, code using $pdo
    // should check `if (!$pdo)` and fail loudly instead of assuming it's set.
    $pdo = null;
}

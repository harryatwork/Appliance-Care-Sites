<?php
/**
 * Site-wide configuration: DB connection + constants used across every page.
 * Included by header.php, so it's automatically available everywhere.
 */

// ---------------------------------------------------------------------------
// Site constants
// ---------------------------------------------------------------------------

// Base URL used for all asset/nav links, derived from this file's fixed
// location relative to the web server's document root — works unchanged on
// XAMPP (/shahid_sites/homesure), on the live host (/homesure), at a domain
// root with no subfolder, and for any future page nested in a subfolder
// (e.g. admin/) since it's anchored to config.php, not to the calling page.
$siteRoot = str_replace('\\', '/', dirname(__DIR__));
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')) : '';
define('SITE_URL', ($docRoot && strpos($siteRoot, $docRoot) === 0) ? substr($siteRoot, strlen($docRoot)) : '');

define('SITE_NAME', 'Home Sure');
define('SITE_PHONE', '+91 855-0000-423'); // same number used for WhatsApp & Call — shared with the Sure Fix site, per client's confirmation
define('SITE_PHONE_LINK', '+918550000423'); // digits-only, for tel:/wa.me links
define('SITE_EMAIL', 'care@home-sure.in'); // placeholder domain pattern mirroring Sure Fix's (care@sure-fix.in) — confirm the real inbox/domain exists before launch
define('SITE_ADDRESS', '221, 100 Feet Road, Indiranagar, Bengaluru, Karnataka 560038'); // same address as the Sure Fix site, per client's confirmation
define('SITE_CITY', 'Bengaluru');

// PLACEHOLDER — these accounts don't necessarily exist yet. Swap for the
// real Home Sure social URLs before launch (client confirmed "placeholder
// for now" rather than reusing Sure Fix's actual accounts).
define('SITE_INSTAGRAM_URL', 'https://www.instagram.com/myhomesure');
define('SITE_FACEBOOK_URL', 'https://www.facebook.com/myhomesure');
define('SITE_X_URL', 'https://x.com/myhomesure');

// Used by the Step 2 location picker in the booking flow (Google Maps JS API
// + Geocoding). Reusing the same key as the Sure Fix site for now (same
// Google Cloud project) — but that key is now referrer-restricted to
// sure-fix.in only, so once Home Sure has its own live domain, add it to
// this key's HTTP referrer allowlist in Google Cloud Console (or issue a
// separate key), otherwise Maps/autocomplete will silently fail on the
// Home Sure domain the same way it did on Sure Fix before that was fixed.
define('GOOGLE_MAPS_API_KEY', 'AIzaSyC8c9LSZsKAveYSZq0sqheXyYR4SO5Rm3s');

// ---------------------------------------------------------------------------
// Database connection (PDO)
// Not wired to real leads/blog data yet — Phase 2/3 per todo.md. Left as a
// working pattern so future includes/db-backed pages can just `require` this
// file and use $pdo directly. Wrapped so pages that don't need the DB yet
// (every page right now) don't fail if MySQL isn't running locally.
// ---------------------------------------------------------------------------

define('DB_HOST', 'localhost');
define('DB_NAME', 'homesure');
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

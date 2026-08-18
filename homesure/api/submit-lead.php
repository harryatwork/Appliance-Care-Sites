<?php
/**
 * Handles the homepage Quick Enquiry mini-form and the Contact page form.
 * Both are simple name/mobile(/email/message) leads — distinguished by
 * `source` ('quick_enquiry' | 'contact') so admin/leads.php can tell them
 * apart. Accepts JSON or regular form-encoded POST bodies.
 */
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

function respond(bool $ok, array $data = []): never {
    http_response_code($ok ? 200 : 400);
    echo json_encode(array_merge(['success' => $ok], $data));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, ['error' => 'Method not allowed']);
}

$raw = file_get_contents('php://input');
$json = json_decode($raw, true);
$input = is_array($json) ? $json : $_POST;

// Honeypot: a field named "website" that's hidden from real users via CSS.
// Bots that fill every field will trip it; pretend success so they don't
// learn to avoid it, but don't actually write a row.
if (!empty($input['website'])) {
    respond(true);
}

$source  = in_array($input['source'] ?? '', ['quick_enquiry', 'contact'], true) ? $input['source'] : 'quick_enquiry';
$name    = trim((string)($input['name'] ?? ''));
$mobile  = preg_replace('/\D/', '', (string)($input['mobile'] ?? ''));
$email   = trim((string)($input['email'] ?? ''));
$message = trim((string)($input['message'] ?? ''));

if ($name === '' || mb_strlen($name) > 120) {
    respond(false, ['error' => 'Please enter your name.']);
}
if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
    respond(false, ['error' => 'Please enter a valid 10-digit mobile number.']);
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, ['error' => 'Please enter a valid email address.']);
}

try {
    $stmt = db()->prepare(
        'INSERT INTO leads (type, name, mobile, email, message, status, ip_address) VALUES (?,?,?,?,?,?,?)'
    );
    $stmt->execute([$source, $name, $mobile, $email ?: null, $message ?: null, 'New', $_SERVER['REMOTE_ADDR'] ?? null]);
    respond(true, ['id' => (int)db()->lastInsertId()]);
} catch (Exception $e) {
    respond(false, ['error' => 'Something went wrong on our end. Please call or WhatsApp us directly.']);
}

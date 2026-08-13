<?php
/**
 * Handles the appliance-page booking flow's final "Confirm Booking" step
 * (assets/js/booking-flow.js confirmBooking()). Generates the real Booking
 * ID here — the JS side no longer fabricates one client-side.
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
$input = json_decode($raw, true);
if (!is_array($input)) {
    respond(false, ['error' => 'Invalid request']);
}

// Honeypot — see api/submit-lead.php for why this is silent-success.
if (!empty($input['website'])) {
    respond(true, ['bookingId' => 'SF' . date('ymd') . '0000']);
}

$service       = trim((string)($input['service'] ?? ''));
$applianceType = trim((string)($input['applianceType'] ?? ''));
$brand         = trim((string)($input['brand'] ?? ''));
$problems      = is_array($input['problems'] ?? null) ? $input['problems'] : [];
$problem       = implode(', ', array_filter(array_map('trim', $problems)));
$address       = trim((string)($input['address'] ?? ''));
$landmark      = trim((string)($input['landmark'] ?? ''));
$area          = trim((string)($input['area'] ?? '')); // Home/Work/Other address label
$notes         = trim((string)($input['notes'] ?? ''));
$lat           = is_numeric($input['lat'] ?? null) ? (float)$input['lat'] : null;
$lng           = is_numeric($input['lng'] ?? null) ? (float)$input['lng'] : null;
$emergency     = !empty($input['emergency']);
$date          = trim((string)($input['date'] ?? ''));
$slot          = trim((string)($input['slot'] ?? ''));
$name          = trim((string)($input['name'] ?? ''));
$mobile        = preg_replace('/\D/', '', (string)($input['mobile'] ?? ''));
$email         = trim((string)($input['email'] ?? ''));

if ($name === '' || mb_strlen($name) > 120) {
    respond(false, ['error' => 'Please enter your name.']);
}
if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
    respond(false, ['error' => 'Please enter a valid 10-digit mobile number.']);
}
if ($address === '') {
    respond(false, ['error' => 'Please enter your address.']);
}
if (!$emergency && ($date === '' || $slot === '')) {
    respond(false, ['error' => 'Please select a date and time slot, or choose Emergency.']);
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, ['error' => 'Please enter a valid email address.']);
}

$preferredDate = ($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) ? $date : null;

try {
    // Unique, human-friendly booking ID: SF + yymmdd + 4 random hex chars.
    do {
        $bookingId = 'SF' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        $check = db()->prepare('SELECT id FROM leads WHERE booking_id = ?');
        $check->execute([$bookingId]);
    } while ($check->fetch());

    $stmt = db()->prepare(
        'INSERT INTO leads
            (booking_id, type, name, mobile, email, service, appliance_type, brand, problem,
             address, landmark, area, lat, lng, preferred_date, preferred_slot, is_emergency,
             notes, status, ip_address)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );
    $stmt->execute([
        $bookingId, 'booking', $name, $mobile, $email ?: null, $service ?: null, $applianceType ?: null, $brand ?: null,
        $problem ?: null, $address, $landmark ?: null, $area ?: null, $lat, $lng, $preferredDate,
        $emergency ? null : ($slot ?: null), $emergency ? 1 : 0, $notes ?: null, 'New', $_SERVER['REMOTE_ADDR'] ?? null,
    ]);

    respond(true, ['bookingId' => $bookingId]);
} catch (Exception $e) {
    respond(false, ['error' => 'Something went wrong while confirming your booking. Please call or WhatsApp us directly and we\'ll book it manually.']);
}

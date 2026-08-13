<?php
require_once '_auth.php';

$STATUSES = ['New', 'Assigned', 'In Progress', 'Completed', 'Cancelled'];

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: leads.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $pid = (int)$_POST['id'];
    if ($_POST['action'] === 'set_status' && in_array($_POST['status'] ?? '', $STATUSES, true)) {
        db()->prepare('UPDATE leads SET status = ? WHERE id = ?')->execute([$_POST['status'], $pid]);
    } elseif ($_POST['action'] === 'delete') {
        db()->prepare('DELETE FROM leads WHERE id = ?')->execute([$pid]);
        header('Location: leads.php');
        exit;
    }
    header('Location: lead-view.php?id=' . $pid);
    exit;
}

$stmt = db()->prepare('SELECT * FROM leads WHERE id = ?');
$stmt->execute([$id]);
$lead = $stmt->fetch();
if (!$lead) { header('Location: leads.php'); exit; }

function statusClass(string $s): string { return 'status-' . str_replace(' ', '-', $s); }
function copyRow(string $label, ?string $value): string {
    if ($value === null || $value === '') return '<div class="detail-row"><strong>' . htmlspecialchars($label) . '</strong><span style="color:#94a3b8">—</span></div>';
    $safe = htmlspecialchars($value);
    return '<div class="detail-row"><strong>' . htmlspecialchars($label) . '</strong><span class="copy-field">' . nl2br($safe) . ' <button type="button" class="copy-btn" data-copy="' . htmlspecialchars($value, ENT_QUOTES) . '" title="Copy"><i class="fa-regular fa-copy"></i></button></span></div>';
}

$PAGE_TITLE = htmlspecialchars($lead['name']) . ($lead['booking_id'] ? ' — ' . $lead['booking_id'] : '');
$ACTIVE_NAV = 'leads';
include '_header.php';
?>

<div style="margin-bottom:16px">
  <a href="leads.php" class="btn btn--secondary btn--sm">
    <i class="fa-solid fa-arrow-left"></i> Back to Leads
  </a>
</div>

<div class="card" style="max-width:720px">
  <div class="card__head">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <span class="badge-<?= htmlspecialchars($lead['type']) ?>"><?= $lead['type'] === 'quick_enquiry' ? 'Quick Enquiry' : ucfirst($lead['type']) ?></span>
      <h2><?= htmlspecialchars($lead['name']) ?></h2>
    </div>
    <form method="POST">
      <input type="hidden" name="id" value="<?= $lead['id'] ?>">
      <input type="hidden" name="action" value="set_status">
      <select name="status" class="status-pill <?= statusClass($lead['status']) ?>" onchange="this.form.submit()">
        <?php foreach ($STATUSES as $s): ?>
          <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <div class="detail-grid">
    <?php
    echo copyRow('Booking ID', $lead['booking_id']);
    echo copyRow('Customer Name', $lead['name']);
    echo copyRow('Mobile Number', $lead['mobile']);
    echo copyRow('Email', $lead['email']);
    echo copyRow('Service', $lead['service']);
    echo copyRow('Appliance Type', $lead['appliance_type']);
    echo copyRow('Brand', $lead['brand']);
    echo copyRow('Problem', $lead['problem']);
    echo copyRow('Address', trim(($lead['address'] ?? '') . ($lead['landmark'] ? ', ' . $lead['landmark'] : '')));
    echo copyRow('Area', $lead['area']);
    if ($lead['lat'] && $lead['lng']) {
        $mapUrl = 'https://www.google.com/maps?q=' . $lead['lat'] . ',' . $lead['lng'];
        echo '<div class="detail-row"><strong>Location Pin</strong><span class="copy-field">' . htmlspecialchars($lead['lat'] . ', ' . $lead['lng']) . ' <a href="' . htmlspecialchars($mapUrl) . '" target="_blank" rel="noopener" class="copy-btn" title="Open in Google Maps"><i class="fa-solid fa-location-dot"></i></a></span></div>';
    } else {
        echo copyRow('Location Pin', null);
    }
    $slotText = trim(($lead['is_emergency'] ? 'Emergency — ASAP' : '') . ($lead['preferred_date'] ? ' ' . date('d M Y', strtotime($lead['preferred_date'])) : '') . ($lead['preferred_slot'] ? ', ' . $lead['preferred_slot'] : ''));
    echo copyRow('Preferred Slot', $slotText ?: null);
    echo copyRow('Notes', $lead['notes']);
    echo copyRow('Message', $lead['message']);
    echo copyRow('Date Created', date('d M Y, h:i A', strtotime($lead['created_at'])));
    ?>
  </div>

  <!-- Action buttons -->
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px;padding-top:16px;border-top:1px solid #f1f5f9">
    <a href="tel:<?= htmlspecialchars($lead['mobile']) ?>" class="btn btn--sm btn--secondary">
      <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="https://wa.me/91<?= htmlspecialchars(preg_replace('/\D/', '', $lead['mobile'])) ?>" target="_blank" rel="noopener" class="btn btn--sm btn--success">
      <i class="fa-brands fa-whatsapp"></i> WhatsApp
    </a>

    <form method="POST" action="lead-view.php?id=<?= $lead['id'] ?>" style="display:inline;margin-left:auto"
          onsubmit="return confirm('Delete this lead permanently?')">
      <input type="hidden" name="id" value="<?= $lead['id'] ?>">
      <input type="hidden" name="action" value="delete">
      <button type="submit" class="btn btn--sm btn--danger">
        <i class="fa-solid fa-trash"></i> Delete
      </button>
    </form>
  </div>
</div>

<?php include '_footer.php'; ?>

<?php
require_once '_auth.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: leads.php'); exit; }

$stmt = db()->prepare('SELECT * FROM leads WHERE id = ?');
$stmt->execute([$id]);
$lead = $stmt->fetch();
if (!$lead) { header('Location: leads.php'); exit; }

// Auto-mark as read when viewed
if ($lead['status'] === 'new') {
    db()->prepare('UPDATE leads SET status = ? WHERE id = ?')->execute(['read', $id]);
    $lead['status'] = 'read';
}

$PAGE_TITLE = ucfirst($lead['type']) . ' — ' . htmlspecialchars($lead['name']);
$ACTIVE_NAV = 'leads';
include '_header.php';
?>

<div style="margin-bottom:16px">
  <a href="leads.php" class="btn btn--secondary btn--sm">
    <i class="fa-solid fa-arrow-left"></i> Back to Leads
  </a>
</div>

<div class="card" style="max-width:680px">
  <div class="card__head">
    <div style="display:flex;align-items:center;gap:10px">
      <span class="badge-<?= $lead['type'] ?>"><?= ucfirst($lead['type']) ?></span>
      <h2><?= htmlspecialchars($lead['name']) ?></h2>
    </div>
    <span class="badge-<?= $lead['status'] ?>"><?= ucfirst($lead['status']) ?></span>
  </div>

  <div class="detail-grid">
    <?php
    $fields = [];
    $fields['Reference']    = $lead['ref'] ?: '—';
    $fields['Name']         = $lead['name'];
    $fields['Phone']        = $lead['phone'] ?: '—';
    $fields['Email']        = $lead['email'] ?: '—';
    if ($lead['type'] === 'contact') {
        $fields['Subject']  = $lead['subject']  ?: '—';
        $fields['Message']  = $lead['message']  ?: '—';
    }
    if ($lead['type'] === 'booking') {
        $fields['Service']       = $lead['service']      ?: '—';
        $fields['Booking Date']  = $lead['booking_date'] ? date('d M Y', strtotime($lead['booking_date'])) : '—';
        $fields['Booking Time']  = $lead['booking_time'] ?: '—';
        $fields['Address']       = $lead['address']      ?: '—';
        $fields['Notes']         = $lead['notes']        ?: '—';
    }
    $fields['Received']     = date('d M Y, h:i A', strtotime($lead['created_at']));
    $fields['IP Address']   = $lead['ip'] ?: '—';

    foreach ($fields as $k => $v): ?>
    <div class="detail-row">
      <strong><?= $k ?></strong>
      <span><?= nl2br(htmlspecialchars($v)) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Action buttons -->
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px;padding-top:16px;border-top:1px solid #f1f5f9">
    <?php if ($lead['phone']): ?>
    <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="btn btn--sm btn--secondary">
      <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="https://wa.me/<?= preg_replace('/\D/', '', $lead['phone']) ?>" target="_blank" class="btn btn--sm btn--success">
      <i class="fa-brands fa-whatsapp"></i> WhatsApp
    </a>
    <?php endif; ?>

    <?php if ($lead['status'] !== 'done'): ?>
    <form method="POST" action="leads.php" style="display:inline">
      <input type="hidden" name="id"     value="<?= $lead['id'] ?>">
      <input type="hidden" name="action" value="done">
      <button type="submit" class="btn btn--sm btn--primary">
        <i class="fa-solid fa-check"></i> Mark as Done
      </button>
    </form>
    <?php else: ?>
    <form method="POST" action="leads.php" style="display:inline">
      <input type="hidden" name="id"     value="<?= $lead['id'] ?>">
      <input type="hidden" name="action" value="new">
      <button type="submit" class="btn btn--sm btn--warning">
        <i class="fa-solid fa-rotate-left"></i> Reopen
      </button>
    </form>
    <?php endif; ?>

    <form method="POST" action="leads.php" style="display:inline"
          onsubmit="return confirm('Delete this lead permanently?')">
      <input type="hidden" name="id"     value="<?= $lead['id'] ?>">
      <input type="hidden" name="action" value="delete">
      <button type="submit" class="btn btn--sm btn--danger">
        <i class="fa-solid fa-trash"></i> Delete
      </button>
    </form>
  </div>
</div>

<?php include '_footer.php'; ?>

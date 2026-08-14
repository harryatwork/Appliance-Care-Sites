<?php
require_once '_auth.php';

$PAGE_TITLE = 'Leads';
$ACTIVE_NAV = 'leads';

$STATUSES = ['New', 'Assigned', 'In Progress', 'Completed', 'Cancelled'];

// ── Handle status/delete actions ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id     = (int)$_POST['id'];
    $action = $_POST['action'];
    $back   = 'leads.php?' . http_build_query(array_filter([
        'date'       => $_POST['date_filter']       ?? '',
        'service'    => $_POST['service_filter']    ?? '',
        'status'     => $_POST['status_filter']     ?? '',
        'technician' => $_POST['technician_filter']  ?? '',
        'search'     => $_POST['search_filter']     ?? '',
        'page'       => $_POST['page_num']          ?? '',
    ], fn($v) => $v !== ''));

    if ($action === 'delete') {
        db()->prepare('DELETE FROM leads WHERE id = ?')->execute([$id]);
    } elseif ($action === 'set_status' && in_array($_POST['status'] ?? '', $STATUSES, true)) {
        db()->prepare('UPDATE leads SET status = ? WHERE id = ?')->execute([$_POST['status'], $id]);
    }
    header('Location: ' . $back);
    exit;
}

// ── Filters & Pagination ──────────────────────────────────────────────────────
$date       = $_GET['date']       ?? '';
$service    = $_GET['service']    ?? 'all';
$status     = $_GET['status']     ?? 'all';
$technician = $_GET['technician'] ?? 'all';
$search     = trim($_GET['search'] ?? '');
$page       = max(1, (int)($_GET['page'] ?? 1));
$per        = 20;
$offset     = ($page - 1) * $per;

$conditions = [];
$params     = [];
if ($date !== '')            { $conditions[] = 'DATE(created_at) = ?'; $params[] = $date; }
if ($service !== 'all' && $service !== '') { $conditions[] = 'service = ?'; $params[] = $service; }
if ($status !== 'all')       { $conditions[] = 'status = ?'; $params[] = $status; }
if ($technician === 'unassigned') {
    $conditions[] = "(technician_name IS NULL OR technician_name = '')";
} elseif ($technician !== 'all' && $technician !== '') {
    $conditions[] = 'technician_name = ?'; $params[] = $technician;
}
if ($search !== '') {
    $conditions[] = '(name LIKE ? OR mobile LIKE ? OR booking_id LIKE ?)';
    $like = '%' . $search . '%';
    array_push($params, $like, $like, $like);
}
$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$cntStmt = db()->prepare("SELECT COUNT(*) FROM leads $where");
$cntStmt->execute($params);
$total = (int)$cntStmt->fetchColumn();

$stmt = db()->prepare("SELECT * FROM leads $where ORDER BY created_at DESC LIMIT $per OFFSET $offset");
$stmt->execute($params);
$leads = $stmt->fetchAll();

$services     = db()->query("SELECT DISTINCT service FROM leads WHERE service IS NOT NULL AND service <> '' ORDER BY service ASC")->fetchAll(PDO::FETCH_COLUMN);
$technicians  = db()->query("SELECT DISTINCT technician_name FROM leads WHERE technician_name IS NOT NULL AND technician_name <> '' ORDER BY technician_name ASC")->fetchAll(PDO::FETCH_COLUMN);

// ── Stats ─────────────────────────────────────────────────────────────────────
$stats = db()->query(
    "SELECT COUNT(*) AS total,
            SUM(status='New') AS new_count,
            SUM(status IN ('Assigned','In Progress')) AS active_count,
            SUM(status='Completed') AS completed_count
     FROM leads"
)->fetch();

$totalPages = (int)ceil($total / $per);

function leadsUrl(array $override = []): string {
    global $date, $service, $status, $technician, $search, $page;
    $base = ['date' => $date, 'service' => $service, 'status' => $status, 'technician' => $technician, 'search' => $search, 'page' => $page];
    return 'leads.php?' . http_build_query(array_filter(array_merge($base, $override), fn($v) => $v !== '' && $v !== 'all'));
}
function statusClass(string $s): string { return 'status-' . str_replace(' ', '-', $s); }

include '_header.php';
?>

<!-- Stat Cards -->
<div class="stat-cards">
  <div class="stat-card">
    <div class="stat-card__label">Total Leads</div>
    <div class="stat-card__val"><?= (int)$stats['total'] ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label" style="color:#1d4ed8">New</div>
    <div class="stat-card__val" style="color:#1d4ed8"><?= (int)$stats['new_count'] ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label" style="color:#c2410c">Assigned / In Progress</div>
    <div class="stat-card__val" style="color:#c2410c"><?= (int)$stats['active_count'] ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label" style="color:#15803d">Completed</div>
    <div class="stat-card__val" style="color:#15803d"><?= (int)$stats['completed_count'] ?></div>
  </div>
</div>

<!-- Filters -->
<form method="GET" class="card" style="padding:16px 18px;margin-bottom:18px">
  <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <div class="form-group" style="margin-bottom:0;min-width:150px">
      <label for="f-date">Date</label>
      <input type="date" id="f-date" name="date" value="<?= htmlspecialchars($date) ?>">
    </div>
    <div class="form-group" style="margin-bottom:0;min-width:170px">
      <label for="f-service">Service</label>
      <select id="f-service" name="service">
        <option value="all">All Services</option>
        <?php foreach ($services as $svc): ?>
          <option value="<?= htmlspecialchars($svc) ?>" <?= $service === $svc ? 'selected' : '' ?>><?= htmlspecialchars($svc) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin-bottom:0;min-width:160px">
      <label for="f-status">Status</label>
      <select id="f-status" name="status">
        <option value="all">All Status</option>
        <?php foreach ($STATUSES as $s): ?>
          <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin-bottom:0;min-width:170px">
      <label for="f-technician">Technician</label>
      <select id="f-technician" name="technician">
        <option value="all">All Technicians</option>
        <option value="unassigned" <?= $technician === 'unassigned' ? 'selected' : '' ?>>Unassigned</option>
        <?php foreach ($technicians as $tech): ?>
          <option value="<?= htmlspecialchars($tech) ?>" <?= $technician === $tech ? 'selected' : '' ?>><?= htmlspecialchars($tech) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin-bottom:0;flex:1;min-width:180px">
      <label for="f-search">Search <span style="font-weight:400;color:#94a3b8">(name, mobile, booking ID)</span></label>
      <input type="text" id="f-search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search leads…">
    </div>
    <div style="display:flex;gap:8px">
      <button type="submit" class="btn btn--primary btn--sm"><i class="fa-solid fa-filter"></i> Filter</button>
      <?php if ($date || ($service !== 'all') || ($status !== 'all') || ($technician !== 'all') || $search): ?>
        <a href="leads.php" class="btn btn--secondary btn--sm">Clear</a>
      <?php endif; ?>
    </div>
  </div>
</form>

<!-- Table -->
<div class="card" style="padding:0">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Name</th>
          <th>Mobile</th>
          <th>Service</th>
          <th>Technician</th>
          <th>Booked On</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leads)): ?>
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              <p>No leads found for this filter.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($leads as $lead): ?>
        <tr>
          <td data-label="Booking ID">
            <?php if ($lead['booking_id']): ?>
              <a href="lead-view.php?id=<?= $lead['id'] ?>" style="font-weight:700;color:#0f172a;text-decoration:none"><?= htmlspecialchars($lead['booking_id']) ?></a>
            <?php else: ?>
              <span class="badge-<?= htmlspecialchars($lead['type']) ?>"><?= $lead['type'] === 'quick_enquiry' ? 'Quick Enquiry' : ucfirst($lead['type']) ?></span>
            <?php endif; ?>
          </td>
          <td data-label="Name">
            <span class="copy-field">
              <a href="lead-view.php?id=<?= $lead['id'] ?>" style="color:#0f172a;text-decoration:none;font-weight:600"><?= htmlspecialchars($lead['name']) ?></a>
              <button type="button" class="copy-btn" data-copy="<?= htmlspecialchars($lead['name']) ?>" title="Copy name"><i class="fa-regular fa-copy"></i></button>
            </span>
          </td>
          <td data-label="Mobile" style="white-space:nowrap">
            <span class="copy-field">
              <?= htmlspecialchars($lead['mobile']) ?>
              <button type="button" class="copy-btn" data-copy="<?= htmlspecialchars($lead['mobile']) ?>" title="Copy"><i class="fa-regular fa-copy"></i></button>
            </span>
          </td>
          <td data-label="Service" style="max-width:180px">
            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#64748b">
              <?= htmlspecialchars($lead['service'] ?: '—') ?>
            </span>
          </td>
          <td data-label="Technician">
            <?php if (!empty($lead['technician_name'])): ?>
              <span class="tech-tag"><i class="fa-solid fa-user-gear"></i> <?= htmlspecialchars($lead['technician_name']) ?></span>
            <?php else: ?>
              <span class="tech-tag tech-tag--unassigned">Unassigned</span>
            <?php endif; ?>
          </td>
          <td data-label="Booked On" style="white-space:nowrap;color:#64748b;font-size:.8rem">
            <?= date('d M Y', strtotime($lead['created_at'])) ?><br>
            <?= date('h:i A', strtotime($lead['created_at'])) ?>
          </td>
          <td data-label="Status">
            <form method="POST" class="status-form">
              <input type="hidden" name="id" value="<?= $lead['id'] ?>">
              <input type="hidden" name="action" value="set_status">
              <input type="hidden" name="date_filter" value="<?= htmlspecialchars($date) ?>">
              <input type="hidden" name="service_filter" value="<?= htmlspecialchars($service) ?>">
              <input type="hidden" name="status_filter" value="<?= htmlspecialchars($status) ?>">
              <input type="hidden" name="technician_filter" value="<?= htmlspecialchars($technician) ?>">
              <input type="hidden" name="search_filter" value="<?= htmlspecialchars($search) ?>">
              <input type="hidden" name="page_num" value="<?= $page ?>">
              <select name="status" class="status-pill <?= statusClass($lead['status']) ?>" onchange="this.form.submit()">
                <?php foreach ($STATUSES as $s): ?>
                  <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td data-label="Actions">
            <div style="display:flex;gap:6px;align-items:center">
              <a href="lead-view.php?id=<?= $lead['id'] ?>" class="btn btn--icon btn--secondary" title="View details">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="tel:<?= htmlspecialchars($lead['mobile']) ?>" class="btn btn--icon btn--secondary" title="Call">
                <i class="fa-solid fa-phone"></i>
              </a>
              <a href="https://wa.me/91<?= htmlspecialchars(preg_replace('/\D/', '', $lead['mobile'])) ?>" target="_blank" rel="noopener" class="btn btn--icon btn--success" title="WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
              </a>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this lead?')">
                <input type="hidden" name="id" value="<?= $lead['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="date_filter" value="<?= htmlspecialchars($date) ?>">
                <input type="hidden" name="service_filter" value="<?= htmlspecialchars($service) ?>">
                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($status) ?>">
                <input type="hidden" name="technician_filter" value="<?= htmlspecialchars($technician) ?>">
                <input type="hidden" name="search_filter" value="<?= htmlspecialchars($search) ?>">
                <input type="hidden" name="page_num" value="<?= $page ?>">
                <button type="submit" class="btn btn--icon btn--danger" title="Delete">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="<?= leadsUrl(['page'=>$page-1]) ?>" class="btn btn--sm btn--secondary"><i class="fa-solid fa-chevron-left"></i></a>
    <?php endif; ?>
    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="<?= leadsUrl(['page'=>$p]) ?>" class="btn btn--sm <?= $p===$page?'btn--primary':'btn--secondary' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <a href="<?= leadsUrl(['page'=>$page+1]) ?>" class="btn btn--sm btn--secondary"><i class="fa-solid fa-chevron-right"></i></a>
    <?php endif; ?>
    <span class="pagination__info"><?= $total ?> total</span>
  </div>
  <?php endif; ?>
</div>

<?php include '_footer.php'; ?>

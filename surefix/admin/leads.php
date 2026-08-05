<?php
require_once '_auth.php';

$PAGE_TITLE = 'Leads';
$ACTIVE_NAV = 'leads';

// ── Handle status/delete actions ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id     = (int)$_POST['id'];
    $action = $_POST['action'];
    $back   = 'leads.php?' . http_build_query(array_filter([
        'type'   => $_POST['type_filter']   ?? '',
        'status' => $_POST['status_filter'] ?? '',
        'page'   => $_POST['page_num']      ?? '',
    ]));
    if ($action === 'delete') {
        db()->prepare('DELETE FROM leads WHERE id = ?')->execute([$id]);
    } elseif (in_array($action, ['new', 'read', 'done'], true)) {
        db()->prepare('UPDATE leads SET status = ? WHERE id = ?')->execute([$action, $id]);
    }
    header('Location: ' . $back);
    exit;
}

// ── Filters & Pagination ──────────────────────────────────────────────────────
$type   = $_GET['type']   ?? 'all';
$status = $_GET['status'] ?? 'all';
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = 20;
$offset = ($page - 1) * $per;

$conditions = [];
$params     = [];
if ($type   !== 'all') { $conditions[] = 'type = ?';   $params[] = $type; }
if ($status !== 'all') { $conditions[] = 'status = ?'; $params[] = $status; }
$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$total = (int)db()->prepare("SELECT COUNT(*) FROM leads $where")->execute($params)
             ?: db()->prepare("SELECT COUNT(*) FROM leads $where")->execute($params);
$cntStmt = db()->prepare("SELECT COUNT(*) FROM leads $where");
$cntStmt->execute($params);
$total = (int)$cntStmt->fetchColumn();

$stmt = db()->prepare("SELECT * FROM leads $where ORDER BY created_at DESC LIMIT $per OFFSET $offset");
$stmt->execute($params);
$leads = $stmt->fetchAll();

// ── Stats ─────────────────────────────────────────────────────────────────────
$stats = db()->query(
    "SELECT COUNT(*) AS total,
            SUM(type='contact') AS contacts,
            SUM(type='booking') AS bookings,
            SUM(status='new')   AS new_count
     FROM leads"
)->fetch();

$totalPages = (int)ceil($total / $per);

// ── Build URL helper ──────────────────────────────────────────────────────────
function leadsUrl(array $params = []): string {
    global $type, $status, $page;
    $base = ['type' => $type, 'status' => $status, 'page' => $page];
    return 'leads.php?' . http_build_query(array_merge($base, $params));
}

include '_header.php';
?>

<!-- Stat Cards -->
<div class="stat-cards">
  <div class="stat-card">
    <div class="stat-card__label">Total Leads</div>
    <div class="stat-card__val"><?= (int)$stats['total'] ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label">Contact Enquiries</div>
    <div class="stat-card__val"><?= (int)$stats['contacts'] ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label">Bookings</div>
    <div class="stat-card__val"><?= (int)$stats['bookings'] ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label" style="color:#ef4444">Unread</div>
    <div class="stat-card__val" style="color:#ef4444"><?= (int)$stats['new_count'] ?></div>
  </div>
</div>

<!-- Filters -->
<div class="filters">
  <a href="<?= leadsUrl(['type'=>'all',     'page'=>1]) ?>" class="filter-btn <?= $type==='all'     ?'active':'' ?>">All Types</a>
  <a href="<?= leadsUrl(['type'=>'contact', 'page'=>1]) ?>" class="filter-btn <?= $type==='contact' ?'active':'' ?>"><i class="fa-solid fa-envelope"></i> Contact</a>
  <a href="<?= leadsUrl(['type'=>'booking', 'page'=>1]) ?>" class="filter-btn <?= $type==='booking' ?'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Booking</a>
  <div class="filter-sep"></div>
  <a href="<?= leadsUrl(['status'=>'all',  'page'=>1]) ?>" class="filter-btn <?= $status==='all'  ?'active':'' ?>">All Status</a>
  <a href="<?= leadsUrl(['status'=>'new',  'page'=>1]) ?>" class="filter-btn <?= $status==='new'  ?'active':'' ?>">New</a>
  <a href="<?= leadsUrl(['status'=>'read', 'page'=>1]) ?>" class="filter-btn <?= $status==='read' ?'active':'' ?>">Read</a>
  <a href="<?= leadsUrl(['status'=>'done', 'page'=>1]) ?>" class="filter-btn <?= $status==='done' ?'active':'' ?>">Done</a>
</div>

<!-- Table -->
<div class="card" style="padding:0">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Subject / Service</th>
          <th>Received</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leads)): ?>
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              <p>No leads found for this filter.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($leads as $lead): ?>
        <tr>
          <td><span class="badge-<?= $lead['type'] ?>"><?= ucfirst($lead['type']) ?></span></td>
          <td>
            <strong><?= htmlspecialchars($lead['name']) ?></strong>
            <?php if ($lead['email']): ?>
              <br><small style="color:#94a3b8"><?= htmlspecialchars($lead['email']) ?></small>
            <?php endif; ?>
          </td>
          <td style="white-space:nowrap"><?= htmlspecialchars($lead['phone'] ?? '—') ?></td>
          <td style="max-width:180px">
            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#64748b">
              <?= htmlspecialchars($lead['service'] ?: $lead['subject'] ?: '—') ?>
            </span>
          </td>
          <td style="white-space:nowrap;color:#64748b;font-size:.8rem">
            <?= date('d M Y', strtotime($lead['created_at'])) ?><br>
            <?= date('h:i A', strtotime($lead['created_at'])) ?>
          </td>
          <td><span class="badge-<?= $lead['status'] ?>"><?= ucfirst($lead['status']) ?></span></td>
          <td>
            <div style="display:flex;gap:5px;align-items:center">
              <a href="lead-view.php?id=<?= $lead['id'] ?>" class="btn btn--sm btn--secondary" title="View details">
                <i class="fa-solid fa-eye"></i>
              </a>
              <?php if ($lead['status'] !== 'done'): ?>
              <form method="POST" style="display:inline">
                <input type="hidden" name="id"            value="<?= $lead['id'] ?>">
                <input type="hidden" name="action"        value="<?= $lead['status']==='new' ? 'read' : 'done' ?>">
                <input type="hidden" name="type_filter"   value="<?= htmlspecialchars($type) ?>">
                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($status) ?>">
                <input type="hidden" name="page_num"      value="<?= $page ?>">
                <button type="submit" class="btn btn--sm btn--success" title="Mark <?= $lead['status']==='new' ? 'Read' : 'Done' ?>">
                  <i class="fa-solid fa-<?= $lead['status']==='new' ? 'envelope-open' : 'check' ?>"></i>
                </button>
              </form>
              <?php endif; ?>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this lead?')">
                <input type="hidden" name="id"            value="<?= $lead['id'] ?>">
                <input type="hidden" name="action"        value="delete">
                <input type="hidden" name="type_filter"   value="<?= htmlspecialchars($type) ?>">
                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($status) ?>">
                <input type="hidden" name="page_num"      value="<?= $page ?>">
                <button type="submit" class="btn btn--sm btn--danger" title="Delete">
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

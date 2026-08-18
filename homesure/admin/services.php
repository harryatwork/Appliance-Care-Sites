<?php
require_once '_auth.php';

$PAGE_TITLE = 'Services';
$ACTIVE_NAV = 'services';

// ── Handle actions ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($_POST['action'] === 'delete') {
        // Remove local uploaded image file
        $row = db()->prepare('SELECT image_url FROM services WHERE id = ?');
        $row->execute([$id]);
        $row = $row->fetch();
        if ($row && $row['image_url'] && str_starts_with($row['image_url'], 'assets/images/services/')) {
            @unlink(__DIR__ . '/../' . $row['image_url']);
        }
        db()->prepare('DELETE FROM services WHERE id = ?')->execute([$id]);
    } elseif ($_POST['action'] === 'toggle') {
        db()->prepare('UPDATE services SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
    }
    header('Location: services.php');
    exit;
}

$services = db()->query('SELECT * FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();

include '_header.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert--success"><i class="fa-solid fa-circle-check"></i> Service saved successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert--info"><i class="fa-solid fa-circle-info"></i> Service deleted.</div>
<?php endif; ?>

<div class="card">
  <div class="card__head">
    <h2>All Services <span style="color:#94a3b8;font-weight:400">(<?= count($services) ?>)</span></h2>
    <a href="services-edit.php" class="btn btn--primary btn--sm">
      <i class="fa-solid fa-plus"></i> Add Service
    </a>
  </div>

  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Description</th>
          <th>Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($services)): ?>
        <tr>
          <td colspan="6">
            <div class="empty-state">
              <i class="fa-solid fa-wrench"></i>
              <p>No services yet. <a href="services-edit.php">Add your first service</a>.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($services as $s): ?>
        <tr>
          <td>
            <?php if ($s['image_url']): 
            $adminImg = str_starts_with($s['image_url'], 'http') ? $s['image_url'] : '../' . $s['image_url'];
          ?>
              <img src="<?= htmlspecialchars($adminImg) ?>" alt="" class="svc-thumb">
            <?php else: ?>
              <div class="img-placeholder"><i class="fa-solid fa-image"></i></div>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
          <td style="max-width:220px;color:#64748b;font-size:.82rem">
            <?= htmlspecialchars(mb_substr(strip_tags($s['description'] ?? ''), 0, 90)) ?>
            <?= mb_strlen(strip_tags($s['description'] ?? '')) > 90 ? '…' : '' ?>
          </td>
          <td style="color:#64748b;text-align:center"><?= (int)$s['sort_order'] ?></td>
          <td>
            <form method="POST" style="display:inline">
              <input type="hidden" name="id"     value="<?= $s['id'] ?>">
              <input type="hidden" name="action" value="toggle">
              <button type="submit" class="btn btn--sm <?= $s['is_active'] ? 'btn--success' : 'btn--secondary' ?>">
                <i class="fa-solid fa-<?= $s['is_active'] ? 'eye' : 'eye-slash' ?>"></i>
                <?= $s['is_active'] ? 'Active' : 'Hidden' ?>
              </button>
            </form>
          </td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="services-edit.php?id=<?= $s['id'] ?>" class="btn btn--sm btn--secondary">
                <i class="fa-solid fa-pen"></i> Edit
              </a>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete \"<?= htmlspecialchars(addslashes($s['name'])) ?>\"?')">
                <input type="hidden" name="id"     value="<?= $s['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn--sm btn--danger">
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
</div>

<?php include '_footer.php'; ?>

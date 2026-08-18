<?php
require_once '_auth.php';

$PAGE_TITLE = 'Testimonials';
$ACTIVE_NAV = 'testimonials';

// ── Handle actions ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($_POST['action'] === 'delete') {
        db()->prepare('DELETE FROM testimonials WHERE id = ?')->execute([$id]);
    } elseif ($_POST['action'] === 'toggle') {
        db()->prepare('UPDATE testimonials SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
    }
    header('Location: testimonials.php');
    exit;
}

$testimonials = db()->query(
    'SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC'
)->fetchAll();

include '_header.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert--success"><i class="fa-solid fa-circle-check"></i> Testimonial saved successfully.</div>
<?php endif; ?>

<div class="card">
  <div class="card__head">
    <h2>All Testimonials <span style="color:#94a3b8;font-weight:400">(<?= count($testimonials) ?>)</span></h2>
    <a href="testimonials-edit.php" class="btn btn--primary btn--sm">
      <i class="fa-solid fa-plus"></i> Add Testimonial
    </a>
  </div>

  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Avatar</th>
          <th>Customer</th>
          <th>Rating</th>
          <th>Review</th>
          <th>Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($testimonials)): ?>
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <i class="fa-solid fa-star"></i>
              <p>No testimonials yet. <a href="testimonials-edit.php">Add the first one</a>.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($testimonials as $t): ?>
        <tr>
          <td>
            <?php if ($t['avatar_url']): ?>
              <img src="<?= htmlspecialchars($t['avatar_url']) ?>" alt="" class="avatar-thumb">
            <?php else: ?>
              <div class="avatar-thumb" style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:.75rem">
                <i class="fa-solid fa-user"></i>
              </div>
            <?php endif; ?>
          </td>
          <td>
            <strong><?= htmlspecialchars($t['customer_name']) ?></strong>
            <?php if ($t['location']): ?>
              <br><small style="color:#94a3b8"><?= htmlspecialchars($t['location']) ?></small>
            <?php endif; ?>
          </td>
          <td>
            <span class="stars"><?= str_repeat('★', (int)$t['rating']) ?></span>
          </td>
          <td style="max-width:240px;color:#64748b;font-size:.82rem">
            <?= htmlspecialchars(mb_substr($t['review_text'], 0, 100)) ?>
            <?= mb_strlen($t['review_text']) > 100 ? '…' : '' ?>
          </td>
          <td style="color:#64748b;text-align:center"><?= (int)$t['sort_order'] ?></td>
          <td>
            <form method="POST" style="display:inline">
              <input type="hidden" name="id"     value="<?= $t['id'] ?>">
              <input type="hidden" name="action" value="toggle">
              <button type="submit" class="btn btn--sm <?= $t['is_active'] ? 'btn--success' : 'btn--secondary' ?>">
                <i class="fa-solid fa-<?= $t['is_active'] ? 'eye' : 'eye-slash' ?>"></i>
                <?= $t['is_active'] ? 'Active' : 'Hidden' ?>
              </button>
            </form>
          </td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="testimonials-edit.php?id=<?= $t['id'] ?>" class="btn btn--sm btn--secondary">
                <i class="fa-solid fa-pen"></i> Edit
              </a>
              <form method="POST" style="display:inline"
                    onsubmit="return confirm('Delete testimonial from <?= htmlspecialchars(addslashes($t['customer_name'])) ?>?')">
                <input type="hidden" name="id"     value="<?= $t['id'] ?>">
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

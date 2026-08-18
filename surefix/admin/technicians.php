<?php
require_once '_auth.php';

$PAGE_TITLE = 'Technicians';
$ACTIVE_NAV = 'technicians';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $errors[] = 'Technician name is required.';
        } else {
            $check = db()->prepare('SELECT id FROM technicians WHERE name = ?');
            $check->execute([$name]);
            if ($check->fetch()) {
                $errors[] = 'A technician with that name already exists.';
            } else {
                db()->prepare('INSERT INTO technicians (name) VALUES (?)')->execute([$name]);
                $success = 'Technician added.';
            }
        }
    } elseif ($action === 'rename') {
        $id   = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        if ($id && $name !== '') {
            db()->prepare('UPDATE technicians SET name = ? WHERE id = ?')->execute([$name, $id]);
            $success = 'Technician updated.';
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            db()->prepare('UPDATE technicians SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            // Leads already assigned to this technician keep the name as
            // plain text (technician_name isn't a foreign key) — deleting
            // just removes them from future dropdown choices.
            db()->prepare('DELETE FROM technicians WHERE id = ?')->execute([$id]);
            $success = 'Technician deleted.';
        }
    }
}

$technicians = db()->query(
    "SELECT t.*, (SELECT COUNT(*) FROM leads WHERE technician_name = t.name) AS lead_count
     FROM technicians t ORDER BY t.is_active DESC, t.name ASC"
)->fetchAll();

include '_header.php';
?>

<?php if ($success): ?>
<div class="alert alert--success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php foreach ($errors as $e): ?>
<div class="alert alert--error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<div class="card" style="max-width:560px">
  <div class="card__head"><h2>Add Technician</h2></div>
  <form method="POST" style="display:flex;gap:10px;align-items:flex-end">
    <input type="hidden" name="action" value="add">
    <div class="form-group" style="flex:1;margin-bottom:0">
      <label for="tech-name">Technician Name</label>
      <input type="text" id="tech-name" name="name" required placeholder="e.g. Ravi Kumar">
    </div>
    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Add</button>
  </form>
</div>

<div class="card" style="padding:0;max-width:640px">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Assigned Bookings</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($technicians)): ?>
        <tr>
          <td colspan="4">
            <div class="empty-state">
              <i class="fa-solid fa-user-gear"></i>
              <p>No technicians yet. Add one above so they show up as a dropdown option when assigning a lead.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($technicians as $tech): ?>
        <tr>
          <td data-label="Name">
            <form method="POST" style="display:flex;gap:8px;align-items:center">
              <input type="hidden" name="action" value="rename">
              <input type="hidden" name="id" value="<?= $tech['id'] ?>">
              <input type="text" name="name" value="<?= htmlspecialchars($tech['name']) ?>" style="max-width:220px" onchange="this.form.requestSubmit()">
            </form>
          </td>
          <td data-label="Assigned Bookings"><?= (int)$tech['lead_count'] ?></td>
          <td data-label="Status">
            <form method="POST" style="display:inline">
              <input type="hidden" name="id" value="<?= $tech['id'] ?>">
              <input type="hidden" name="action" value="toggle">
              <button type="submit" class="btn btn--sm <?= $tech['is_active'] ? 'btn--success' : 'btn--secondary' ?>">
                <i class="fa-solid fa-<?= $tech['is_active'] ? 'user-check' : 'user-slash' ?>"></i>
                <?= $tech['is_active'] ? 'Active' : 'Inactive' ?>
              </button>
            </form>
          </td>
          <td data-label="Actions">
            <form method="POST" onsubmit="return confirm('Delete this technician? They will no longer appear in the assignment dropdown — bookings already assigned to them keep the name on record.')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $tech['id'] ?>">
              <button type="submit" class="btn btn--sm btn--danger"><i class="fa-solid fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '_footer.php'; ?>

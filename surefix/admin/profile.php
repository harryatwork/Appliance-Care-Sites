<?php
require_once '_auth.php';

$stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();
if (!$admin) { header('Location: logout.php'); exit; }

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($name === '') $errors[] = 'Name is required.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';

        if (empty($errors)) {
            db()->prepare('UPDATE admin_users SET name = ?, email = ? WHERE id = ?')->execute([$name, $email, $admin['id']]);
            $admin['name'] = $name;
            $admin['email'] = $email;
            $success = 'Profile updated.';
        }
    } elseif ($action === 'change_password') {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!password_verify($current, $admin['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        } elseif (strlen($new) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        } elseif ($new !== $confirm) {
            $errors[] = 'New password and confirmation do not match.';
        }

        if (empty($errors)) {
            db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $admin['id']]);
            $success = 'Password changed successfully.';
        }
    }
}

$PAGE_TITLE = 'Profile';
$ACTIVE_NAV = 'profile';
include '_header.php';
?>

<?php if ($success): ?>
<div class="alert alert--success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php foreach ($errors as $e): ?>
<div class="alert alert--error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<div class="card" style="max-width:520px">
  <div class="card__head"><h2>Profile Details</h2></div>
  <form method="POST">
    <input type="hidden" name="action" value="update_profile">
    <div class="form-group">
      <label for="p-name">Name</label>
      <input type="text" id="p-name" name="name" required value="<?= htmlspecialchars($admin['name']) ?>">
    </div>
    <div class="form-group">
      <label for="p-username">Username</label>
      <input type="text" id="p-username" value="<?= htmlspecialchars($admin['username']) ?>" disabled style="background:#f8fafc;color:#94a3b8">
      <p class="form-hint">Username can't be changed here — contact your developer if you need it updated.</p>
    </div>
    <div class="form-group">
      <label for="p-email">Email</label>
      <input type="email" id="p-email" name="email" required value="<?= htmlspecialchars($admin['email']) ?>">
    </div>
    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
  </form>
</div>

<div class="card" style="max-width:520px">
  <div class="card__head"><h2>Change Password</h2></div>
  <form method="POST" autocomplete="off">
    <input type="hidden" name="action" value="change_password">
    <div class="form-group">
      <label for="p-current">Current Password</label>
      <input type="password" id="p-current" name="current_password" required autocomplete="current-password">
    </div>
    <div class="form-group">
      <label for="p-new">New Password</label>
      <input type="password" id="p-new" name="new_password" required minlength="8" autocomplete="new-password">
      <p class="form-hint">At least 8 characters.</p>
    </div>
    <div class="form-group">
      <label for="p-confirm">Confirm New Password</label>
      <input type="password" id="p-confirm" name="confirm_password" required minlength="8" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-key"></i> Change Password</button>
  </form>
</div>

<?php include '_footer.php'; ?>

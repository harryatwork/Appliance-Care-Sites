<?php
require_once '_auth.php';

$id  = (int)($_GET['id'] ?? 0);
$svc = null;

if ($id) {
    $s = db()->prepare('SELECT * FROM services WHERE id = ?');
    $s->execute([$id]);
    $svc = $s->fetch();
    if (!$svc) { header('Location: services.php'); exit; }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']        ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_url   = trim($_POST['image_url']   ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '') $errors[] = 'Service name is required.';

    // Handle file upload (takes priority over URL)
    if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/services/';
        if (!is_dir($uploadDir)) @mkdir($uploadDir, 0775, true);

        $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) {
            $errors[] = 'Invalid image format. Allowed: JPG, PNG, WebP, GIF.';
        } elseif ($_FILES['image_file']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Image must be under 5 MB.';
        } else {
            $fname = 'svc_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $fname)) {
                // Delete old local file if replacing
                if ($svc && $svc['image_url'] && str_starts_with($svc['image_url'], 'assets/images/services/')) {
                    @unlink(__DIR__ . '/../' . $svc['image_url']);
                }
                $image_url = 'assets/images/services/' . $fname;
            } else {
                $errors[] = 'Failed to save uploaded image.';
            }
        }
    }

    if (empty($errors)) {
        if ($id) {
            db()->prepare(
                'UPDATE services SET name=?, description=?, image_url=?, sort_order=?, is_active=? WHERE id=?'
            )->execute([$name, $description, $image_url, $sort_order, $is_active, $id]);
        } else {
            db()->prepare(
                'INSERT INTO services (name, description, image_url, sort_order, is_active) VALUES (?,?,?,?,?)'
            )->execute([$name, $description, $image_url, $sort_order, $is_active]);
        }
        header('Location: services.php?saved=1');
        exit;
    }

    // Re-populate form on error
    $svc = compact('name', 'description', 'image_url', 'sort_order', 'is_active');
}

$PAGE_TITLE = $id ? 'Edit Service' : 'Add Service';
$ACTIVE_NAV = 'services';
include '_header.php';
?>

<div style="margin-bottom:16px">
  <a href="services.php" class="btn btn--secondary btn--sm">
    <i class="fa-solid fa-arrow-left"></i> Back to Services
  </a>
</div>

<div class="card" style="max-width:680px">
  <div class="card__head">
    <h2><?= $id ? 'Edit Service' : 'Add New Service' ?></h2>
  </div>

  <?php foreach ($errors as $e): ?>
  <div class="alert alert--error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <form method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label for="name">Service Name <span style="color:#ef4444">*</span></label>
      <input type="text" id="name" name="name" required
             value="<?= htmlspecialchars($svc['name'] ?? '') ?>"
             placeholder="e.g. Refrigerator Repair">
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4"
                placeholder="Short description shown on the website…"><?= htmlspecialchars($svc['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label>Service Image — Upload File</label>
      <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif">
      <p class="form-hint">Max 5 MB. JPG, PNG or WebP recommended.</p>
      <?php if (!empty($svc['image_url'])): 
        $previewSrc = str_starts_with($svc['image_url'], 'http') ? $svc['image_url'] : '../' . $svc['image_url'];
      ?>
      <img src="<?= htmlspecialchars($previewSrc) ?>" alt="current" class="img-preview">
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label for="image_url">— OR — Image URL</label>
      <input type="text" id="image_url" name="image_url"
             placeholder="https://images.unsplash.com/... or assets/images/services/..."
             value="<?= htmlspecialchars($svc['image_url'] ?? '') ?>">
      <p class="form-hint">If you upload a file above, the URL field is ignored.</p>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label for="sort_order">Display Order <span style="font-weight:400;color:#94a3b8">(lower = first)</span></label>
        <input type="number" id="sort_order" name="sort_order" min="0" max="999"
               value="<?= (int)($svc['sort_order'] ?? 0) ?>">
      </div>
      <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:6px">
        <label class="toggle">
          <input type="checkbox" name="is_active" <?= ($svc['is_active'] ?? 1) ? 'checked' : '' ?>>
          <span class="toggle__track"></span>
          Show on website
        </label>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:8px">
      <button type="submit" class="btn btn--primary">
        <i class="fa-solid fa-floppy-disk"></i> Save Service
      </button>
      <a href="services.php" class="btn btn--secondary">Cancel</a>
    </div>

  </form>
</div>

<?php include '_footer.php'; ?>

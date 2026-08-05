<?php
require_once '_auth.php';

$id   = (int)($_GET['id'] ?? 0);
$item = null;

if ($id) {
    $s = db()->prepare('SELECT * FROM testimonials WHERE id = ?');
    $s->execute([$id]);
    $item = $s->fetch();
    if (!$item) { header('Location: testimonials.php'); exit; }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $location      = trim($_POST['location']      ?? '');
    $rating        = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $review_text   = trim($_POST['review_text']   ?? '');
    $avatar_url    = trim($_POST['avatar_url']     ?? '');
    $sort_order    = (int)($_POST['sort_order']    ?? 0);
    $is_active     = isset($_POST['is_active']) ? 1 : 0;

    if ($customer_name === '') $errors[] = 'Customer name is required.';
    if ($review_text   === '') $errors[] = 'Review text is required.';

    if (empty($errors)) {
        if ($id) {
            db()->prepare(
                'UPDATE testimonials SET customer_name=?, location=?, rating=?, review_text=?, avatar_url=?, sort_order=?, is_active=? WHERE id=?'
            )->execute([$customer_name, $location, $rating, $review_text, $avatar_url, $sort_order, $is_active, $id]);
        } else {
            db()->prepare(
                'INSERT INTO testimonials (customer_name, location, rating, review_text, avatar_url, sort_order, is_active) VALUES (?,?,?,?,?,?,?)'
            )->execute([$customer_name, $location, $rating, $review_text, $avatar_url, $sort_order, $is_active]);
        }
        header('Location: testimonials.php?saved=1');
        exit;
    }

    // Re-populate on error
    $item = compact('customer_name', 'location', 'rating', 'review_text', 'avatar_url', 'sort_order', 'is_active');
}

$PAGE_TITLE = $id ? 'Edit Testimonial' : 'Add Testimonial';
$ACTIVE_NAV = 'testimonials';
include '_header.php';
?>

<div style="margin-bottom:16px">
  <a href="testimonials.php" class="btn btn--secondary btn--sm">
    <i class="fa-solid fa-arrow-left"></i> Back to Testimonials
  </a>
</div>

<div class="card" style="max-width:620px">
  <div class="card__head">
    <h2><?= $id ? 'Edit Testimonial' : 'Add New Testimonial' ?></h2>
  </div>

  <?php foreach ($errors as $e): ?>
  <div class="alert alert--error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <form method="POST">

    <div class="form-row-2">
      <div class="form-group">
        <label for="customer_name">Customer Name <span style="color:#ef4444">*</span></label>
        <input type="text" id="customer_name" name="customer_name" required
               value="<?= htmlspecialchars($item['customer_name'] ?? '') ?>"
               placeholder="e.g. Sneha Reddy">
      </div>
      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" id="location" name="location"
               value="<?= htmlspecialchars($item['location'] ?? '') ?>"
               placeholder="e.g. Bengaluru">
      </div>
    </div>

    <div class="form-group">
      <label for="rating">Star Rating</label>
      <select id="rating" name="rating">
        <?php for ($i = 5; $i >= 1; $i--): ?>
          <option value="<?= $i ?>" <?= ($item['rating'] ?? 5) == $i ? 'selected' : '' ?>>
            <?= str_repeat('★', $i) ?> <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="review_text">Review Text <span style="color:#ef4444">*</span></label>
      <textarea id="review_text" name="review_text" rows="4" required
                placeholder="Write the customer's review here…"><?= htmlspecialchars($item['review_text'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label for="avatar_url">Avatar URL <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
      <input type="url" id="avatar_url" name="avatar_url"
             placeholder="https://i.pravatar.cc/100?img=1"
             value="<?= htmlspecialchars($item['avatar_url'] ?? '') ?>">
      <p class="form-hint">Leave blank to show a default placeholder. Use <a href="https://pravatar.cc" target="_blank">pravatar.cc</a> for free avatars.</p>
      <?php if (!empty($item['avatar_url'])): ?>
      <img src="<?= htmlspecialchars($item['avatar_url']) ?>" alt=""
           style="width:50px;height:50px;border-radius:50%;object-fit:cover;margin-top:8px">
      <?php endif; ?>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label for="sort_order">Display Order <span style="font-weight:400;color:#94a3b8">(lower = first)</span></label>
        <input type="number" id="sort_order" name="sort_order" min="0" max="999"
               value="<?= (int)($item['sort_order'] ?? 0) ?>">
      </div>
      <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:6px">
        <label class="toggle">
          <input type="checkbox" name="is_active" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?>>
          <span class="toggle__track"></span>
          Show on website
        </label>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:8px">
      <button type="submit" class="btn btn--primary">
        <i class="fa-solid fa-floppy-disk"></i> Save Testimonial
      </button>
      <a href="testimonials.php" class="btn btn--secondary">Cancel</a>
    </div>

  </form>
</div>

<?php include '_footer.php'; ?>

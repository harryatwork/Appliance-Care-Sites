<?php
require_once '_auth.php';

$id   = (int)($_GET['id'] ?? 0);
$item = null;

if ($id) {
    $s = db()->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $s->execute([$id]);
    $item = $s->fetch();
    if (!$item) { header('Location: blogs.php'); exit; }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = trim($_POST['title'] ?? '');
    $slug         = trim($_POST['slug'] ?? '');
    $service_id   = (int)($_POST['service_id'] ?? 0) ?: null;
    $excerpt      = trim($_POST['excerpt'] ?? '');
    $content      = trim($_POST['content'] ?? '');
    $image_url    = trim($_POST['image_url'] ?? '');
    $author       = trim($_POST['author'] ?? 'We Assist Team');

    // Handle file upload (takes priority over URL)
    if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/blogs/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext  = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp','svg'];
        if (in_array($ext, $allowed)) {
            $filename = time() . '-' . preg_replace('/[^a-z0-9]+/', '-', strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_FILENAME))) . '.' . $ext;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $filename)) {
                $image_url = 'assets/images/blogs/' . $filename;
            }
        } else {
            $errors[] = 'Image must be jpg, png, gif, webp, or svg.';
        }
    }
    $topic        = trim($_POST['topic'] ?? '');
    $read_time    = trim($_POST['read_time'] ?? '5 min read');
    $sort_order   = (int)($_POST['sort_order'] ?? 0);
    $is_active    = isset($_POST['is_active']) ? 1 : 0;
    $published_at = trim($_POST['published_at'] ?? '') ?: date('Y-m-d H:i:s');

    // Auto-generate slug
    if ($slug === '' && $title !== '') {
        $slug = mb_strtolower(trim($title), 'UTF-8');
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
    }

    if ($title === '') $errors[] = 'Title is required.';
    if ($slug === '')  $errors[] = 'Slug is required.';

    // Check slug uniqueness
    if (empty($errors)) {
        $check = db()->prepare('SELECT id FROM blog_posts WHERE slug = ? AND id != ?');
        $check->execute([$slug, $id]);
        if ($check->fetch()) $errors[] = 'Slug already exists. Please change it.';
    }

    if (empty($errors)) {
        if ($id) {
            db()->prepare(
                'UPDATE blog_posts SET title=?, slug=?, service_id=?, excerpt=?, content=?, image_url=?, author=?, topic=?, read_time=?, sort_order=?, is_active=?, published_at=? WHERE id=?'
            )->execute([$title, $slug, $service_id, $excerpt, $content, $image_url, $author, $topic, $read_time, $sort_order, $is_active, $published_at, $id]);
        } else {
            db()->prepare(
                'INSERT INTO blog_posts (title, slug, service_id, excerpt, content, image_url, author, topic, read_time, sort_order, is_active, published_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'
            )->execute([$title, $slug, $service_id, $excerpt, $content, $image_url, $author, $topic, $read_time, $sort_order, $is_active, $published_at]);
        }
        header('Location: blogs.php?saved=1');
        exit;
    }

    $item = compact('title', 'slug', 'service_id', 'excerpt', 'content', 'image_url', 'author', 'topic', 'read_time', 'sort_order', 'is_active', 'published_at');
}

$services = db()->query('SELECT id, name FROM services WHERE is_active = 1 ORDER BY name ASC')->fetchAll();

$PAGE_TITLE = $id ? 'Edit Blog Post' : 'Add Blog Post';
$ACTIVE_NAV = 'blogs';
include '_header.php';
?>

<div style="margin-bottom:16px">
  <a href="blogs.php" class="btn btn--secondary btn--sm">
    <i class="fa-solid fa-arrow-left"></i> Back to Blog Posts
  </a>
</div>

<div class="card" style="max-width:780px">
  <div class="card__head">
    <h2><?= $id ? 'Edit Blog Post' : 'Add New Blog Post' ?></h2>
  </div>

  <?php foreach ($errors as $e): ?>
  <div class="alert alert--error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <form method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label for="title">Title <span style="color:#ef4444">*</span></label>
      <input type="text" id="title" name="title" required
             value="<?= htmlspecialchars($item['title'] ?? '') ?>"
             placeholder="e.g. 7 simple habits to keep your fridge running">
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label for="slug">Slug <span style="font-weight:400;color:#94a3b8">(auto-generated if blank)</span></label>
        <input type="text" id="slug" name="slug"
               value="<?= htmlspecialchars($item['slug'] ?? '') ?>"
               placeholder="7-simple-habits-to-keep-your-fridge">
      </div>
      <div class="form-group">
        <label for="service_id">Category (Service)</label>
        <select id="service_id" name="service_id">
          <option value="0">— None —</option>
          <?php foreach ($services as $svc): ?>
            <option value="<?= $svc['id'] ?>" <?= ($item['service_id'] ?? 0) == $svc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($svc['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="excerpt">Excerpt</label>
      <textarea id="excerpt" name="excerpt" rows="2"
                placeholder="Short summary for listing pages…"><?= htmlspecialchars($item['excerpt'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label for="content">Content (HTML) <span style="color:#ef4444">*</span></label>
      <textarea id="content" name="content" rows="14"
                placeholder="<p>Write your blog post content here using HTML…</p>"
                style="font-family:monospace;font-size:.82rem"><?= htmlspecialchars($item['content'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label for="image_file">Featured Image (Upload)</label>
      <input type="file" id="image_file" name="image_file" accept="image/*">
      <p class="form-hint">Upload an image, or provide a URL below instead.</p>
    </div>

    <div class="form-group">
      <label for="image_url">Featured Image URL <span style="font-weight:400;color:#94a3b8">(used if no file uploaded)</span></label>
      <input type="text" id="image_url" name="image_url"
             value="<?= htmlspecialchars($item['image_url'] ?? '') ?>"
             placeholder="https://images.unsplash.com/photo-...">
      <?php if (!empty($item['image_url'])): ?>
        <img src="<?= str_starts_with($item['image_url'], 'http') ? htmlspecialchars($item['image_url']) : '../' . htmlspecialchars($item['image_url']) ?>" alt="" style="width:120px;height:70px;object-fit:cover;border-radius:6px;margin-top:8px">
      <?php endif; ?>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label for="author">Author</label>
        <input type="text" id="author" name="author"
               value="<?= htmlspecialchars($item['author'] ?? 'We Assist Team') ?>"
               placeholder="We Assist Team">
      </div>
      <div class="form-group">
        <label for="topic">Topic Tag</label>
        <input type="text" id="topic" name="topic"
               value="<?= htmlspecialchars($item['topic'] ?? '') ?>"
               placeholder="e.g. maintenance, repair, buying guide">
      </div>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label for="read_time">Read Time</label>
        <input type="text" id="read_time" name="read_time"
               value="<?= htmlspecialchars($item['read_time'] ?? '5 min read') ?>"
               placeholder="5 min read">
      </div>
      <div class="form-group">
        <label for="published_at">Publish Date</label>
        <input type="datetime-local" id="published_at" name="published_at"
               value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($item['published_at'] ?? 'now'))) ?>">
      </div>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label for="sort_order">Sort Order <span style="font-weight:400;color:#94a3b8">(lower = first)</span></label>
        <input type="number" id="sort_order" name="sort_order" min="0" max="999"
               value="<?= (int)($item['sort_order'] ?? 0) ?>">
      </div>
      <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:6px">
        <label class="toggle">
          <input type="checkbox" name="is_active" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?>>
          <span class="toggle__track"></span>
          Publish on website
        </label>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:8px">
      <button type="submit" class="btn btn--primary">
        <i class="fa-solid fa-floppy-disk"></i> Save Post
      </button>
      <a href="blogs.php" class="btn btn--secondary">Cancel</a>
    </div>

  </form>
</div>

<?php include '_footer.php'; ?>

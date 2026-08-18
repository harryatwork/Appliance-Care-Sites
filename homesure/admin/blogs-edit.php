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
    $title       = trim($_POST['title'] ?? '');
    $slug        = trim($_POST['slug'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0) ?: null;
    $excerpt     = trim($_POST['excerpt'] ?? '');
    $content     = trim($_POST['content'] ?? '');
    $image_url   = trim($_POST['image_url'] ?? '');
    $author      = trim($_POST['author'] ?? 'Home Sure Team');
    $status      = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';
    $published_at = trim($_POST['published_at'] ?? '') ?: date('Y-m-d H:i:s');

    // Handle file upload (takes priority over URL)
    if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/blogs/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext  = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed, true) && $_FILES['image_file']['size'] <= 5 * 1024 * 1024) {
            $filename = time() . '-' . preg_replace('/[^a-z0-9]+/', '-', strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_FILENAME))) . '.' . $ext;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $filename)) {
                $image_url = 'assets/images/blogs/' . $filename;
            }
        } else {
            $errors[] = 'Image must be jpg, png, gif or webp, and under 5MB.';
        }
    }

    // Auto-generate slug
    if ($slug === '' && $title !== '') {
        $slug = mb_strtolower(trim($title), 'UTF-8');
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
    }

    if ($title === '')   $errors[] = 'Title is required.';
    if ($slug === '')    $errors[] = 'Slug is required.';
    if ($content === '') $errors[] = 'Content is required.';

    // Check slug uniqueness
    if (empty($errors)) {
        $check = db()->prepare('SELECT id FROM blog_posts WHERE slug = ? AND id != ?');
        $check->execute([$slug, $id]);
        if ($check->fetch()) $errors[] = 'Slug already exists. Please change it.';
    }

    if (empty($errors)) {
        if ($id) {
            db()->prepare(
                'UPDATE blog_posts SET title=?, slug=?, category_id=?, excerpt=?, content=?, image_url=?, author=?, status=?, published_at=? WHERE id=?'
            )->execute([$title, $slug, $category_id, $excerpt, $content, $image_url, $author, $status, $published_at, $id]);
        } else {
            db()->prepare(
                'INSERT INTO blog_posts (title, slug, category_id, excerpt, content, image_url, author, status, published_at) VALUES (?,?,?,?,?,?,?,?,?)'
            )->execute([$title, $slug, $category_id, $excerpt, $content, $image_url, $author, $status, $published_at]);
        }
        header('Location: blogs.php?saved=1');
        exit;
    }

    $item = compact('title', 'slug', 'category_id', 'excerpt', 'content', 'image_url', 'author', 'status', 'published_at');
}

$categories = db()->query('SELECT id, name FROM blog_categories ORDER BY name ASC')->fetchAll();

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

  <?php if (empty($categories)): ?>
  <div class="alert alert--info">
    <i class="fa-solid fa-circle-info"></i> No categories yet —
    <a href="blog-categories.php">add one</a> first (optional, posts can also have no category).
  </div>
  <?php endif; ?>

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
        <label for="category_id">Category</label>
        <select id="category_id" name="category_id">
          <option value="0">— None —</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($item['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
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
      <p class="form-hint">Upload an image, or provide a URL below instead. Max 5MB.</p>
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
               value="<?= htmlspecialchars($item['author'] ?? 'Home Sure Team') ?>"
               placeholder="Home Sure Team">
      </div>
      <div class="form-group">
        <label for="published_at">Publish Date</label>
        <input type="datetime-local" id="published_at" name="published_at"
               value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($item['published_at'] ?? 'now'))) ?>">
      </div>
    </div>

    <div class="form-group">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published (visible on website)</option>
        <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft (hidden)</option>
      </select>
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

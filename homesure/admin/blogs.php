<?php
require_once '_auth.php';

$PAGE_TITLE = 'Blog Posts';
$ACTIVE_NAV = 'blogs';

// ── Handle actions ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($_POST['action'] === 'delete') {
        $img = db()->prepare('SELECT image_url FROM blog_posts WHERE id = ?');
        $img->execute([$id]);
        $imgPath = $img->fetchColumn();
        db()->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
        if ($imgPath && !str_starts_with($imgPath, 'http') && file_exists(__DIR__ . '/../' . $imgPath)) {
            @unlink(__DIR__ . '/../' . $imgPath);
        }
    } elseif ($_POST['action'] === 'toggle') {
        db()->prepare("UPDATE blog_posts SET status = IF(status='published','draft','published') WHERE id = ?")->execute([$id]);
    }
    header('Location: blogs.php');
    exit;
}

// ── Filter by category ────────────────────────────────────────────────────────
$filterCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sql = 'SELECT bp.*, bc.name AS category_name FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id';
$params = [];
if ($filterCategory) {
    $sql .= ' WHERE bp.category_id = ?';
    $params[] = $filterCategory;
}
$sql .= ' ORDER BY bp.published_at DESC, bp.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

$categories = db()->query('SELECT id, name FROM blog_categories ORDER BY name ASC')->fetchAll();

include '_header.php';
?>

<?php if (isset($_GET['saved'])): ?>
<div class="alert alert--success"><i class="fa-solid fa-circle-check"></i> Blog post saved successfully.</div>
<?php endif; ?>

<div class="card">
  <div class="card__head">
    <h2>All Posts <span style="color:#94a3b8;font-weight:400">(<?= count($posts) ?>)</span></h2>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <form method="GET" style="display:flex;gap:8px;align-items:center">
        <select name="category" onchange="this.form.submit()" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;font-size:.82rem">
          <option value="0">All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $filterCategory == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </form>
      <a href="blogs-edit.php" class="btn btn--primary btn--sm">
        <i class="fa-solid fa-plus"></i> Add Post
      </a>
    </div>
  </div>

  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Category</th>
          <th>Author</th>
          <th>Published</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($posts)): ?>
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <i class="fa-solid fa-blog"></i>
              <p>No blog posts yet. <a href="blogs-edit.php">Add the first one</a>.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($posts as $p): ?>
        <tr>
          <td data-label="Image">
            <?php if ($p['image_url']): ?>
              <img src="<?= str_starts_with($p['image_url'], 'http') ? htmlspecialchars($p['image_url']) : '../' . htmlspecialchars($p['image_url']) ?>" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:4px">
            <?php else: ?>
              <div class="img-placeholder"><i class="fa-solid fa-image"></i></div>
            <?php endif; ?>
          </td>
          <td data-label="Title">
            <strong style="font-size:.85rem"><?= htmlspecialchars(mb_substr($p['title'], 0, 50)) ?><?= mb_strlen($p['title']) > 50 ? '…' : '' ?></strong>
            <br><small style="color:#94a3b8">/blog-post.php?slug=<?= htmlspecialchars($p['slug']) ?></small>
          </td>
          <td data-label="Category"><span style="font-size:.8rem;padding:2px 8px;border-radius:4px;background:#f1f5f9"><?= htmlspecialchars($p['category_name'] ?? '—') ?></span></td>
          <td data-label="Author" style="font-size:.82rem;color:#64748b"><?= htmlspecialchars($p['author']) ?></td>
          <td data-label="Published" style="font-size:.82rem;color:#64748b"><?= $p['published_at'] ? date('M d, Y', strtotime($p['published_at'])) : '—' ?></td>
          <td data-label="Status">
            <form method="POST" style="display:inline">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <input type="hidden" name="action" value="toggle">
              <button type="submit" class="btn btn--sm <?= $p['status'] === 'published' ? 'btn--success' : 'btn--secondary' ?>">
                <i class="fa-solid fa-<?= $p['status'] === 'published' ? 'eye' : 'eye-slash' ?>"></i>
                <?= $p['status'] === 'published' ? 'Published' : 'Draft' ?>
              </button>
            </form>
          </td>
          <td data-label="Actions">
            <div style="display:flex;gap:5px">
              <a href="blogs-edit.php?id=<?= $p['id'] ?>" class="btn btn--sm btn--secondary">
                <i class="fa-solid fa-pen"></i> Edit
              </a>
              <form method="POST" style="display:inline"
                    onsubmit="return confirm('Delete this blog post?')">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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

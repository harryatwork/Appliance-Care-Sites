<?php
require_once '_auth.php';

$PAGE_TITLE = 'Blog Categories';
$ACTIVE_NAV = 'blog-categories';

$errors = [];
$success = '';

function slugify(string $s): string {
    $s = mb_strtolower(trim($s), 'UTF-8');
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim($s, '-');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $errors[] = 'Category name is required.';
        } else {
            $slug = slugify($name);
            $check = db()->prepare('SELECT id FROM blog_categories WHERE slug = ?');
            $check->execute([$slug]);
            if ($check->fetch()) {
                $errors[] = 'A category with that name already exists.';
            } else {
                db()->prepare('INSERT INTO blog_categories (name, slug) VALUES (?, ?)')->execute([$name, $slug]);
                $success = 'Category added.';
            }
        }
    } elseif ($action === 'rename') {
        $id   = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        if ($id && $name !== '') {
            db()->prepare('UPDATE blog_categories SET name = ?, slug = ? WHERE id = ?')->execute([$name, slugify($name), $id]);
            $success = 'Category renamed.';
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            // Posts in this category fall back to "no category" (FK is ON DELETE SET NULL)
            db()->prepare('DELETE FROM blog_categories WHERE id = ?')->execute([$id]);
            $success = 'Category deleted.';
        }
    }
}

$categories = db()->query(
    'SELECT bc.*, (SELECT COUNT(*) FROM blog_posts WHERE category_id = bc.id) AS post_count
     FROM blog_categories bc ORDER BY bc.name ASC'
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
  <div class="card__head"><h2>Add Category</h2></div>
  <form method="POST" style="display:flex;gap:10px;align-items:flex-end">
    <input type="hidden" name="action" value="add">
    <div class="form-group" style="flex:1;margin-bottom:0">
      <label for="cat-name">Category Name</label>
      <input type="text" id="cat-name" name="name" required placeholder="e.g. AC Care">
    </div>
    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Add</button>
  </form>
</div>

<div class="card" style="padding:0;max-width:560px">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Posts</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($categories)): ?>
        <tr>
          <td colspan="3">
            <div class="empty-state">
              <i class="fa-solid fa-tags"></i>
              <p>No categories yet.</p>
            </div>
          </td>
        </tr>
        <?php else: foreach ($categories as $cat): ?>
        <tr>
          <td data-label="Name">
            <form method="POST" style="display:flex;gap:8px;align-items:center">
              <input type="hidden" name="action" value="rename">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
              <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" style="max-width:220px" onchange="this.form.requestSubmit()">
            </form>
          </td>
          <td data-label="Posts"><?= (int)$cat['post_count'] ?></td>
          <td data-label="Actions">
            <form method="POST" onsubmit="return confirm('Delete this category? Posts in it will keep their content but show no category.')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
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

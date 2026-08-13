<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$post = null;
if ($slug !== '') {
    $stmt = db()->prepare(
        "SELECT bp.*, bc.name AS category_name
         FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id
         WHERE bp.slug = ? AND bp.status = 'published'"
    );
    $stmt->execute([$slug]);
    $post = $stmt->fetch() ?: null;
}

if ($post) {
    $pageTitle = $post['title'] . ' | Sure Fix Blog';
    $pageDescription = $post['excerpt'] ?: $post['title'];
} else {
    $pageTitle = 'Post Not Found | Sure Fix Blog';
    $pageDescription = 'This blog post could not be found.';
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="<?php echo SITE_URL; ?>/blog.php">Blog</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span><?php echo $post ? htmlspecialchars($post['title']) : 'Not Found'; ?></span>
  </nav>
</div>

<?php if ($post): ?>
<section class="page-hero">
  <div class="container">
    <div class="page-hero__icon reveal in-view"><i class="fa-solid fa-newspaper"></i></div>
    <h1 class="reveal in-view"><?php echo htmlspecialchars($post['title']); ?></h1>
  </div>
</section>

<?php if ($post['image_url']): ?>
<section class="page-banner">
  <img class="page-banner__img" src="<?php echo str_starts_with($post['image_url'], 'http') ? htmlspecialchars($post['image_url']) : SITE_URL . '/' . htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy">
</section>
<?php endif; ?>

<section class="pad-sm">
  <div class="container">
    <article class="blog-post glass reveal" style="padding:36px 34px;">
      <div class="blog-post__meta">
        <span><i class="fa-regular fa-calendar"></i> <?php echo date('F j, Y', strtotime($post['published_at'] ?: $post['created_at'])); ?></span>
        <?php if ($post['category_name']): ?><span><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($post['category_name']); ?></span><?php endif; ?>
        <span><i class="fa-regular fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></span>
      </div>
      <div class="blog-post__body">
        <?php
        // Content is stored as author-entered HTML (admin panel field is
        // explicitly "Content (HTML)") — trusted content from a logged-in
        // admin, not user input, so it's echoed directly rather than
        // htmlspecialchars()'d/escaped.
        echo $post['content'];
        ?>
      </div>
    </article>
    <div style="text-align:center;margin-top:30px;">
      <a href="<?php echo SITE_URL; ?>/blog.php" class="btn btn--glass"><i class="fa-solid fa-arrow-left"></i> Back to Blog</a>
    </div>
  </div>
</section>
<?php else: ?>
<section class="pad">
  <div class="container" style="text-align:center;">
    <h1>Post Not Found</h1>
    <p style="color:var(--gray);margin:14px 0 24px;">The article you're looking for doesn't exist or may have been moved.</p>
    <a href="<?php echo SITE_URL; ?>/blog.php" class="btn btn--primary"><i class="fa-solid fa-arrow-left"></i> Back to Blog</a>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/section-cta.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

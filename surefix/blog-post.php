<?php
require_once __DIR__ . '/includes/blog-data.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$post = $slug !== '' ? findBlogPostBySlug($blogPosts, $slug) : null;

if ($post) {
    $pageTitle = $post['title'] . ' | Sure Fix Blog';
    $pageDescription = $post['excerpt'];
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
    <div class="page-hero__icon reveal in-view"><i class="fa-solid <?php echo htmlspecialchars($post['icon']); ?>"></i></div>
    <h1 class="reveal in-view"><?php echo htmlspecialchars($post['title']); ?></h1>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <article class="blog-post glass reveal" style="padding:36px 34px;">
      <div class="blog-post__meta">
        <span><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($post['date']); ?></span>
        <span><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($post['category']); ?></span>
      </div>
      <div class="blog-post__body">
        <?php foreach (explode("\n\n", $post['body']) as $paragraph): ?>
        <p><?php echo htmlspecialchars($paragraph); ?></p>
        <?php endforeach; ?>
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

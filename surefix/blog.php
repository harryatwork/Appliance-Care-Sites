<?php
$pageTitle = 'Blog | Appliance Care Tips from Sure Fix';
$pageDescription = 'Tips and guides to help you get more life out of your home appliances, from the Sure Fix team in Bengaluru.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/blog-data.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Blog</span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <div class="page-hero__icon reveal in-view"><i class="fa-solid fa-newspaper"></i></div>
    <h1 class="reveal in-view">News &amp; Articles</h1>
    <p class="reveal in-view">Tips and guides to help you get more life out of your home appliances.</p>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <div class="blog-grid">
      <?php foreach ($blogPosts as $post): ?>
      <article class="blog-card glass reveal">
        <div class="blog-card__media">
          <span class="blog-card__tag"><?php echo htmlspecialchars($post['category']); ?></span>
          <i class="fa-solid <?php echo htmlspecialchars($post['icon']); ?>"></i>
        </div>
        <div class="blog-card__body">
          <div class="blog-card__date"><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($post['date']); ?></div>
          <h3><?php echo htmlspecialchars($post['title']); ?></h3>
          <a href="<?php echo SITE_URL; ?>/blog-post.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card__link">Read More <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/section-cta.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

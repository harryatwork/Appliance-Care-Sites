<?php
$pageTitle = 'Blog | Appliance Care Tips from Home Sure';
$pageDescription = 'Tips and guides to help you get more life out of your home appliances, from the Home Sure team in Bengaluru.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

$posts = db()->query(
    "SELECT bp.*, bc.name AS category_name
     FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id
     WHERE bp.status = 'published'
     ORDER BY bp.published_at DESC, bp.created_at DESC"
)->fetchAll();
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
    <?php if (empty($posts)): ?>
    <p style="text-align:center;color:var(--gray);">No articles published yet — check back soon.</p>
    <?php else: ?>
    <div class="blog-grid">
      <?php foreach ($posts as $post): ?>
      <article class="blog-card glass reveal">
        <div class="blog-card__media">
          <?php if ($post['category_name']): ?><span class="blog-card__tag"><?php echo htmlspecialchars($post['category_name']); ?></span><?php endif; ?>
          <?php if ($post['image_url']): ?>
            <img src="<?php echo str_starts_with($post['image_url'], 'http') ? htmlspecialchars($post['image_url']) : SITE_URL . '/' . htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy">
          <?php else: ?>
            <i class="fa-solid fa-newspaper"></i>
          <?php endif; ?>
        </div>
        <div class="blog-card__body">
          <div class="blog-card__date"><i class="fa-regular fa-calendar"></i> <?php echo $post['published_at'] ? date('F j, Y', strtotime($post['published_at'])) : date('F j, Y', strtotime($post['created_at'])); ?></div>
          <h3><?php echo htmlspecialchars($post['title']); ?></h3>
          <a href="<?php echo SITE_URL; ?>/blog-post.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card__link">Read More <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/section-cta.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

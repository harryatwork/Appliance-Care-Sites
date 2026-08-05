<?php
// Shared admin HTML header + sidebar.
// Expects: $PAGE_TITLE (string), $ACTIVE_NAV ('leads'|'services'|'testimonials')
try {
    $unread = db()->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn();
} catch (Exception $e) { $unread = 0; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($PAGE_TITLE ?? 'Admin') ?> — We Assist Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="admin-wrap">

  <!-- Sidebar -->
  <aside class="sidebar">
    <a href="leads.php" class="sidebar__logo">
      <img src="../assets/images/logo.png" alt="We Assist" class="sidebar__logo-img">
      <small>Admin Panel</small>
    </a>
    <nav class="sidebar__nav">
      <a href="leads.php" class="<?= ($ACTIVE_NAV ?? '') === 'leads' ? 'active' : '' ?>">
        <i class="fa-solid fa-inbox"></i> Leads
        <?php if ($unread > 0): ?>
          <span class="badge"><?= (int)$unread ?></span>
        <?php endif; ?>
      </a>
      <a href="services.php" class="<?= ($ACTIVE_NAV ?? '') === 'services' ? 'active' : '' ?>">
        <i class="fa-solid fa-wrench"></i> Services
      </a>
      <a href="testimonials.php" class="<?= ($ACTIVE_NAV ?? '') === 'testimonials' ? 'active' : '' ?>">
        <i class="fa-solid fa-star"></i> Testimonials
      </a>
      <a href="blogs.php" class="<?= ($ACTIVE_NAV ?? '') === 'blogs' ? 'active' : '' ?>">
        <i class="fa-solid fa-blog"></i> Blog Posts
      </a>
    </nav>
    <div class="sidebar__bottom">
      <a href="../index.php" target="_blank">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Website
      </a>
      <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="admin-main">
    <div class="admin-topbar">
      <h1 class="admin-topbar__title"><?= htmlspecialchars($PAGE_TITLE ?? 'Admin') ?></h1>
      <div class="admin-topbar__user">
        <i class="fa-solid fa-circle-user"></i>
        <?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?>
      </div>
    </div>
    <div class="admin-content">

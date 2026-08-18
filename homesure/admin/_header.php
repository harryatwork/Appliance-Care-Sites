<?php
// Shared admin HTML header + sidebar.
// Expects: $PAGE_TITLE (string), $ACTIVE_NAV ('leads'|'blogs'|'profile')
try {
    $unread = db()->query("SELECT COUNT(*) FROM leads WHERE status='New'")->fetchColumn();
} catch (Exception $e) { $unread = 0; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($PAGE_TITLE ?? 'Admin') ?> — Home Sure Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="admin-wrap">

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <a href="leads.php" class="sidebar__logo">
      <img src="../assets/images/logo.png" alt="Home Sure" class="sidebar__logo-img">
      <small>Admin Panel</small>
    </a>
    <nav class="sidebar__nav">
      <a href="leads.php" class="<?= ($ACTIVE_NAV ?? '') === 'leads' ? 'active' : '' ?>">
        <i class="fa-solid fa-inbox"></i> Leads
        <?php if ($unread > 0): ?>
          <span class="badge"><?= (int)$unread ?></span>
        <?php endif; ?>
      </a>
      <a href="technicians.php" class="<?= ($ACTIVE_NAV ?? '') === 'technicians' ? 'active' : '' ?>">
        <i class="fa-solid fa-screwdriver-wrench"></i> Technicians
      </a>
      <a href="blogs.php" class="<?= ($ACTIVE_NAV ?? '') === 'blogs' ? 'active' : '' ?>">
        <i class="fa-solid fa-blog"></i> Blog Posts
      </a>
      <a href="blog-categories.php" class="<?= ($ACTIVE_NAV ?? '') === 'blog-categories' ? 'active' : '' ?>">
        <i class="fa-solid fa-tags"></i> Blog Categories
      </a>
      <a href="profile.php" class="<?= ($ACTIVE_NAV ?? '') === 'profile' ? 'active' : '' ?>">
        <i class="fa-solid fa-user-gear"></i> Profile
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
      <div style="display:flex;align-items:center;gap:10px">
        <button type="button" class="admin-topbar__menu-btn" id="sidebarToggle" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
        <h1 class="admin-topbar__title"><?= htmlspecialchars($PAGE_TITLE ?? 'Admin') ?></h1>
      </div>
      <div class="admin-topbar__user">
        <i class="fa-solid fa-circle-user"></i>
        <?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?>
      </div>
    </div>
    <div class="admin-content">

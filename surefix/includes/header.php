<?php
/**
 * Shared header include: <head> + preloader + background mesh + topbar +
 * sticky nav + mobile drawer. Every page sets $pageTitle / $pageDescription
 * before requiring this file; both fall back to the homepage defaults below
 * if a page forgets to set them.
 *
 * Nav links use index.php#section so they resolve correctly from any page,
 * except Contact/Blog which point at their own dedicated pages.
 */
require_once __DIR__ . '/config.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Sure Fix — Premium Home Appliance Repair in Bengaluru';
}
if (!isset($pageDescription)) {
    $pageDescription = 'Sure Fix is a premium home appliance repair service in Bengaluru — refrigerators, washing machines, ACs, microwaves and more. Same-day service guaranteed.';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">

<link rel="icon" href="<?php echo SITE_URL; ?>/assets/images/logo.png" sizes="32x32" />
<link rel="icon" href="<?php echo SITE_URL; ?>/assets/images/logo.png" sizes="192x192" />
<link rel="apple-touch-icon" href="<?php echo SITE_URL; ?>/assets/images/logo.png" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
<script>window.SUREFIX_SITE_URL = <?php echo json_encode(SITE_URL); ?>;</script>
</head>
<body>

<div class="preloader" id="preloader">
  <div class="preloader__logo"><img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Sure Fix"></div>
</div>

<div class="bg-mesh" aria-hidden="true"><span></span><span></span><span></span></div>

<!-- Topbar -->
<div class="topbar">
  <div class="container">
    <div class="topbar__left">
      <span class="topbar__item"><i class="fa-regular fa-clock"></i> Mon–Sun: 8 AM – 10 PM</span>
      <span class="topbar__item"><i class="fa-solid fa-location-dot"></i> Bengaluru, Karnataka</span>
    </div>
    <div class="topbar__right">
      <a href="<?php echo SITE_FACEBOOK_URL; ?>" target="_blank" rel="noopener" class="topbar__social" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="<?php echo SITE_INSTAGRAM_URL; ?>" target="_blank" rel="noopener" class="topbar__social" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
      <a href="<?php echo SITE_X_URL; ?>" target="_blank" rel="noopener" class="topbar__social" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter"></i></a>
    </div>
  </div>
</div>

<!-- Navbar -->
<header class="navbar" id="navbar">
  <div class="container">
    <div class="navbar__inner glass">
      <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
        <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Sure Fix" class="logo__icon">
        <span class="logo__text">Sure Fix</span>
      </a>
      <ul class="nav-menu">
        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
        <li><a href="<?php echo SITE_URL; ?>/index.php#services">Services</a></li>
        <li><a href="<?php echo SITE_URL; ?>/index.php#brands">Brands</a></li>
        <li><a href="<?php echo SITE_URL; ?>/index.php#faq">FAQ</a></li>
        <li><a href="<?php echo SITE_URL; ?>/blog.php">Blog</a></li>
        <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
      </ul>
      <div class="navbar__actions">
        <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn glass" aria-label="Call us" style="width:44px;height:44px;padding:0;border-radius:50%;justify-content:center;"><i class="fa-solid fa-phone"></i></a>
        <a href="<?php echo SITE_URL; ?>/index.php#services" class="btn btn--primary">Book a Repair</a>
        <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span><span></span><span></span></button>
      </div>
    </div>
  </div>
</header>

<div class="drawer-overlay" id="drawerOverlay"></div>
<nav class="drawer" id="drawer">
  <button class="drawer__close btn" id="drawerClose" style="width:40px;height:40px;padding:0;border-radius:50%;background:var(--glass-bg-strong);border:1px solid var(--glass-border);justify-content:center;" aria-label="Close menu"><i class="fa-solid fa-xmark"></i></button>
  <ul>
    <li><a href="<?php echo SITE_URL; ?>/index.php" class="drawer-link">Home</a></li>
    <li><a href="<?php echo SITE_URL; ?>/index.php#services" class="drawer-link">Services</a></li>
    <li><a href="<?php echo SITE_URL; ?>/index.php#brands" class="drawer-link">Brands</a></li>
    <li><a href="<?php echo SITE_URL; ?>/index.php#faq" class="drawer-link">FAQ</a></li>
    <li><a href="<?php echo SITE_URL; ?>/blog.php" class="drawer-link">Blog</a></li>
    <li><a href="<?php echo SITE_URL; ?>/contact.php" class="drawer-link">Contact</a></li>
  </ul>
  <div class="drawer__cta">
    <a href="<?php echo SITE_URL; ?>/index.php#services" class="btn btn--primary" style="justify-content:center;">Book a Repair</a>
    <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--glass" style="justify-content:center;"><i class="fa-solid fa-phone"></i> <?php echo SITE_PHONE; ?></a>
  </div>
</nav>

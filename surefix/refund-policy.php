<?php
$pageTitle = 'Refund Policy | Sure Fix';
$pageDescription = 'Sure Fix\'s refund and cancellation policy for appliance repair bookings.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Refund Policy</span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <h1 class="reveal in-view">Refund Policy</h1>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <div class="legal-content glass reveal">
      <p class="legal-content__updated">Last updated: <?php echo date('F j, Y'); ?> — <strong>Draft content: please have this reviewed by a legal professional before the site goes live.</strong></p>

      <h2>1. Diagnostic Visits</h2>
      <p>Diagnostic visits are provided free of charge, so no refund applies if you choose not to proceed with a repair after diagnosis.</p>

      <h2>2. Cancellations</h2>
      <p>You may cancel a scheduled booking at no cost before a technician has been dispatched. If a technician has already been assigned or is en route, please cancel as early as possible so we can reassign them efficiently.</p>

      <h2>3. Refunds for Completed Repairs</h2>
      <p>If a repair does not resolve the reported issue, contact us within 7 days and we will re-inspect the appliance at no additional charge. Refunds for service charges are considered on a case-by-case basis if the issue cannot be resolved.</p>

      <h2>4. Spare Parts</h2>
      <p>Genuine spare parts fitted during a repair are covered by the applicable manufacturer or service warranty. Parts refunds are not offered once a part has been installed and the repair completed successfully.</p>

      <h2>5. How to Request a Refund</h2>
      <p>To request a refund or raise a concern about a completed repair, contact us at <?php echo SITE_EMAIL; ?> or <?php echo SITE_PHONE; ?> with your Booking ID.</p>

      <h2>6. Processing Time</h2>
      <p>Approved refunds are processed within 7–10 business days to the original mode of payment.</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

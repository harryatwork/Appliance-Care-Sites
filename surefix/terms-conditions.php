<?php
$pageTitle = 'Terms & Conditions | Sure Fix';
$pageDescription = 'Terms and conditions for using Sure Fix appliance repair services.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Terms &amp; Conditions</span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <h1 class="reveal in-view">Terms &amp; Conditions</h1>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <div class="legal-content glass reveal">
      <p class="legal-content__updated">Last updated: <?php echo date('F j, Y'); ?> — <strong>Draft content: please have this reviewed by a legal professional before the site goes live.</strong></p>

      <h2>1. Services</h2>
      <p>Sure Fix provides home appliance repair services in Bengaluru, including but not limited to washing machines, refrigerators, air conditioners, televisions, microwave ovens, and geysers.</p>

      <h2>2. Booking &amp; Scheduling</h2>
      <p>Service bookings made through this website are subject to technician availability. We aim to honor the requested time slot but may need to reschedule in rare cases; you'll be notified promptly if that happens.</p>

      <h2>3. Pricing &amp; Diagnostics</h2>
      <p>A diagnostic visit is provided at no charge. Repair costs are quoted upfront after diagnosis, and no work begins without your approval.</p>

      <h2>4. Spare Parts &amp; Warranty</h2>
      <p>We use genuine, manufacturer-approved spare parts wherever possible. Repairs carry a service warranty; specific warranty terms will be communicated at the time of service.</p>

      <h2>5. Customer Responsibilities</h2>
      <ul>
        <li>Provide accurate address and contact information for the service visit</li>
        <li>Ensure safe and reasonable access to the appliance being repaired</li>
        <li>Be present or have an authorized adult present during the service visit</li>
      </ul>

      <h2>6. Limitation of Liability</h2>
      <p>Sure Fix is not liable for pre-existing damage or issues unrelated to the specific repair performed, or for appliance failure due to factors outside our control (e.g. power surges after service completion).</p>

      <h2>7. Changes to These Terms</h2>
      <p>We may update these Terms &amp; Conditions from time to time. Continued use of our services after changes constitutes acceptance of the updated terms.</p>

      <h2>8. Contact Us</h2>
      <p>Questions about these terms can be directed to <?php echo SITE_EMAIL; ?> or <?php echo SITE_PHONE; ?>.</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

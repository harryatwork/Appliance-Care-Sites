<?php
$pageTitle = 'Privacy Policy | Sure Fix';
$pageDescription = 'How Sure Fix collects, uses, and protects your personal information.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Privacy Policy</span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <h1 class="reveal in-view">Privacy Policy</h1>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <div class="legal-content glass reveal">
      <p class="legal-content__updated">Last updated: <?php echo date('F j, Y'); ?> — <strong>Draft content: please have this reviewed by a legal professional before the site goes live.</strong></p>

      <h2>1. Information We Collect</h2>
      <p>When you use Sure Fix to book a service or contact us, we may collect your name, mobile number, email address, service address, and details about the appliance issue you're reporting.</p>

      <h2>2. How We Use Your Information</h2>
      <ul>
        <li>To schedule and manage your appliance repair booking</li>
        <li>To contact you about your service request via call, SMS, or WhatsApp</li>
        <li>To improve our services and respond to enquiries</li>
        <li>To send service-related notifications (booking confirmations, status updates)</li>
      </ul>

      <h2>3. Information Sharing</h2>
      <p>We do not sell your personal information. Your details are shared only with the technician assigned to your service request, solely for the purpose of completing that request.</p>

      <h2>4. Data Security</h2>
      <p>We take reasonable technical and organizational measures to protect your information from unauthorized access, alteration, or disclosure.</p>

      <h2>5. Your Rights</h2>
      <p>You may request access to, correction of, or deletion of your personal information by contacting us at <?php echo SITE_EMAIL; ?>.</p>

      <h2>6. Contact Us</h2>
      <p>If you have questions about this Privacy Policy, reach us at <?php echo SITE_EMAIL; ?> or <?php echo SITE_PHONE; ?>.</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

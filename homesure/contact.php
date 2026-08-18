<?php
$pageTitle = 'Contact Home Sure | Appliance Repair in Bengaluru';
$pageDescription = 'Get in touch with Home Sure for home appliance repair in Bengaluru. Call, WhatsApp, or send an enquiry — we typically respond within minutes.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Contact</span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <div class="page-hero__icon reveal in-view"><i class="fa-solid fa-headset"></i></div>
    <h1 class="reveal in-view">Get in Touch</h1>
    <p class="reveal in-view">Questions, feedback, or ready to book a repair? Reach us any way that's convenient — we usually respond within minutes.</p>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-form-wrap">
        <form id="contactForm" class="contact-form glass reveal" novalidate>
          <input type="text" name="website" class="form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
          <div class="form-row">
            <label for="cf-name">Full Name</label>
            <input type="text" id="cf-name" name="name" placeholder="Your name" required>
          </div>
          <div class="form-row">
            <label for="cf-mobile">Mobile Number</label>
            <input type="tel" id="cf-mobile" name="mobile" placeholder="10-digit mobile number" maxlength="10" inputmode="numeric" required>
          </div>
          <div class="form-row">
            <label for="cf-email">Email (optional)</label>
            <input type="email" id="cf-email" name="email" placeholder="you@example.com">
          </div>
          <div class="form-row">
            <label for="cf-message">Message</label>
            <textarea id="cf-message" name="message" placeholder="Tell us what's wrong and we'll get back to you"></textarea>
          </div>
          <p class="contact-form__hint" id="cfHint" hidden></p>
          <button type="submit" class="btn btn--primary"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
        </form>
        <div class="contact-form__success glass reveal" id="cfSuccess" hidden>
          <i class="fa-solid fa-circle-check"></i>
          <p>Thanks! We've received your message and will get back to you shortly.</p>
        </div>
      </div>

      <div class="contact-info">
        <div class="contact-info__card glass reveal">
          <span class="contact-info__icon"><i class="fa-solid fa-phone"></i></span>
          <div>
            <h3>Call Us</h3>
            <p><?php echo SITE_PHONE; ?></p>
            <div class="contact-info__actions" style="margin-top:12px;">
              <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--primary" style="padding:9px 18px;font-size:13px;"><i class="fa-solid fa-phone"></i> Call Now</a>
            </div>
          </div>
        </div>
        <div class="contact-info__card glass reveal">
          <span class="contact-info__icon"><i class="fa-brands fa-whatsapp"></i></span>
          <div>
            <h3>WhatsApp</h3>
            <p>Message us for a quick response.</p>
            <div class="contact-info__actions" style="margin-top:12px;">
              <a href="https://wa.me/<?php echo ltrim(SITE_PHONE_LINK, '+'); ?>" class="btn btn--glass" style="padding:9px 18px;font-size:13px;" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>
          </div>
        </div>
        <div class="contact-info__card glass reveal">
          <span class="contact-info__icon"><i class="fa-solid fa-location-dot"></i></span>
          <div>
            <h3>Visit Us</h3>
            <p><?php echo SITE_ADDRESS; ?></p>
          </div>
        </div>
        <div class="contact-info__card glass reveal">
          <span class="contact-info__icon"><i class="fa-regular fa-clock"></i></span>
          <div style="width:100%;">
            <h3>Business Hours</h3>
            <table class="hours-table">
              <tr><td>Monday – Sunday</td><td>8:00 AM – 10:00 PM</td></tr>
              <tr><td>Emergency Service</td><td>24/7</td></tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/section-cta.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- CTA banner (shared across index.php and every appliance page) -->
<section class="pad-sm">
  <div class="container">
    <div class="cta-banner glass reveal">
      <h2>Need Urgent Appliance Repair? We're One Call Away.</h2>
      <div style="display:flex;align-items:center;gap:26px;flex-wrap:wrap;">
        <div class="cta-banner__phone">
          <span class="cta-banner__phone-icon"><i class="fa-solid fa-phone"></i></span>
          <span><span>Call anytime</span><strong><?php echo SITE_PHONE; ?></strong></span>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--primary"><i class="fa-solid fa-phone"></i> Call Now</a>
          <a href="<?php echo SITE_URL; ?>/index.php#services" class="btn btn--glass"><i class="fa-solid fa-calendar-check"></i> Book Online</a>
        </div>
      </div>
    </div>
  </div>
</section>

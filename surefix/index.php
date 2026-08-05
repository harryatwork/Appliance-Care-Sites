<?php
$pageTitle = 'Sure Fix — Premium Home Appliance Repair in Bengaluru';
$pageDescription = 'Sure Fix is a premium home appliance repair service in Bengaluru — refrigerators, washing machines, ACs, microwaves and more. Same-day service guaranteed.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="hero" id="home">
  <div class="container">
    <div class="hero__grid">
      <div class="reveal in-view">
        <span class="hero__badge glass"><i class="fa-solid fa-star"></i> Rated 4.9/5 by 2,400+ Bengaluru homes</span>
        <h1>Fast, Reliable Appliance Repair — <span>Right at Your Doorstep</span></h1>
        <p class="hero__lead">Certified technicians, genuine spare parts, and transparent pricing for every appliance in your home. Book online in under a minute.</p>
        <div class="hero__cta">
          <a href="#services" class="btn btn--primary"><i class="fa-solid fa-calendar-check"></i> Book a Repair</a>
          <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--glass"><i class="fa-solid fa-phone-volume"></i> Call Now</a>
        </div>
        <div class="hero__trust glass">
          <div class="avatar-row"><span>R</span><span>P</span><span>A</span><span>+</span></div>
          <div class="hero__trust-text"><strong>4.9 <span class="stars">★★★★★</span></strong>50,000+ repairs completed</div>
        </div>
      </div>
      <div class="hero__visual reveal in-view">
        <div class="hero__visual-core">
          <div class="hero__quick-form glass">
            <span class="hero__quick-form-eyebrow"><i class="fa-solid fa-bolt"></i> Get a Call Back in 2 Minutes</span>
            <form id="quickEnquiryForm" novalidate>
              <div class="form-row">
                <input type="text" id="qeName" placeholder="Your name" aria-label="Your name" required>
              </div>
              <div class="form-row">
                <input type="tel" id="qeMobile" placeholder="10-digit mobile number" aria-label="Mobile number" maxlength="10" inputmode="numeric" required>
              </div>
              <p class="hero__quick-form-hint" id="qeHint">Please enter your name and a valid 10-digit mobile number.</p>
              <button type="submit" class="btn btn--primary hero__quick-form-submit">Request a Call Back <i class="fa-solid fa-arrow-right"></i></button>
            </form>
            <div class="hero__quick-form-success" id="qeSuccess" hidden>
              <i class="fa-solid fa-circle-check"></i>
              <p>Thanks! Our team will call you back shortly.</p>
            </div>
            <p class="hero__quick-form-note"><i class="fa-solid fa-shield-halved"></i> No spam, ever.</p>
          </div>
        </div>
        <div class="hero__chip hero__chip--1 glass"><i class="fa-solid fa-snowflake"></i> Refrigerator</div>
        <div class="hero__chip hero__chip--2 glass"><i class="fa-solid fa-wind"></i> AC Service</div>
        <div class="hero__chip hero__chip--3 glass"><i class="fa-solid fa-shirt"></i> Washing Machine</div>
        <div class="hero__chip hero__chip--4 glass"><i class="fa-solid fa-bolt"></i> Same-Day Fix</div>
      </div>
    </div>
  </div>
</section>

<div class="marquee-wrap glass">
  <div class="marquee-track">
    <span><i class="fa-solid fa-shield-halved"></i> 24/7 Emergency Service</span>
    <span><i class="fa-solid fa-certificate"></i> Certified Technicians</span>
    <span><i class="fa-solid fa-gears"></i> Genuine Spare Parts</span>
    <span><i class="fa-solid fa-bolt"></i> Same-Day Service</span>
    <span><i class="fa-solid fa-map-location-dot"></i> Pan-Bengaluru Coverage</span>
    <span><i class="fa-solid fa-tags"></i> Transparent Pricing</span>
    <span><i class="fa-solid fa-shield-halved"></i> 24/7 Emergency Service</span>
    <span><i class="fa-solid fa-certificate"></i> Certified Technicians</span>
    <span><i class="fa-solid fa-gears"></i> Genuine Spare Parts</span>
    <span><i class="fa-solid fa-bolt"></i> Same-Day Service</span>
    <span><i class="fa-solid fa-map-location-dot"></i> Pan-Bengaluru Coverage</span>
    <span><i class="fa-solid fa-tags"></i> Transparent Pricing</span>
  </div>
</div>

<?php require __DIR__ . '/includes/section-why.php'; ?>

<!-- Our Services (matches the 6 dedicated appliance pages in the FRD's page list) -->
<section class="pad" id="services">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><i class="fa-solid fa-circle"></i> Our Services</span>
      <h2>Repairs for Every Appliance in Your Home</h2>
      <p>From daily kitchen essentials to whole-home comfort systems — we fix it right, the first time.</p>
    </div>
    <div class="services-grid">
      <div class="svc-card glass svc-card--featured reveal">
        <span class="svc-card__icon"><i class="fa-solid fa-wind"></i></span>
        <h3>Air Conditioner</h3>
        <p>Gas top-up, deep cleaning, PCB &amp; compressor repair — our most-booked service.</p>
        <a href="<?php echo SITE_URL; ?>/air-conditioner.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><i class="fa-solid fa-snowflake"></i></span>
        <h3>Refrigerator</h3>
        <p>Cooling issues, gas refilling, compressor repair.</p>
        <a href="<?php echo SITE_URL; ?>/refrigerator.php" class="svc-card__link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><i class="fa-solid fa-shirt"></i></span>
        <h3>Washing Machine</h3>
        <p>Drum, motor, drainage &amp; control panel repairs.</p>
        <a href="<?php echo SITE_URL; ?>/washing-machine.php" class="svc-card__link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><i class="fa-solid fa-tv"></i></span>
        <h3>Television</h3>
        <p>Panel faults, sound issues, smart TV software problems.</p>
        <a href="<?php echo SITE_URL; ?>/television.php" class="svc-card__link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><i class="fa-solid fa-fire-burner"></i></span>
        <h3>Microwave &amp; Oven</h3>
        <p>Heating faults, sparking, turntable issues fixed fast.</p>
        <a href="<?php echo SITE_URL; ?>/microwave-oven.php" class="svc-card__link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><i class="fa-solid fa-droplet"></i></span>
        <h3>Geyser</h3>
        <p>Geyser installation, thermostat &amp; element replacement.</p>
        <a href="<?php echo SITE_URL; ?>/geyser.php" class="svc-card__link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/section-brands.php'; ?>

<!-- How It Works -->
<section class="pad" id="process">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><i class="fa-solid fa-circle"></i> How It Works</span>
      <h2>Book a Repair in 4 Simple Steps</h2>
      <p>A straightforward, no-surprises process from the first click to the final fix.</p>
    </div>
    <div class="process-row">
      <div class="process-step reveal">
        <div class="process-step__num">01</div>
        <h3>Book Online</h3>
        <p>Tell us the appliance and the problem — takes under a minute.</p>
      </div>
      <div class="process-step reveal">
        <div class="process-step__num">02</div>
        <h3>Free Diagnosis</h3>
        <p>Our technician inspects and quotes before any work begins.</p>
      </div>
      <div class="process-step reveal">
        <div class="process-step__num">03</div>
        <h3>Repair Completed</h3>
        <p>Genuine parts fitted, tested, and quality-checked on the spot.</p>
      </div>
      <div class="process-step reveal">
        <div class="process-step__num">04</div>
        <h3>Warranty Backed</h3>
        <p>Every repair comes with a service warranty for peace of mind.</p>
      </div>
    </div>
  </div>
</section>

<!-- Stats -->
<section class="pad-sm">
  <div class="container">
    <div class="stats-bar glass reveal">
      <div class="stat"><div class="stat__num" data-count="20">0<span>+</span></div><div class="stat__label">Years of Experience</div></div>
      <div class="stat"><div class="stat__num" data-count="50">0<span>K+</span></div><div class="stat__label">Repairs Completed</div></div>
      <div class="stat"><div class="stat__num" data-count="15">0<span>+</span></div><div class="stat__label">Areas Covered</div></div>
      <div class="stat"><div class="stat__num" data-count="98">0<span>%</span></div><div class="stat__label">Satisfaction Rate</div></div>
    </div>
  </div>
</section>

<!-- Customer Reviews -->
<section class="pad" id="reviews">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><i class="fa-solid fa-circle"></i> Customer Reviews</span>
      <h2>What Bengaluru Says About Us</h2>
      <p>Rated 4.9 out of 5 from over 2,400 verified customers.</p>
    </div>
    <div class="testi-grid">
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Booked a same-day AC repair and the technician arrived within two hours. Transparent pricing, no upselling."</p>
        <div class="testi-foot">
          <div class="testi-avatar">P</div>
          <div><div class="testi-name">Priya Menon</div><div class="testi-role">Koramangala, Bengaluru</div></div>
        </div>
      </div>
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Our refrigerator compressor gave up on a Sunday. Sure Fix had someone at our door within the hour."</p>
        <div class="testi-foot">
          <div class="testi-avatar">A</div>
          <div><div class="testi-name">Arvind Kumar</div><div class="testi-role">Indiranagar, Bengaluru</div></div>
        </div>
      </div>
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Finally a repair service that explains the issue in plain language before quoting a price. Highly recommend."</p>
        <div class="testi-foot">
          <div class="testi-avatar">S</div>
          <div><div class="testi-name">Sunita Rao</div><div class="testi-role">Whitefield, Bengaluru</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="pad" id="faq">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><i class="fa-solid fa-circle"></i> Get Answers</span>
      <h2>Frequently Asked Questions</h2>
      <p>Everything you need to know before booking your appliance repair.</p>
    </div>
    <div class="faq-wrap">
      <div class="faq-item glass open reveal">
        <div class="faq-q"><h3>How quickly can a technician reach me?</h3><span class="plus"><i class="fa-solid fa-plus"></i></span></div>
        <div class="faq-a"><p>In most serviceable areas of Bengaluru, we offer same-day appointments — often within 2–4 hours of booking.</p></div>
      </div>
      <div class="faq-item glass reveal">
        <div class="faq-q"><h3>Do you use genuine spare parts?</h3><span class="plus"><i class="fa-solid fa-plus"></i></span></div>
        <div class="faq-a"><p>Yes — every part is sourced from authorized distributors, and every repair carries a service warranty.</p></div>
      </div>
      <div class="faq-item glass reveal">
        <div class="faq-q"><h3>Is the diagnostic visit really free?</h3><span class="plus"><i class="fa-solid fa-plus"></i></span></div>
        <div class="faq-a"><p>Absolutely. We inspect and quote before any work begins — you only pay once you approve the repair.</p></div>
      </div>
      <div class="faq-item glass reveal">
        <div class="faq-q"><h3>Which areas of Bengaluru do you cover?</h3><span class="plus"><i class="fa-solid fa-plus"></i></span></div>
        <div class="faq-a"><p>We currently operate across Koramangala, Indiranagar, Whitefield, HSR Layout and 15+ other localities.</p></div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/section-cta.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

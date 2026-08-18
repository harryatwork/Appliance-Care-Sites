<?php
$pageTitle = 'Home Sure — Premium Home Appliance Repair in Bengaluru';
$pageDescription = 'Home Sure is a premium home appliance repair service in Bengaluru — refrigerators, washing machines, ACs, microwaves and more. Same-day service guaranteed.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="hero" id="home">
  <div class="container">
    <div class="hero__grid">
      <div class="reveal in-view">
        <h1>Fast, Reliable Appliance Repair — <span>Right at Your Doorstep</span></h1>
        <p class="hero__lead">Certified technicians, genuine spare parts, and transparent pricing for every appliance in your home. Book online in under a minute.</p>
        <div class="hero__cta">
          <a href="#services" class="btn btn--primary"><i class="fa-solid fa-calendar-check"></i> Book a Repair</a>
          <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--glass"><i class="fa-solid fa-phone-volume"></i> Call Now</a>
        </div>
        <div class="hero__trust glass">
          <div class="avatar-row"><span><i class="fa-solid fa-user"></i></span><span><i class="fa-solid fa-user"></i></span><span><i class="fa-solid fa-user"></i></span><span>+</span></div>
          <div class="hero__trust-text"><strong>4.9 <span class="stars">★★★★★</span></strong>50,000+ repairs completed</div>
        </div>
      </div>
      <div class="hero__visual reveal in-view">
        <div class="hero__visual-core">
          <div class="hero__quick-form">
            <span class="hero__quick-form-eyebrow"><i class="fa-solid fa-bolt"></i> Get a Call Back in 2 Minutes</span>
            <div class="hero__quick-form-body">
              <form id="quickEnquiryForm" novalidate>
                <input type="text" name="website" class="form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
                <div class="form-row">
                  <input type="text" id="qeName" placeholder="Your name" aria-label="Your name" required>
                </div>
                <div class="form-row">
                  <input type="tel" id="qeMobile" placeholder="10-digit mobile number" aria-label="Mobile number" maxlength="10" inputmode="numeric" required>
                </div>
                <p class="hero__quick-form-hint" id="qeHint">Please enter your name and a valid 10-digit mobile number.</p>
                <button type="submit" class="btn btn--primary hero__quick-form-submit">Request a Call Back <i class="fa-solid fa-arrow-right"></i></button>
                <p class="hero__quick-form-consent">By submitting, you consent to be contacted by Home Sure and agree to our <a href="<?php echo SITE_URL; ?>/privacy-policy.php">Privacy Policy</a>.</p>
              </form>
              <div class="hero__quick-form-success" id="qeSuccess" hidden>
                <i class="fa-solid fa-circle-check"></i>
                <p>Thanks! Our team will call you back shortly.</p>
              </div>
              <p class="hero__quick-form-note"><i class="fa-solid fa-shield-halved"></i> No spam, ever.</p>
            </div>
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
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/air-conditioner.png" alt="" loading="lazy"></span>
        <h3>Air Conditioner</h3>
        <p>Gas top-up, deep cleaning &amp; compressor repair — our most-booked service.</p>
        <a href="<?php echo SITE_URL; ?>/air-conditioner.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/refrigerator.png" alt="" loading="lazy"></span>
        <h3>Refrigerator</h3>
        <p>Cooling issues, gas refilling &amp; compressor repair — fixed fast.</p>
        <a href="<?php echo SITE_URL; ?>/refrigerator.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/washing-machine.png" alt="" loading="lazy"></span>
        <h3>Washing Machine</h3>
        <p>Drum, motor &amp; drainage issues — control panel repairs included.</p>
        <a href="<?php echo SITE_URL; ?>/washing-machine.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/television.png" alt="" loading="lazy"></span>
        <h3>Television</h3>
        <p>Panel faults, sound issues &amp; smart TV software problems — resolved fast.</p>
        <a href="<?php echo SITE_URL; ?>/television.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/microwave-oven.png" alt="" loading="lazy"></span>
        <h3>Microwave &amp; Oven</h3>
        <p>Heating faults, sparking &amp; turntable issues — fixed the same day.</p>
        <a href="<?php echo SITE_URL; ?>/microwave-oven.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/geyser.png" alt="" loading="lazy"></span>
        <h3>Geyser</h3>
        <p>Installation, thermostat &amp; element replacement — done safely.</p>
        <a href="<?php echo SITE_URL; ?>/geyser.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/ro.png" alt="" loading="lazy"></span>
        <h3>RO Water Purifier</h3>
        <p>Filter replacement, check-ups &amp; installation — clean water, sorted.</p>
        <a href="<?php echo SITE_URL; ?>/ro.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/dishwasher.png" alt="" loading="lazy"></span>
        <h3>Dishwasher</h3>
        <p>Power, leakage &amp; cleaning issues — fixed the same day.</p>
        <a href="<?php echo SITE_URL; ?>/dishwasher.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-card glass reveal">
        <span class="svc-card__icon"><img src="<?php echo SITE_URL; ?>/assets/images/appliances/dryer.png" alt="" loading="lazy"></span>
        <h3>Dryer</h3>
        <p>Heating, draining &amp; noise issues — resolved fast.</p>
        <a href="<?php echo SITE_URL; ?>/dryer.php" class="svc-card__link">Book Now <i class="fa-solid fa-arrow-right"></i></a>
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
      <h2>Book a Repair in 3 Simple Steps</h2>
      <p>A straightforward, no-surprises process from the first click to the final fix.</p>
    </div>
    <div class="process-row">
      <div class="process-step reveal">
        <div class="process-step__num">01</div>
        <h3>Book Online</h3>
        <p>Tell us the appliance and the problem — takes under a minute.</p>
      </div>
      <i class="fa-solid fa-arrow-right process-arrow" aria-hidden="true"></i>
      <div class="process-step reveal">
        <div class="process-step__num">02</div>
        <h3>Diagnosis &amp; Repair</h3>
        <p>Technician inspects and quotes upfront (diagnosis from ₹199), then fits genuine parts and tests the fix.</p>
      </div>
      <i class="fa-solid fa-arrow-right process-arrow" aria-hidden="true"></i>
      <div class="process-step reveal">
        <div class="process-step__num">03</div>
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
    <div class="testi-grid" id="testiGrid">
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Booked a same-day AC repair and the technician arrived within two hours. Transparent pricing, no upselling."</p>
        <div class="testi-foot">
          <div class="testi-avatar"><img src="<?php echo SITE_URL; ?>/assets/images/reviews/diya.jpg" alt="" loading="lazy"></div>
          <div><div class="testi-name">Diya Kapoor</div><div class="testi-role">Koramangala, Bengaluru</div></div>
        </div>
      </div>
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Our refrigerator compressor gave up on a Sunday. Home Sure had someone at our door within the hour."</p>
        <div class="testi-foot">
          <div class="testi-avatar"><img src="<?php echo SITE_URL; ?>/assets/images/reviews/rohit.jpg" alt="" loading="lazy"></div>
          <div><div class="testi-name">Rohit Nair</div><div class="testi-role">Indiranagar, Bengaluru</div></div>
        </div>
      </div>
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Booked my washing machine repair online in under a minute and got a same-day slot. Technician was professional and cleaned up after himself too."</p>
        <div class="testi-foot">
          <div class="testi-avatar"><img src="<?php echo SITE_URL; ?>/assets/images/reviews/sunil.jpg" alt="" loading="lazy"></div>
          <div><div class="testi-name">Sunil Reddy</div><div class="testi-role">HSR Layout, Bengaluru</div></div>
        </div>
      </div>
      <div class="testi-card glass reveal">
        <i class="fa-solid fa-quote-left quote"></i>
        <p>"Finally a repair service that explains the issue in plain language before quoting a price. Highly recommend."</p>
        <div class="testi-foot">
          <div class="testi-avatar"><img src="<?php echo SITE_URL; ?>/assets/images/reviews/varun.jpg" alt="" loading="lazy"></div>
          <div><div class="testi-name">Varun Iyer</div><div class="testi-role">Whitefield, Bengaluru</div></div>
        </div>
      </div>
    </div>
    <div class="testi-dots" id="testiDots" aria-hidden="true">
      <button class="testi-dot is-active" data-index="0" aria-label="Review 1"></button>
      <button class="testi-dot" data-index="1" aria-label="Review 2"></button>
      <button class="testi-dot" data-index="2" aria-label="Review 3"></button>
      <button class="testi-dot" data-index="3" aria-label="Review 4"></button>
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
        <div class="faq-q"><h3>How much does a diagnostic visit cost?</h3><span class="plus"><i class="fa-solid fa-plus"></i></span></div>
        <div class="faq-a"><p>A diagnostic visit starts at just ₹199. Our technician inspects the appliance and quotes upfront — you only pay for the repair once you approve it.</p></div>
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

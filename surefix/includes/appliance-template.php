<?php
/**
 * Shared template for every appliance page (washing-machine.php, etc).
 * The calling page sets these variables, then does:
 *   require __DIR__ . '/includes/appliance-template.php';
 *
 * Required variables:
 *   $pageTitle, $pageDescription   — SEO (read by header.php)
 *   $applianceName                 — e.g. "Washing Machine"
 *   $applianceIconClass            — Font Awesome class, e.g. "fa-shirt"
 *   $heroTagline                   — one-line description under the H1
 *   $typeOptions                   — array of strings, e.g. ["Front Load", ...]
 *   $problemOptions                — array of strings
 *   $commonIssues                  — array of ['icon'=>.., 'title'=>.., 'desc'=>..]
 *   $faqs                          — array of ['q'=>.., 'a'=>..]
 *
 * Booking widget is the full 6-step FRD booking flow (Appliance/Problem →
 * Location + Google Map → Slot → Customer Details → Review → Thank You),
 * driven by assets/js/booking-flow.js. Step 1's chips are real accessible
 * radio/checkbox markup; steps 2-6 are shown/hidden by JS. "Confirm Booking"
 * generates a placeholder booking ID client-side only — nothing is written to
 * the database yet. Wiring this to api/submit-booking.php + the `bookings`
 * table is Phase 2.
 */
require_once __DIR__ . '/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span><?php echo htmlspecialchars($applianceName); ?></span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <div class="page-hero__icon reveal in-view"><i class="fa-solid <?php echo htmlspecialchars($applianceIconClass); ?>"></i></div>
    <h1 class="reveal in-view"><?php echo htmlspecialchars($applianceName); ?> Repair in Bengaluru</h1>
    <p class="reveal in-view"><?php echo htmlspecialchars($heroTagline); ?></p>
    <div class="page-hero__cta reveal in-view">
      <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--glass"><i class="fa-solid fa-phone-volume"></i> Call Now</a>
      <a href="#book" class="btn btn--primary"><i class="fa-solid fa-calendar-check"></i> Book a Repair</a>
    </div>
  </div>
</section>

<section class="pad-sm" id="book">
  <div class="container">
    <div class="booking-widget glass reveal" id="bookingWidget" data-appliance="<?php echo htmlspecialchars($applianceName); ?>">

      <div class="booking-progress" aria-hidden="true">
        <div class="booking-progress__step" data-step-dot="1"><span>1</span><label>Appliance</label></div>
        <div class="booking-progress__step" data-step-dot="2"><span>2</span><label>Location</label></div>
        <div class="booking-progress__step" data-step-dot="3"><span>3</span><label>Slot</label></div>
        <div class="booking-progress__step" data-step-dot="4"><span>4</span><label>Details</label></div>
        <div class="booking-progress__step" data-step-dot="5"><span>5</span><label>Review</label></div>
      </div>

      <form id="bookingForm" novalidate>

        <!-- Step 1: Appliance type + problem -->
        <div class="booking-step" data-step="1">
          <span class="booking-widget__label">Select your <?php echo htmlspecialchars(strtolower($applianceName)); ?> type</span>
          <div class="selector-group" role="radiogroup" aria-label="Appliance type">
            <?php foreach ($typeOptions as $i => $type): ?>
            <input type="radio" name="applianceType" id="type-<?php echo $i; ?>" class="selector-chip" <?php echo $i === 0 ? 'checked' : ''; ?>>
            <label for="type-<?php echo $i; ?>"><?php echo htmlspecialchars($type); ?></label>
            <?php endforeach; ?>
          </div>

          <span class="booking-widget__label">What's the problem?</span>
          <div class="selector-group" role="group" aria-label="Problem">
            <?php foreach ($problemOptions as $i => $problem): ?>
            <input type="checkbox" name="problem[]" id="problem-<?php echo $i; ?>" class="selector-chip">
            <label for="problem-<?php echo $i; ?>"><?php echo htmlspecialchars($problem); ?></label>
            <?php endforeach; ?>
          </div>
          <p class="booking-widget__hint" data-hint="1">Please select at least one problem to continue.</p>

          <div class="booking-widget__actions">
            <span></span>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 2: Location -->
        <div class="booking-step" data-step="2">
          <span class="booking-widget__label">Where should we send the technician?</span>
          <div class="form-row">
            <label for="bookAddress">Address</label>
            <input type="text" id="bookAddress" placeholder="Flat / house no., street, area" autocomplete="off">
          </div>
          <button type="button" class="btn btn--glass btn--sm" data-action="locate"><i class="fa-solid fa-location-crosshairs"></i> Use My Current Location</button>
          <div class="booking-map" id="bookingMap" data-lat="12.9716" data-lng="77.5946">
            <div class="booking-map__placeholder"><i class="fa-solid fa-map-location-dot"></i> Map loads when you reach this step</div>
          </div>
          <div class="form-row">
            <label for="bookNotes">Landmark / notes (optional)</label>
            <textarea id="bookNotes" rows="2" placeholder="e.g. Near XYZ apartments, gate 2"></textarea>
          </div>
          <p class="booking-widget__hint" data-hint="2">Please enter your address to continue.</p>

          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 3: Slot -->
        <div class="booking-step" data-step="3">
          <div class="form-row">
            <label for="bookDate">Preferred date</label>
            <input type="date" id="bookDate">
          </div>
          <span class="booking-widget__label">Available time slots</span>
          <div class="slot-grid" id="slotGrid"></div>
          <p class="booking-widget__hint" data-hint="3">Please select a date and time slot to continue.</p>

          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 4: Customer details -->
        <div class="booking-step" data-step="4">
          <div class="form-row">
            <label for="bookName">Full name</label>
            <input type="text" id="bookName" placeholder="Your name">
          </div>
          <div class="form-row">
            <label for="bookMobile">Mobile number</label>
            <input type="tel" id="bookMobile" placeholder="10-digit mobile number" maxlength="10" inputmode="numeric">
          </div>
          <div class="form-row">
            <label for="bookEmail">Email (optional)</label>
            <input type="email" id="bookEmail" placeholder="you@example.com">
          </div>
          <p class="booking-widget__hint" data-hint="4">Please enter your name and a valid 10-digit mobile number.</p>

          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 5: Review -->
        <div class="booking-step" data-step="5">
          <span class="booking-widget__label">Review your booking</span>
          <div class="review-summary" id="reviewSummary"></div>

          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="confirm"><i class="fa-solid fa-check"></i> Confirm Booking</button>
          </div>
        </div>

        <!-- Step 6: Thank you -->
        <div class="booking-step" data-step="6">
          <div class="booking-thankyou">
            <span class="booking-thankyou__icon"><i class="fa-solid fa-circle-check"></i></span>
            <h3>Booking Confirmed!</h3>
            <p>Thank you — a Sure Fix technician will call to confirm your visit shortly.</p>
            <div class="booking-thankyou__id">Booking ID: <strong id="bookingIdOut"></strong></div>
            <div class="booking-thankyou__window">Expected arrival window: <strong id="bookingWindowOut"></strong></div>
            <div class="booking-thankyou__actions">
              <a href="tel:<?php echo SITE_PHONE_LINK; ?>" class="btn btn--primary"><i class="fa-solid fa-phone"></i> Call Support</a>
              <a href="https://wa.me/<?php echo ltrim(SITE_PHONE_LINK, '+'); ?>" target="_blank" rel="noopener" class="btn btn--glass"><i class="fa-brands fa-whatsapp"></i> WhatsApp Us</a>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</section>

<script>window.SUREFIX_MAPS_KEY = <?php echo json_encode(GOOGLE_MAPS_API_KEY); ?>;</script>
<script src="<?php echo SITE_URL; ?>/assets/js/booking-flow.js" defer></script>

<section class="pad" id="issues">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><i class="fa-solid fa-circle"></i> Common Issues</span>
      <h2>Problems We Fix in Your <?php echo htmlspecialchars($applianceName); ?></h2>
      <p>If it's not on this list, call us anyway — we've likely seen it before.</p>
    </div>
    <div class="issues-grid">
      <?php foreach ($commonIssues as $issue): ?>
      <div class="issue-item glass reveal">
        <span class="issue-item__icon"><i class="fa-solid <?php echo htmlspecialchars($issue['icon']); ?>"></i></span>
        <div><h3><?php echo htmlspecialchars($issue['title']); ?></h3><p><?php echo htmlspecialchars($issue['desc']); ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/section-why.php'; ?>
<?php require __DIR__ . '/section-brands.php'; ?>

<section class="pad" id="faq">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><i class="fa-solid fa-circle"></i> Get Answers</span>
      <h2><?php echo htmlspecialchars($applianceName); ?> Repair — FAQs</h2>
      <p>Everything you need to know before booking your repair.</p>
    </div>
    <div class="faq-wrap">
      <?php foreach ($faqs as $i => $faq): ?>
      <div class="faq-item glass reveal <?php echo $i === 0 ? 'open' : ''; ?>">
        <div class="faq-q"><h3><?php echo htmlspecialchars($faq['q']); ?></h3><span class="plus"><i class="fa-solid fa-plus"></i></span></div>
        <div class="faq-a"><p><?php echo htmlspecialchars($faq['a']); ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/section-cta.php'; ?>

<?php require_once __DIR__ . '/footer.php'; ?>

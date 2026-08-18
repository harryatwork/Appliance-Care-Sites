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
 *   $problemOptions                — array of strings (multi-select checkboxes).
 *                                    OR set $problemGroups instead (not both) —
 *                                    ['Repair' => [...], 'Service' => [...]] —
 *                                    for appliances where the client wants the
 *                                    problem list split into two labeled
 *                                    sections (currently AC, TV). Rendered as
 *                                    a single radio group spanning both
 *                                    sections, so only one option total can be
 *                                    picked across the two groups.
 *   $commonIssues                  — array of ['icon'=>.., 'title'=>.., 'desc'=>..]
 *   $faqs                          — array of ['q'=>.., 'a'=>..]
 *
 * Booking widget is the full 8-step flow (Appliance Type → Brand → Problem →
 * Location → Schedule → Contact Details → Verify OTP → Review), driven by
 * assets/js/booking-flow.js, matching the client's reference booking-flow
 * design (clientFeedback/02/*.html — "template 2" specifically for the
 * Location and Schedule steps) adapted to our glass/orange brand. Selections
 * use real <input radio/checkbox> under the hood styled as icon-cards, so
 * they stay keyboard/screen-reader accessible with zero JS; the step
 * machinery itself (which step is visible, geocoding, slot generation, OTP
 * mock) needs JS regardless. "Confirm Booking" generates a placeholder
 * booking ID client-side only — nothing is written to the database yet.
 * Wiring this to api/submit-booking.php + the `bookings` table is Phase 2.
 * The mock OTP step (code 1234) is UI-only for the same reason. There is no
 * separate service/price-selection step — every visit has a flat ₹199+
 * diagnostic charge (shown on the Review step), repair cost is quoted
 * on-site after inspection.
 */
require_once __DIR__ . '/header.php';

// Brand list — identical across every appliance page, so defined once here
// rather than duplicated in each of the 6 appliance page files.
$brandOptions = ['LG', 'Samsung', 'Whirlpool', 'IFB', 'Bosch', 'Godrej', 'Voltas', 'Haier', 'Panasonic', 'Blue Star', 'Daikin', 'Hitachi', 'Other / Not Sure'];

/** Best-effort icon for a type/problem label, keyword-matched so appliance
 * pages don't each need to hand-assign an icon per option. Order matters:
 * more specific / multi-word keywords are listed before the shorter,
 * generic ones they could otherwise be shadowed by (e.g. "uninstallation"
 * must be checked before "installation", since it contains it). */
function bookingOptionIcon($label, $fallback) {
    static $map = [
        // types
        'front load' => 'fa-shirt', 'top load' => 'fa-shirt', 'semi automatic' => 'fa-shirt',
        'single door' => 'fa-box', 'double door' => 'fa-box-open', 'side by side' => 'fa-box-open',
        'side-by-side' => 'fa-box-open', 'deep freezer' => 'fa-box', 'multi door' => 'fa-box-open', 'mini' => 'fa-box',
        'split ac' => 'fa-wind', 'tower ac' => 'fa-wind', 'window ac' => 'fa-wind', 'central ac' => 'fa-wind',
        'cassette ac' => 'fa-wind', 'portable ac' => 'fa-wind',
        'led' => 'fa-tv', 'smart tv' => 'fa-tv', 'oled' => 'fa-tv', 'lcd' => 'fa-tv',
        'solo' => 'fa-fire-burner', 'grill' => 'fa-fire-burner', 'convection' => 'fa-fire-burner',
        'heat pump' => 'fa-leaf', 'vented' => 'fa-wind', 'condenser' => 'fa-box',
        'freestanding' => 'fa-box', 'built-in' => 'fa-box-open',
        'ro+uv+uf' => 'fa-filter', 'ro+uv' => 'fa-filter', 'alkaline' => 'fa-flask',
        'electric' => 'fa-bolt', 'instant' => 'fa-bolt', 'storage' => 'fa-droplet',
        // problems
        'not sure' => 'fa-circle-question', 'unknown issue' => 'fa-circle-question',
        'not cooling' => 'fa-temperature-low', 'excess cooling' => 'fa-snowflake',
        'not starting' => 'fa-power-off', 'not turning on' => 'fa-power-off', 'not working' => 'fa-power-off',
        'no power' => 'fa-plug', 'power issue' => 'fa-plug',
        'not spinning' => 'fa-rotate', 'no spin' => 'fa-rotate',
        'draining' => 'fa-faucet-drip', 'drainage' => 'fa-faucet-drip', 'not filling' => 'fa-faucet',
        'water leak' => 'fa-droplet', 'leakage' => 'fa-droplet', 'water quality' => 'fa-flask',
        'noise' => 'fa-volume-high', 'vibration' => 'fa-volume-high',
        'door lock' => 'fa-lock', 'door issue' => 'fa-door-closed', 'door seal' => 'fa-door-closed',
        'door not closing' => 'fa-door-closed', 'dirty dishes' => 'fa-utensils',
        'display' => 'fa-microchip', 'error code' => 'fa-microchip', 'frost' => 'fa-snowflake', 'ice' => 'fa-snowflake',
        'compressor' => 'fa-gear', 'odor' => 'fa-wind', 'smell' => 'fa-wind',
        'remote' => 'fa-satellite-dish', 'gas leakage' => 'fa-triangle-exclamation', 'gas refill' => 'fa-gas-pump',
        'gas' => 'fa-fire', 'black screen' => 'fa-tv', 'lines on screen' => 'fa-tv',
        'sound' => 'fa-volume-high', 'smart features' => 'fa-satellite-dish',
        'heating' => 'fa-fire-burner', 'turntable' => 'fa-rotate', 'sparking' => 'fa-bolt',
        'button' => 'fa-square-h', 'touch issue' => 'fa-square-h',
        'hot water' => 'fa-temperature-high', 'too hot' => 'fa-temperature-high', 'tripping' => 'fa-bolt',
        'both installation' => 'fa-arrows-rotate', 'uninstallation' => 'fa-box', 'installation' => 'fa-toolbox',
        'cleaning' => 'fa-spray-can-sparkles', 'general service' => 'fa-clipboard-check',
        'diagnosis' => 'fa-magnifying-glass', 'check-up' => 'fa-magnifying-glass', 'checkup' => 'fa-magnifying-glass',
        'filter' => 'fa-filter', 'other' => 'fa-circle-question',
    ];
    $lower = strtolower($label);
    foreach ($map as $needle => $icon) {
        if (strpos($lower, $needle) !== false) return $icon;
    }
    return $fallback;
}

/** Real client-supplied photo for a type/problem label, where one exists
 * (assets/images/types/ and assets/images/problems/) — same keyword-match
 * approach as bookingOptionIcon(). Returns null (fall back to the FA icon)
 * when no photo was supplied for that option. */
function bookingOptionImage($label, $lookup) {
    $lower = strtolower($label);
    foreach ($lookup as $needle => $file) {
        if (strpos($lower, $needle) !== false) return $file;
    }
    return null;
}

// Per-type photos only exist for AC, Washing Machine and Refrigerator —
// every other appliance keeps its FA icon on the Type step (no distinct
// photo per type was supplied for them).
$typeImageMap = [
    'split ac' => 'split-ac.png', 'tower ac' => 'tower-ac.png',
    'window ac' => 'window-ac.png', 'central ac' => 'central-ac.png',
    'front load' => 'front-load.png', 'top load' => 'top-load.png', 'semi automatic' => 'semi-automatic.png',
    'single door' => 'single-door.png', 'double door' => 'double-door.png', 'side by side' => 'side-by-side.png',
    'deep freezer' => 'deep-freezer.png', 'multi door' => 'multi-door.png',
];

// Problem-step photos, keyword-matched the same way as the icon map —
// order matters where one phrase contains another (e.g. "both
// installation" / "uninstallation" must be checked before "installation").
$problemImageMap = [
    'bad smell' => 'bad-smell.png', 'button' => 'button-touch-issue.png', 'touch issue' => 'button-touch-issue.png',
    'cleaning' => 'cleaning.png', 'dirty dishes' => 'dirty-dishes.png', 'display issue' => 'display-issue.png',
    'door issue' => 'door-issue.png', 'draining' => 'draining-issue.png', 'error code' => 'error-code.png',
    'excess cooling' => 'excess-cooling.png', 'filter check' => 'filter-checkup.png',
    'filter replacement' => 'filter-replacement.png', 'gas refill' => 'gas-refill.png',
    'general service' => 'general-service.png', 'both installation' => 'both-installation-uninstallation.png',
    'uninstallation' => 'uninstallation.png', 'installation' => 'installation.png', 'noise' => 'noise.png',
    'not working' => 'not-working.png', 'no power' => 'no-power.png', 'no spin' => 'no-spin.png',
    'not cooling' => 'not-cooling.png', 'not filling' => 'not-filling.png',
    'not heating' => 'not-heating.png', 'no heating' => 'not-heating.png', 'power issue' => 'power-issue.png',
    'remote issue' => 'remote-issue.png', 'repair check' => 'repair-checkup.png', 'sparking' => 'sparking.png',
    'unknown issue' => 'unknown-issue.png', 'water leakage' => 'water-leakage.png',
    'water quality' => 'water-quality-issue.png',
];
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

<?php if (isset($bannerImage)): ?>
<section class="page-banner">
  <img class="page-banner__img" src="<?php echo SITE_URL; ?>/assets/images/banners/<?php echo htmlspecialchars($bannerImage); ?>" alt="<?php echo htmlspecialchars($applianceName); ?> repair service" loading="lazy">
</section>
<?php endif; ?>

<section class="pad-sm" id="book">
  <div class="container">
    <div class="booking-widget glass reveal" id="bookingWidget" data-appliance="<?php echo htmlspecialchars($applianceName); ?>">

      <div class="booking-progress" aria-hidden="true">
        <div class="booking-progress__top">
          <span class="booking-progress__label" id="bpLabel">Step 1 of 7</span>
          <span class="booking-progress__pct" id="bpPct">13%</span>
        </div>
        <div class="booking-progress__track"><div class="booking-progress__fill" id="bpFill"></div></div>
      </div>

      <form id="bookingForm" novalidate>

        <!-- Step 1: Appliance type -->
        <div class="booking-step" data-step="1">
          <span class="booking-widget__label">Select your <?php echo htmlspecialchars(strtolower($applianceName)); ?> type</span>
          <div class="option-grid" role="radiogroup" aria-label="Appliance type">
            <?php foreach ($typeOptions as $i => $type):
              $icon = bookingOptionIcon($type, $applianceIconClass);
              $img = bookingOptionImage($type, $typeImageMap);
            ?>
            <input type="radio" name="applianceType" id="type-<?php echo $i; ?>" class="option-input" <?php echo $i === 0 ? 'checked' : ''; ?>>
            <label for="type-<?php echo $i; ?>" class="option-card">
              <span class="option-card__check"><i class="fa-solid fa-check"></i></span>
              <span class="option-card__icon"><?php if ($img): ?><img src="<?php echo SITE_URL; ?>/assets/images/types/<?php echo htmlspecialchars($img); ?>" alt=""><?php else: ?><i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i><?php endif; ?></span>
              <span class="option-card__name"><?php echo htmlspecialchars($type); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
          <div class="booking-widget__actions">
            <span></span>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 2: Brand (optional) -->
        <div class="booking-step" data-step="2">
          <span class="booking-widget__label">Which brand is it? <small>(optional — helps us bring the right parts)</small></span>
          <div class="option-grid option-grid--brand" role="radiogroup" aria-label="Brand">
            <?php foreach ($brandOptions as $i => $brand): ?>
            <input type="radio" name="brand" id="brand-<?php echo $i; ?>" class="option-input">
            <label for="brand-<?php echo $i; ?>" class="option-card option-card--compact">
              <span class="option-card__check"><i class="fa-solid fa-check"></i></span>
              <span class="option-card__badge"><?php echo htmlspecialchars(strtoupper(substr($brand, 0, 2))); ?></span>
              <span class="option-card__name"><?php echo htmlspecialchars($brand); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <div class="booking-widget__actions-right">
              <button type="button" class="btn btn--text" data-action="skip-brand">Skip</button>
              <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
            </div>
          </div>
        </div>

        <!-- Step 3: Problem -->
        <div class="booking-step" data-step="3">
          <?php if (isset($problemGroups)): ?>
          <span class="booking-widget__label">What's the problem? <small>(choose one)</small></span>
          <?php $gi = 0; foreach ($problemGroups as $groupLabel => $groupItems): ?>
          <p class="problem-group-label"><?php echo htmlspecialchars($groupLabel); ?></p>
          <div class="option-grid" role="radiogroup" aria-label="<?php echo htmlspecialchars($groupLabel); ?>">
            <?php foreach ($groupItems as $problem):
              $icon = bookingOptionIcon($problem, 'fa-triangle-exclamation');
              $img = bookingOptionImage($problem, $problemImageMap);
              $gi++;
            ?>
            <input type="radio" name="problem" id="problem-<?php echo $gi; ?>" class="option-input" value="<?php echo htmlspecialchars($problem); ?>">
            <label for="problem-<?php echo $gi; ?>" class="option-card">
              <span class="option-card__check"><i class="fa-solid fa-check"></i></span>
              <span class="option-card__icon"><?php if ($img): ?><img src="<?php echo SITE_URL; ?>/assets/images/problems/<?php echo htmlspecialchars($img); ?>" alt=""><?php else: ?><i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i><?php endif; ?></span>
              <span class="option-card__name"><?php echo htmlspecialchars($problem); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
          <?php else: ?>
          <span class="booking-widget__label">What's the problem? <small>(select all that apply)</small></span>
          <div class="option-grid" role="group" aria-label="Problem">
            <?php foreach ($problemOptions as $i => $problem):
              $icon = bookingOptionIcon($problem, 'fa-triangle-exclamation');
              $img = bookingOptionImage($problem, $problemImageMap);
            ?>
            <input type="checkbox" name="problem[]" id="problem-<?php echo $i; ?>" class="option-input" value="<?php echo htmlspecialchars($problem); ?>">
            <label for="problem-<?php echo $i; ?>" class="option-card">
              <span class="option-card__check"><i class="fa-solid fa-check"></i></span>
              <span class="option-card__icon"><?php if ($img): ?><img src="<?php echo SITE_URL; ?>/assets/images/problems/<?php echo htmlspecialchars($img); ?>" alt=""><?php else: ?><i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i><?php endif; ?></span>
              <span class="option-card__name"><?php echo htmlspecialchars($problem); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <p class="booking-widget__hint" data-hint="3">Please select a problem to continue.</p>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 4: Location -->
        <div class="booking-step" data-step="4">
          <span class="booking-widget__label">Where should we send the technician?</span>

          <div class="locate-card" id="locateCard">
            <span class="locate-card__icon"><i class="fa-solid fa-location-crosshairs"></i></span>
            <h3>Use your current location</h3>
            <p>We'll detect your address automatically so you don't have to type it out.</p>
            <button type="button" class="btn btn--primary" data-action="locate"><i class="fa-solid fa-location-crosshairs"></i> Detect my location</button>
            <button type="button" class="link-btn" data-action="manual-address">Enter address manually</button>
          </div>

          <div class="detected-card" id="detectedCard" hidden>
            <i class="fa-solid fa-circle-check"></i>
            <div class="detected-card__body">
              <div class="detected-card__title">Location detected</div>
              <div class="detected-card__addr" id="detectedAddrOut"></div>
            </div>
            <button type="button" class="link-btn" data-action="manual-address">Edit</button>
          </div>

          <div id="addressFields" hidden>
            <div class="form-row">
              <label for="bookAddress">Full address <small>(house / flat, street, area)</small></label>
              <textarea id="bookAddress" rows="2" placeholder="e.g. 3rd Floor, Meridian Apartments, 100 Feet Road" autocomplete="off"></textarea>
            </div>
            <div class="form-row">
              <label for="bookLandmark">Landmark <small>(optional)</small></label>
              <input type="text" id="bookLandmark" placeholder="e.g. Near Sony World Signal">
            </div>
          </div>

          <div class="form-row">
            <label class="field-label">Save address as</label>
            <div class="chip-row" role="radiogroup" aria-label="Address tag">
              <input type="radio" name="addrTag" id="tag-0" class="option-input" checked>
              <label for="tag-0" class="chip-pill"><i class="fa-solid fa-house"></i> Home</label>
              <input type="radio" name="addrTag" id="tag-1" class="option-input">
              <label for="tag-1" class="chip-pill"><i class="fa-solid fa-briefcase"></i> Work</label>
              <input type="radio" name="addrTag" id="tag-2" class="option-input">
              <label for="tag-2" class="chip-pill"><i class="fa-solid fa-building"></i> Other</label>
            </div>
          </div>

          <div class="form-row">
            <label for="bookNotes">Address notes <small>(optional — gate code, parking, timing preference)</small></label>
            <textarea id="bookNotes" rows="2" placeholder="Anything our technician should know before arriving"></textarea>
          </div>

          <p class="booking-widget__hint" data-hint="4">Please enter your address to continue.</p>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 5: Schedule -->
        <div class="booking-step" data-step="5">
          <span class="booking-widget__label">Pick a date &amp; time</span>
          <div class="emergency-card" id="emergencyCard" data-action="toggle-emergency" tabindex="0" role="button" aria-pressed="false">
            <span class="emergency-card__icon"><i class="fa-solid fa-bolt"></i></span>
            <span class="emergency-card__body">
              <span class="emergency-card__title">Emergency — ASAP within 60 minutes</span>
              <span class="emergency-card__sub" id="emergencyCardSub">24/7 priority dispatch, small surcharge applies</span>
            </span>
            <span class="emergency-card__check"><i class="fa-solid fa-check"></i></span>
          </div>

          <div class="date-row" id="dateRow"></div>
          <div class="calendar-panel" id="calendarPanel" hidden></div>

          <div id="slotGroups"></div>

          <p class="booking-widget__hint" data-hint="5">Please select "Emergency" or a date and time slot to continue.</p>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 6: Contact details -->
        <div class="booking-step" data-step="6">
          <span class="booking-widget__label">Confirm your contact details</span>
          <p class="booking-widget__sublabel">So our technician can reach you before arrival.</p>
          <div class="form-row">
            <label for="bookName">Full name</label>
            <input type="text" id="bookName" placeholder="e.g. Priya Menon">
          </div>
          <div class="form-row">
            <label for="bookMobile">Mobile number</label>
            <div class="phone-input-row">
              <span class="phone-prefix">+91</span>
              <input type="tel" id="bookMobile" placeholder="10-digit mobile number" maxlength="10" inputmode="numeric">
            </div>
          </div>
          <div class="form-row">
            <label for="bookEmail">Email (optional)</label>
            <input type="email" id="bookEmail" placeholder="you@example.com">
          </div>
          <p class="booking-widget__hint" data-hint="6">Please enter your name and a valid 10-digit mobile number.</p>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 7: Verify OTP — hidden for now (feature not live yet), see
             booking-flow.js showStep()/next/prev for the skip-over logic.
             Left in place so it's a small change to bring back later. -->
        <div class="booking-step" data-step="7">
          <span class="booking-widget__label">Verify your mobile number</span>
          <p class="booking-widget__sublabel">Enter the 4-digit code sent to +91 <strong id="otpPhoneOut"></strong> — <button type="button" class="link-btn" data-action="prev">not this number?</button></p>

          <div class="otp-row">
            <input class="otp-box" id="otp0" data-otp-index="0" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
            <input class="otp-box" id="otp1" data-otp-index="1" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input class="otp-box" id="otp2" data-otp-index="2" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input class="otp-box" id="otp3" data-otp-index="3" maxlength="1" inputmode="numeric" pattern="[0-9]*">
          </div>
          <p class="otp-msg otp-msg--error" id="otpError" hidden><i class="fa-solid fa-circle-exclamation"></i> Incorrect code. Please check and try again.</p>
          <p class="otp-msg otp-msg--success" id="otpSuccess" hidden><i class="fa-solid fa-circle-check"></i> Number verified successfully.</p>
          <button type="button" class="btn btn--primary" data-action="verify-otp" style="width:100%;justify-content:center;margin-top:8px;"><i class="fa-solid fa-shield-check"></i> Verify OTP</button>
          <div class="otp-meta" id="otpMeta">
            <button type="button" class="link-btn" id="resendOtpBtn" data-action="resend-otp">Resend OTP</button>
          </div>
          <p class="otp-demo-hint">Prototype tip: enter <strong>1234</strong> to verify. Any other 4 digits shows the failed-code state.</p>

          <p class="booking-widget__hint" data-hint="7">Please verify your mobile number to continue.</p>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="next">Continue <i class="fa-solid fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Step 8: Review -->
        <div class="booking-step" data-step="8">
          <span class="booking-widget__label">Review your booking</span>
          <div class="review-summary" id="reviewSummary"></div>
          <div class="price-card">
            <div class="price-card__top"><span>Diagnostic visit charge</span><strong>₹199 onwards</strong></div>
            <p class="price-card__note">Repair cost is quoted on-site after inspection — you approve it before any work begins.</p>
          </div>
          <p class="booking-widget__hint" data-hint="8">Something went wrong — please try again, or call us to book directly.</p>
          <div class="booking-widget__actions">
            <button type="button" class="btn btn--glass" data-action="prev"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn--primary" data-action="confirm" id="confirmBookingBtn"><i class="fa-solid fa-check"></i> Confirm Booking</button>
          </div>
        </div>

        <!-- Step 9: Thank you -->
        <div class="booking-step" data-step="9">
          <div class="booking-thankyou">
            <span class="booking-thankyou__icon"><i class="fa-solid fa-circle-check"></i></span>
            <h3>Booking Confirmed!</h3>
            <p>Thank you — a Home Sure technician will call to confirm your visit shortly.</p>
            <div class="booking-thankyou__id">Booking ID: <strong id="bookingIdOut"></strong></div>
            <div class="booking-thankyou__window">Expected arrival window: <strong id="bookingWindowOut"></strong></div>
            <div class="booking-thankyou__next">
              <h4>What happens next</h4>
              <p><i class="fa-solid fa-circle-check"></i> We'll send technician details on WhatsApp &amp; SMS</p>
              <p><i class="fa-solid fa-circle-check"></i> Diagnosis from ₹199 — you approve the quote before any repair</p>
              <p><i class="fa-solid fa-circle-check"></i> Every repair is backed by a service warranty</p>
            </div>
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

<script>window.HOMESURE_MAPS_KEY = <?php echo json_encode(GOOGLE_MAPS_API_KEY); ?>;</script>
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

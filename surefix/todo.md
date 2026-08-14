# Sure Fix — Build Roadmap

Source of truth: `docs/surefix.md` (FRD v1.1, SureFix only — no second "Home Sure"
site, no Phase 2 items like OTP/technician management/invoicing in this doc).
Stack: plain PHP + MySQL, no framework. Local DB `surefix` already created in
XAMPP/phpMyAdmin.

**Sequencing (per client):** finish all page **design** first → client confirms →
then wire up **functionality** (forms, booking flow, DB) → client confirms → then
build the **admin panel**. Phases below are ordered accordingly.

---

## Phase 0 — Foundation
- [x] Scaffold `surefix/` (`includes/`, `admin/`, `api/`, `assets/`, `sql/`)
- [x] Copy design assets from `demo/index3` into `assets/css/style.css`,
      `assets/js/main.js`, `assets/images/logo.png`
- [x] `includes/config.php` — site constants (phone/email/address), SITE_URL,
      PDO connection to the `surefix` DB (created, not yet used by any page)
- [x] `includes/header.php` — shared `<head>`, preloader, topbar, nav, drawer;
      title/description overridable per-page; nav links resolve from any page
- [x] `includes/footer.php` — shared footer + scripts
- [x] `index.php` — homepage content (from `index3.html`)
- [x] `.htaccess` — protect `/includes`, `/sql` from direct access

## Phase 1 — Website Designing *(current focus — all static, no DB/forms yet)*
Every page shares `header.php`/`footer.php` so the nav/footer/branding stay
consistent automatically. Each appliance page follows the FRD's Step 1 pattern
(type selector + problem list) as **static UI** for now — real submission logic
comes in Phase 2.
- [x] `washing-machine.php` — Front Load / Top Load / Semi Automatic + problems
      (Not Starting, Not Spinning, Water Leakage, Excessive Noise, Drainage
      Issue, Door Lock Problem, Display/Error Code, Other — per FRD example)
- [x] `refrigerator.php` — type + problem options are now your real content
      (round 4 feedback) — no longer a placeholder
- [x] `air-conditioner.php` — type + problem options are now your real
      content (round 4 feedback, problems grouped Repair/Service) — no
      longer a placeholder
- [x] `television.php` — problem options are your real content (round 4,
      grouped Repair/Service); *type options (LED/Smart/OLED/LCD) are still
      my best-fit guess — not covered in your feedback, flag for review*
- [x] `microwave-oven.php` — problem options are your real content (round 4);
      *type options (Solo/Grill/Convection) are still my best-fit guess —
      not covered in your feedback, flag for review*
- [x] `geyser.php` — problem options are your real content (round 4);
      *type options (Electric/Gas/Instant/Storage) are still my best-fit
      guess — not covered in your feedback, flag for review*
- [x] `washing-machine.php` — problem options updated to your round 4 list;
      type options were already your real content (Front/Top Load, Semi
      Automatic) from the original FRD
- [x] `ro.php`, `dishwasher.php`, `dryer.php` — 3 new appliance pages added
      per round 4 feedback (problem options are your real content; type
      options are my best-fit guess — flag for review, see round 4 notes below)
- [x] `contact.php` — contact form (static for now), WhatsApp button,
      click-to-call, business hours
- [x] `blog.php` — blog listing template, placeholder posts (real CMS in Phase 2/3)
- [x] `blog-post.php` — single post template, placeholder content
- [x] `privacy-policy.php`, `terms-conditions.php`, `refund-policy.php` — replaced
      2026-08-12 with the real policy content you supplied
      (`clientFeedback/SureFix-Legal-Policies.md`) — 12/40/19 sections
      respectively, converted from Markdown to the site's legal-page layout.
      Far more thorough than the original boilerplate, but it still carries
      its own disclaimer that it needs a lawyer's review before go-live —
      see the open items below, which are your source document's own
      checklist, not something I'm adding on top.
- [x] Update `header.php` nav so "Contact" and "Blog" point to the new real
      pages instead of homepage anchors
- [x] Full verification pass: `php -l` on every file, live-server + curl check
      on all 12 pages (HTTP 200, correct titles, no PHP warnings), HTML
      tag-balance + broken-link + CSS-class-coverage checks — all clean
- [x] Full 6-step booking flow UI on every appliance page (`#book` widget):
      Step 1 Appliance/Problem (chips), Step 2 Location (address + Google
      Maps picker + "use my location" + notes), Step 3 Slot (date + time
      chips, earliest slot = now + 1hr per FRD), Step 4 Customer Details,
      Step 5 Review, Step 6 Thank You. Client-side only for now (JS state,
      `assets/js/booking-flow.js`) — "Confirm Booking" shows a placeholder
      booking ID, nothing is saved to the DB yet; that's Phase 2.
- [x] Fixed dangling `index.php#contact` links (leftover from before Contact
      became its own page) — global "Book a Repair" CTAs (navbar, drawer,
      hero, CTA banner) now point to `index.php#services` so visitors pick an
      appliance first, landing on that page's booking flow.
- [x] Quick Enquiry mini-form (FRD Form 1 — Name + Mobile) — built into the
      homepage hero (`index.php`, `.hero__quick-form`), replacing the
      decorative icon card. Client-side validation + success state
      (`assets/js/main.js`); not yet wired to the DB — Phase 2.
- [ ] **Checkpoint: client reviews full page set before Phase 2 starts**

### Client feedback round 1 (`clientFeedback/01`, reported 2026-08-07) — addressed 2026-08-08
- [x] Removed duplicate "Rated 4.9…" hero badge (kept the avatar trust bar)
- [x] Hero + review avatars now show a profile icon instead of a bare initial
- [x] Removed the redundant phone number from the top info bar (nav already
      has a call button); topbar itself still shows on every page incl.
      service pages
- [x] Added "Blog" to footer → Useful Links
- [x] Added a DPDP consent line + Privacy Policy link to the homepage
      Quick Enquiry form, and gave the form card its own visual definition
      (accent top border, stronger shadow/contrast)
- [x] Service cards: all CTAs now read "Book Now" as a prominent pill button;
      descriptions normalized to a consistent length/structure
- [x] Process section reduced to 3 steps with a visible connecting
      line/arrow at every breakpoint (was dashed-only on desktop before)
- [x] Reviews section is now a swipeable scroll-snap carousel with dot
      indicators on mobile
- [x] Rebuilt the booking flow on all 6 appliance pages to match the
      client's reference design (`clientFeedback/02/*.html`): Type → Brand
      → Problem → Service (with price + "Recommended" badge) → Location
      (+ area quick-chips) → Schedule (+ Emergency ASAP) → Contact + mock
      OTP verification → Review — using icon/selectable cards throughout,
      restyled to our glass/orange brand rather than the reference's flat
      white theme. Still client-side only (no DB, no real SMS) — Phase 2.
- [x] Fixed the mobile "jumps/drags upward" bug after tapping Next — the
      keyboard-close reflow and our own smooth-scroll were fighting each
      other; now the active field blurs first and the scroll target
      accounts for the sticky navbar height
- [ ] **Open — needs your input:** official brand logos (Brands section)
      and a photo per appliance page — flagged below, not done yet
- [x] Follow-up bugs from testing the rebuilt flow (2026-08-08): split
      "Contact Details" and "Verify OTP" into two separate wizard steps
      (was one combined step) — this also fixes the "can't edit a wrong
      phone number" issue, since the mobile field is never disabled now;
      going Back from the OTP step just returns to the editable field.
      "Verify OTP" is now a full-width primary button instead of a small
      text link. Hardened the Google Map step against silent failures
      (`gm_authFailure` handler + forced resize/re-center after the step
      becomes visible) — flagged to you separately if the map is still not
      showing after this, since that specific failure mode is usually a
      Google Cloud Console setting (billing/API enablement), not app code.
- [x] Client feedback round 3 (2026-08-09):
  - Reviews carousel now autoscrolls on mobile (pauses on touch/interaction,
    resumes after 6s), respects reduced-motion preference.
  - Hero "Get a Call Back" box made taller (460px → 540px, 480px on mobile).
  - Fixed `.page-hero__cta` flicker on scroll — the scroll-reveal observer
    was toggling elements visible/hidden every time they crossed the
    viewport edge; it now reveals once and stops watching, which was the
    actual bug (not appliance-page-specific, this affected every `.reveal`
    element near a scroll boundary).
  - Removed Why Us / Process / Reviews from the header nav + mobile drawer
    (sections still exist on the homepage, just not linked from nav).
  - Removed the area quick-pick chips from booking (superseded by the
    Location step rebuild below).
  - Rebuilt the booking flow's **Location** and **Schedule** steps to match
    `clientFeedback/02/surefix-booking-flow - template 2.html`: Location is
    now a "Detect my location" / manual-address flow (no visible map — uses
    Google's Geocoder + Places Autocomplete headlessly, which also
    sidesteps the map-rendering bug below) with landmark, Home/Work/Other
    tag, and notes fields. Schedule is now a quick-date row (Today/
    Tomorrow/+2/+3 + a custom calendar picker) with time slots grouped into
    Morning/Afternoon/Evening — still generated from our real 8am–10pm,
    "now + 1hr" business rule, not fabricated data.
  - Removed the Service/price-selection step entirely (was step 4) — no
    service list or per-service pricing is shown anymore.
  - Sitewide: replaced every "free diagnosis" claim with a flat **₹199
    minimum diagnostic charge** — homepage Process step, homepage FAQ,
    `refund-policy.php`, and the booking flow's Review step + thank-you
    screen. Booking flow's Review step now shows a static "₹199 onwards"
    line instead of a per-service price (since there's no service step to
    price anymore).
  - Emergency-card checkmark forced to explicit white via a defensive CSS
    rule targeting the icon directly.
  - Map bug: hardened with a `gm_authFailure` handler (Google's official
    hook for key/API/billing problems) and a forced resize+re-center after
    the step becomes visible — but I can't visually test Maps rendering
    myself (no browser in this environment). If it's still broken after
    this, it's very likely a Google Cloud Console setting (Maps JavaScript
    API not enabled, or no billing account attached), not app code — the
    Location step redesign above no longer depends on a visible map at all,
    only on Geocoding, which is a lighter-weight, more reliable call.
- [x] Fixed "Enter address manually" doing nothing (2026-08-09): root cause
      was a CSS specificity bug, not a missing click handler — `.locate-card`
      and `.detected-card` (and `.otp-msg`) set `display: flex` in their own
      class rules, and an author stylesheet's `display` always beats the
      browser's built-in `[hidden] { display: none }` rule, regardless of
      selector specificity. So setting `.hidden = true` in JS silently did
      nothing on those elements. Added a global `[hidden] { display: none
      !important; }` rule so the native `hidden` attribute actually works
      everywhere on the site, not just a one-off fix for this button.
- [x] Fixed homepage auto-scrolling to Reviews on its own (2026-08-09): the
      reviews autoscroll timer was moving cards with `scrollIntoView()`,
      which scrolls *every* ancestor container needed to bring the target
      into view — including the page itself if the card was off-screen
      below the fold, which is exactly what was happening 4s after every
      page load. Switched to scrolling only the carousel's own horizontal
      `scrollLeft`, and added an IntersectionObserver so the autoscroll
      timer only runs while the Reviews section is actually on screen.
- Deliberate scope trims from the reference design (flagging so you can
  ask for either if you want them): kept Problem as multi-select (a machine
  can have more than one symptom) instead of the reference's single-select;
  skipped the reference's persistent desktop sidebar summary + sticky
  mobile mini-summary footer bar (the feedback specifically asked for
  "selectable visual buttons/icons," which is done — the sidebar is a
  bigger layout addition beyond that ask, happy to add if wanted)
- [x] Client feedback round 4 — real type/problem lists + 3 new appliances
      (2026-08-10):
  - Replaced the type/problem lists on all 6 existing appliance pages with
    the client's real content: AC (Split/Tower/Window/Central AC/Not Sure),
    Washing Machine, Refrigerator (Single/Double/Side By Side/Deep Freezer/
    Multi Door/Not Sure), TV, Microwave, Geyser all updated. This replaces
    my earlier best-fit guesses — no longer flagged as placeholder content
    for these 6.
  - AC and TV problems are now grouped into "Repair" and "Service" sections
    within a single Problem step (per your direction) — one shared radio
    group spanning both sections so only one option total can be picked,
    visually separated by section headers. Every other appliance keeps the
    existing multi-select checkbox behavior (a machine can have more than
    one symptom).
  - Added **3 new appliance pages** — `ro.php` (RO Water Purifier),
    `dishwasher.php`, `dryer.php` — full structure matching the other 6
    (hero, 9-step booking flow, common issues, FAQs), linked from the
    homepage services grid and footer. **Type options for these 3 weren't
    specified in your feedback, so I used best-fit defaults — same flag as
    the original 6 got, please review:**
    - RO: RO / RO+UV / RO+UV+UF / Alkaline / Not Sure
    - Dishwasher: Freestanding / Built-in / Not Sure
    - Dryer: Vented / Heat Pump / Condenser / Not Sure
  - Dryer's problem list was given identical to Washing Machine's (plus "No
    Heating") — implemented exactly as given, but flagging that draining/
    filling symptoms are unusual for a clothes dryer in case it was
    copy-pasted by mistake.
  - Minor spelling corrections made while transcribing your list: "Gas
    Refil" → "Gas Refill", "side byuu Side" → "Side By Side".
  - Expanded the icon-matching keyword list substantially to cover the new
    terms (Installation/Uninstallation, Cleaning, Gas Refill, Power Issue,
    Draining, Filter, Water Quality, etc.) so every new option still gets a
    relevant icon automatically.
- [x] Client feedback round 5 — real photos throughout (2026-08-11):
  - You added 4 image folders to `docs/` (review avatars, generic appliance
    photos, per-type photos, per-problem icons, service-page banners).
    Copied and optimized all of them into `assets/images/{appliances,types,
    problems,reviews,banners}/` with clean web-safe filenames — originals
    in `docs/` untouched. Some of the review photos were 10–13MB
    camera-resolution JPEGs; resized/compressed down to ~2.1MB total across
    every image on the site.
  - Reviews: added a 4th testimonial (Sunil Reddy, HSR Layout — new quote
    about booking a washing machine repair) alongside the existing 3, all
    now using your real photos instead of icon placeholders. Since the
    photos were captioned Diya/Rohit/Sunil/Varun but the existing 3 reviews
    were named Priya/Arvind/Sunita, I renamed the people to match the
    photos (Diya Kapoor, Rohit Nair, Varun Iyer keep the original 3 quotes;
    Sunil Reddy is the new one) rather than mismatch a name to the wrong
    face — flag if you'd rather I kept the original names.
  - Reviews now auto-scroll at every screen size (previously mobile-only) —
    shows 1 card on phones, up to 3 on desktop, always sliding rather than
    a static grid.
  - Homepage service cards (all 9) and the booking flow's Type step now
    show real photos: **Type step photos only exist for AC (Split/Tower/
    Window/Central), Washing Machine (Front/Top/Semi-auto), and
    Refrigerator (Single/Double/Side-by-side/Deep Freezer/Multi-door)** —
    you didn't supply per-type photos for TV/Microwave/Geyser/RO/
    Dishwasher/Dryer, so those 6 keep icon-cards on the Type step (no
    distinct image existed to show per option). Let me know if you want to
    supply those too.
  - Booking flow's Problem step now shows your real icons wherever a
    matching image exists (matched by keyword against the problem text,
    same approach as the icon system); falls back to a Font Awesome icon
    for the handful of items with no supplied image (Other, Not Sure, etc).
  - Added a full-width banner photo to the top of the 6 original service
    pages (AC, Washing Machine, Refrigerator, TV, Microwave, Geyser) — you
    only supplied banners for these 6, not RO/Dishwasher/Dryer, so those 3
    don't have one yet.
- [x] Client feedback round 6 (2026-08-11):
  - Fixed the desktop reviews carousel — the cards' width was set as a
    percentage that happened to fit the container almost exactly at common
    desktop sizes, so there was no real overflow to scroll/auto-advance
    through. Switched to a fixed pixel width per card so it reliably
    overflows (and therefore scrolls/auto-advances) at every screen size.
  - Escalated the Quick Enquiry form's visual separation again — you'd
    flagged this once before and the first pass (accent border + shadow)
    apparently wasn't enough. It's now a solid two-tone card (navy header
    band + white form body, no more translucent glass blending into the
    hero background) so it reads unambiguously as its own UI block rather
    than hero decoration.
  - Rebuilt the 6 banner-equipped service pages' hero: the photo is now
    true full-bleed (edge-to-edge of the browser, not boxed in a rounded
    card below the text) with the icon/heading/tagline/CTA buttons
    overlaid directly on top of it behind a dark gradient scrim for
    legibility — matches what you described instead of "text above, boxed
    photo below." RO/Dishwasher/Dryer (no banner supplied) keep the plain
    icon-only hero unaffected.
- [x] Client feedback round 7 (2026-08-11):
  - Fixed banner cropping — was using a CSS `background-image` with
    `cover` sizing and a fixed height, which cropped the photo to fill that
    box. Switched to a real `<img>` at full width/auto height, so the
    section is now exactly as tall as the complete image — nothing gets
    cut off. On very narrow phones this makes the banner fairly short
    (16:9 photo), so the tagline paragraph auto-hides below 480px to keep
    the icon/heading/CTA from feeling cramped — icon and heading stay put.
  - Darkened the banner overlay from a ~50–88% gradient to a flatter, more
    consistent ~76% navy tint so the photo reads as muted background
    texture rather than a bright, attention-grabbing image.
  - Enlarged the Quick Enquiry form (300px → 360px max-width, bigger
    padding, larger input/button text and touch targets).
- [x] Client feedback round 8 (2026-08-12):
  - Reverted the banner overlay from round 7 — split back into two
    sections (hero text, then a clean standalone banner photo below it
    with no text/CTA on top and no dark scrim), since the overlaid version
    was covering the photo. Still full-bleed edge-to-edge and full image
    height (no crop).
  - Contact details updated everywhere they're used (topbar, nav, footer,
    contact page, WhatsApp links): phone/WhatsApp now +91 855-0000-423,
    email now care@sure-fix.in.
  - Social links updated to your real accounts — Facebook, Instagram, X
    (added `SITE_FACEBOOK_URL`/`SITE_INSTAGRAM_URL`/`SITE_X_URL` to
    `config.php`). Dropped the WhatsApp icon from the topbar row (redundant
    with the phone number + floating WhatsApp button) and dropped LinkedIn
    from the footer row (wasn't in your list) so both rows now consistently
    show the same 3 platforms.
  - Hid the Verify OTP step from the booking flow — Contact Details now
    goes straight to Review. The step's HTML/CSS/JS are still all there
    (`OTP_STEP_HIDDEN = true` in `booking-flow.js`), just skipped over in
    navigation, so turning it back on later is a one-line flip once the
    real SMS integration is ready, not a rebuild.
  - Fixed Review step text alignment — values were right-aligned, which
    left a ragged left edge whenever a longer value (address, schedule)
    wrapped to a second line. Now label-left/value-left, conventional and
    easier to read on wrap.

## Phase 2 — Website Development ✅ *(built 2026-08-12, per your explicit go-ahead — see notes)*
- [x] `sql/schema.sql` — `admin_users`, `leads`, `blog_posts`, `blog_categories`;
      imported into the existing `surefix` DB. (No separate `bookings` table —
      Quick Enquiry, Contact, and full Bookings all land in one `leads` table,
      distinguished by a `type` column, since the admin's lead list needs to
      show all three together anyway.)
- [x] Form 1 — Quick Enquiry (Name + Mobile), wired to `leads` table via
      `api/submit-lead.php`
- [x] Contact page form — was completely inert (`action="#"`,
      `onsubmit="return false;"`) — now wired to the same endpoint
- [x] Form 2 — Service Booking, full flow now functional end-to-end:
      Type → Brand → Problem → Location → Schedule → Contact → Review →
      Confirm now POSTs to `api/submit-booking.php`, which validates,
      generates a **real, unique, server-side Booking ID** (`SF` + date +
      random suffix, e.g. `SF260812XXXX`), and inserts the full booking into
      `leads`. The Verify OTP step stays hidden (per your earlier
      instruction — `OTP_STEP_HIDDEN` flag in `booking-flow.js`), so the
      flow completes without it.
- [x] `api/submit-lead.php`, `api/submit-booking.php` — server-side
      validation (name/mobile/email/address format checks), PDO prepared
      statements throughout, JSON responses consumed via `fetch()`
- [x] Spam protection — honeypot field (`name="website"`, hidden off-screen)
      on both the Quick Enquiry and Contact forms and the booking flow;
      bots that fill it get a fake success with no row written. *Rate
      limiting not added yet — flagged as a further hardening step below.*
- [x] `blog.php`/`blog-post.php` now query `blog_posts`/`blog_categories`
      directly — the old `includes/blog-data.php` placeholder array is
      deleted (fully superseded, not just unused)
- [ ] Notifications — admin email on new lead/booking. **Not built this
      round** — needs a mail-sending setup (SMTP credentials or a
      transactional-email provider) that wasn't specified; flagged below.
- [ ] ~~Checkpoint: client confirms before Phase 3 starts~~ — you asked for
      Phase 2 and Phase 3 together in the same message, so both were built
      in this round rather than gating on a separate confirmation step.

## Phase 3 — Admin Panel ✅ *(built 2026-08-12)*
- [x] Auth — login/logout, hashed passwords (`password_hash`/
      `password_verify`), session-based, auth guard on every admin page
- [x] Lead management — list with filters (date/service/status/search),
      status dropdown (New/Assigned/In Progress/Completed/Cancelled) that
      saves on change, click-to-call, one-click WhatsApp, every field on
      the detail view has its own copy-to-clipboard button
- [x] Blog management — create/edit/delete, image upload (5MB limit,
      jpg/png/gif/webp), category CRUD (add/rename/delete)
- [x] Profile page (**not in the original Phase 3 checklist, but you asked
      for it this round**) — edit name/email, change password (current
      password verified, 8-char minimum)
- [x] Mobile-responsive — off-canvas sidebar with a hamburger toggle below
      900px, tables collapse into stacked cards below 900px. *I could not
      visually verify this in an actual mobile browser (no browser
      automation in this environment, per your standing instruction) — the
      CSS follows a standard, well-tested responsive-table pattern, but
      please check it on a real phone and flag anything that looks off.*
- Two admin pages from earlier scaffolding — `services.php`/
  `services-edit.php` (service catalog) and `testimonials.php`/
  `testimonials-edit.php` — are **not wired up or linked in the nav**,
  since you said lead management + profile + blogs was the current scope.
  Left the files in place rather than deleting them in case you want that
  scope later; they'd need their own DB tables first if so.
- [x] Client feedback round 9 — admin panel observations (2026-08-14):
  - **Fixed the emergency-booking bug** — Emergency ASAP could still be
    submitted even when today has no bookable slots left (your example:
    11:30 PM). Fixed on both ends, not just the UI: the client-side
    Emergency card now visually disables itself once today's last
    half-hour slot (8 AM–10 PM grid) can't fit the required "1 hour from
    now" buffer, *and* `api/submit-booking.php` independently re-checks
    the same rule server-side and rejects the booking if bypassed — so
    even a direct API call (not just the on-screen button) can't create an
    emergency booking outside hours.
  - Call/WhatsApp/Delete on the lead detail page are now full-size buttons
    (`.btn--lg`) instead of small ones; the View/Call/WhatsApp/Delete icon
    buttons in the leads list are now uniform 36×36px targets, easier to
    tap on mobile.
  - Added a **Copy All Details** button on the lead detail page — copies
    every populated field as one plain-text block (label: value per line)
    in a single click, instead of copying field-by-field.
  - Renamed "Date Created" → **Booked On** (both list and detail view, for
    consistency) and "Area" → **Type** (the Home/Work/Other address tag).
  - Location Pin no longer shows raw latitude/longitude text — it's now a
    single "View on Map" link straight to Google Maps.
  - Detail-view label column is now a fixed width (was min-width, which let
    each row's label size to its own text) so every row's value now starts
    at the same x-position — the alignment issue you flagged.
  - **Technician assignment** — added a `technician_name` column to
    `leads` (migration note in `sql/schema.sql` for the column since your
    dev DB already existed when this was added). Lead detail page has an
    inline "assign a technician" field; the leads list shows a technician
    tag/badge per row ("Unassigned" in gray if nobody's assigned yet) and
    a new Technician filter dropdown (including an "Unassigned" option) so
    you can filter by name.
  - Quick Enquiry leads now show only Customer Name, Mobile Number,
    Message (if any) and Booked On — the full field list was mostly empty
    "—" placeholders for this lead type, per your note to keep it to
    Name & Contact.
  - Replaced the dashboard's flat white cards/stat-cards/login box with a
    subtly tinted background + border on each, so panels read as visually
    separated modules against the page background rather than plain white
    blocks with only a shadow.

## Phase 4 — SEO
- [ ] Editable meta title/description per page (DB-driven for blog, config for
      static pages)
- [ ] Image ALT text audit across all pages
- [ ] SEO-friendly URLs (`.htaccess` rewrites, e.g. `/blog/post-slug`)
- [ ] `sitemap.xml`, `robots.txt`
- [ ] Canonical tags, breadcrumbs
- [ ] Local Business + Service schema (JSON-LD)
- [ ] Open Graph tags

## Phase 5 — Performance
- [ ] Compress `logo.png` (currently 636KB, uncompressed — first target)
- [ ] Lazy-load below-the-fold images
- [ ] Minify CSS/JS for production
- [ ] Browser cache headers (`.htaccess`)
- [ ] DB indexes on frequently-queried columns (status, date, mobile)

## Phase 6 — Security
- [ ] PDO prepared statements everywhere (no raw string queries)
- [ ] Input validation/sanitization on all form fields
- [ ] CSRF protection on admin forms
- [ ] `.htaccess` hardening — block direct access to `/includes`, `/sql`
- [ ] Directory listing disabled

## Phase 7 — Google Integrations
- [ ] Google Analytics 4
- [ ] Google Search Console verification
- [ ] Google Tag Manager
- [ ] *(Meta Pixel — optional/future, per FRD)*

## Phase 8 — Testing & QA
- [ ] Cross-browser check (Chrome, Edge, Firefox, Safari)
- [ ] Responsive check (mobile, tablet, desktop)
- [ ] Form + booking flow edge-case testing
- [ ] Admin CRUD testing

## Phase 9 — Deployment & Handover
- [ ] Hosting + SSL setup
- [ ] Production DB migration + backup process
- [ ] Technical documentation + admin user manual
- [ ] Handover: source code, DB backup, credentials, git repo access

---

### Open items from the admin panel / DB build (2026-08-12)
- **Admin login credentials** — username `admin`, password was randomly
  generated and given to you separately in chat (not written here since
  this file may end up somewhere more widely readable than the chat).
  **Please log in and change the password via Profile immediately.**
- **Production DB credentials** — `includes/config.php` still has local
  XAMPP dev credentials (`DB_HOST=localhost, DB_USER=root, DB_PASS=''`).
  These must be swapped for your real hosting MySQL credentials before
  go-live, same flag as `SITE_URL` had earlier — except this one isn't
  self-detecting, you'll need to update it manually when you deploy.
- **Email notifications on new leads/bookings** — not built this round,
  needs SMTP credentials or a transactional-email provider (e.g. an SMTP
  relay, SendGrid, etc.) that wasn't specified. The admin panel shows new
  leads immediately (and a red count badge in the sidebar), so nothing is
  silently missed in the meantime — but there's no push/email alert yet.
- **Rate limiting** on the public API endpoints (`api/submit-lead.php`,
  `api/submit-booking.php`) — only honeypot spam protection exists today.
  Fine for now, worth adding before the site gets real traffic.
- **Services/Testimonials admin** — scaffolding exists (`admin/services.php`
  etc.) from before this session but isn't wired up or in the nav, since you
  scoped this round to leads/blogs/profile only. Say the word if you want
  that turned on later — it needs its own DB tables first.

### Open items needing your input (not blocking Phase 1, but flagged early)
- Brand logos for the "Brands We Repair" strip (LG, Samsung, Whirlpool, IFB,
  Bosch, Godrej, Voltas, Haier, Panasonic, Blue Star, Daikin, Hitachi) —
  client feedback round 1 asked for official logos instead of text chips.
  I didn't source these myself: I can't verify I'd be grabbing each
  brand's current official mark from a reliable source, and using another
  company's trademark on a commercial site is worth your sign-off rather
  than my guessing. Please send logo files (PNG/SVG, transparent
  background) for the brands you want listed, or confirm you're fine with
  me sourcing them and I will.
- ~~A hero/header photo for each of the 6 appliance pages~~ — received and
  added (round 5, `assets/images/banners/`). Still open: RO, Dishwasher and
  Dryer don't have a banner photo yet — send one for each if you want them
  to match the other 6.
- Per-type photos for the booking flow Type step only cover AC, Washing
  Machine and Refrigerator (round 5) — TV, Microwave, Geyser, RO,
  Dishwasher and Dryer still show icon-cards there since no distinct photo
  per type was supplied for them. Send photos per type if you want full
  parity across all 9 appliances.
- Type options still needing your review (round 4 feedback covered
  problems for every appliance, and types for AC/Washing Machine/
  Refrigerator only): TV, Microwave, Geyser type options are still my
  original best-fit guesses, and RO/Dishwasher/Dryer (new pages) have type
  options I invented since they weren't in your feedback at all.
- ~~Google Maps API key~~ — received, added to `includes/config.php`. **Before
  going live**, restrict it to your production domain's HTTP referrers in
  Google Cloud Console (client-side keys are visible in page source, so an
  unrestricted key can be used by anyone).
- Legal pages now use your supplied real policy content (2026-08-12), but its
  own final checklist flags these still need doing before go-live — carrying
  them over here so they don't get lost:
  - Insert actual Effective Date / Last Updated date (pages currently show
    today's date dynamically as a placeholder).
  - Confirm care@sure-fix.in is actively monitored and who owns responding
    to privacy queries.
  - Name the actual payment gateway(s) once finalised (Terms §15 currently
    deliberately avoids naming one).
  - Make sure invoices/job sheets actually state the specific warranty
    period per job (Terms §24–25 rely on this).
  - Consider listing serviceable pin codes/cities once finalised (currently
    silent on service area).
  - If you activate Google Analytics/Ads, Meta Pixel etc., add a cookie
    consent banner and update Privacy §4 to say exactly which tools are live.
  - Once GST-registered, update Terms §16 and start issuing GST invoices.
  - A physical/registered business address is currently omitted from the
    policies by choice — revisit this, since it's commonly expected on
    Indian consumer-facing sites and may become a compliance requirement.
  - **Have a qualified Indian lawyer review before publishing** — specifically
    flagged in your source doc: Limitation of Liability (Terms §34), Dispute
    Resolution (Terms §37), the "rights and DPDP framework" section (Privacy
    §10 — confirm the phased-commencement description is still accurate as
    of your actual publish date), the cancellation/visiting-charge clauses
    (Terms §14/17, Refund §4–5), and the Indemnity clause (Terms §36).
    *(2026-08-12: removed the visible "draft, please have this reviewed"
    disclaimer from the live pages per your instruction, so they read as
    finished/published — this checklist item is unchanged and still
    genuinely open, just no longer shown to site visitors.)*
- Production domain name (for `SITE_URL` and SSL setup later).

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
- [x] `refrigerator.php` — type options (Single Door / Double Door / Side-by-Side)
      + equivalent problem list *(not spelled out in FRD; my best-fit content —
      flag for your review)*
- [x] `air-conditioner.php` — type options (Split / Window / Cassette) + problems
      *(same flag — not in FRD, my best-fit content)*
- [x] `television.php` — type options (LED / Smart / OLED) + problems *(same flag)*
- [x] `microwave-oven.php` — type options (Solo / Grill / Convection) + problems
      *(same flag)*
- [x] `geyser.php` — type options (Electric / Gas / Instant) + problems *(same flag)*
- [x] `contact.php` — contact form (static for now), WhatsApp button,
      click-to-call, business hours
- [x] `blog.php` — blog listing template, placeholder posts (real CMS in Phase 2/3)
- [x] `blog-post.php` — single post template, placeholder content
- [x] `privacy-policy.php`, `terms-conditions.php`, `refund-policy.php` — standard
      boilerplate legal content *(flag: should get a legal review before go-live,
      not something I can certify)*
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

## Phase 2 — Website Development *(after design is confirmed)*
- [ ] `sql/schema.sql` — `leads`, `bookings`, `blog_posts`, `blog_categories`,
      `admin_users` tables; import into the existing `surefix` DB
- [ ] Form 1 — Quick Enquiry (Name + Mobile), wired to `leads` table
- [ ] Form 2 — Service Booking, full 6-step flow made functional:
  - [x] Step 1 Appliance + problem (built as UI in Phase 1)
  - [x] Step 2 Location — address + Google Maps picker (key added to
        `config.php`) + "use my location" + notes fallback — *UI built,
        client-side reverse geocoding only, no server storage yet*
  - [x] Step 3 Slot — date + time, earliest slot = current time + 1 hour —
        *UI built, generated client-side in JS*
  - [x] Step 4 Customer details — *UI built*
  - [x] Step 5 Review & confirm — *UI built*
  - [x] Step 6 Thank-you page — Booking ID, confirmation, arrival window,
        call/WhatsApp buttons — *UI built; Booking ID is a placeholder
        generated in JS, not a real persisted ID until this is wired below*
- [ ] `api/submit-lead.php`, `api/submit-booking.php` — validation, real
      server-generated booking ID, DB insert (wires the Phase 1 UI above to
      the database)
- [ ] Spam protection (honeypot + basic rate limiting) on public forms
- [ ] Notifications — admin email on new lead/booking; structured so
      SMS/WhatsApp can be added later without rework
- [ ] Wire `blog.php`/`blog-post.php` to the `blog_posts` table
- [ ] **Checkpoint: client confirms forms/booking flow work end-to-end before
      Phase 3 starts**

## Phase 3 — Admin Panel *(after development is confirmed)*
- [ ] Auth — login/logout, hashed passwords, session-based
- [ ] Dashboard — basic overview (today's leads, total leads)
- [ ] Lead management — list with filters (date/service/status/search),
      status updates (New/Assigned/In Progress/Completed/Cancelled),
      click-to-call, one-click WhatsApp, copyable fields
- [ ] Blog management — create/edit/delete, image upload, categories
- [ ] Confirm admin dashboard is fully mobile-responsive (FRD requirement)

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

### Open items needing your input (not blocking Phase 1, but flagged early)
- Appliance type/problem lists for Refrigerator, AC, TV, Microwave, Geyser —
  FRD only spells this out for Washing Machine; I've filled in reasonable
  defaults to keep moving, but they're guesses and worth a quick review.
- ~~Google Maps API key~~ — received, added to `includes/config.php`. **Before
  going live**, restrict it to your production domain's HTTP referrers in
  Google Cloud Console (client-side keys are visible in page source, so an
  unrestricted key can be used by anyone).
- Legal copy for Privacy Policy / Terms & Conditions / Refund Policy should get
  an actual legal review before launch — I can draft standard boilerplate, but
  I'm not a substitute for that.
- Production domain name (for `SITE_URL` and SSL setup later).

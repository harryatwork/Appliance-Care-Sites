# Home Sure — Build Roadmap

Home Sure is a second brand for the same client, built as a rebranded fork of
the **Sure Fix** project (`../surefix/`). Same stack (plain PHP + MySQL, no
framework), same pages, same booking flow, same admin panel — different
branding. For the full feature build history (why things are built the way
they are, all client feedback rounds, every design decision), see
`../surefix/todo.md` — that history applies here too, since this is the same
codebase. This file only tracks what's specific to Home Sure.

---

## Rebrand from Sure Fix (2026-08-18)

- [x] Separate MySQL database (`homesure`, not sharing `surefix`'s DB) —
      schema imported, seeded with rebranded blog content
- [x] Fresh admin login seeded (`admin_users` — own credentials, not shared
      with Sure Fix's admin panel)
- [x] `includes/config.php` — `SITE_NAME`, `DB_NAME`, `SITE_EMAIL` updated
- [x] Brand color palette swapped in `assets/css/style.css` (`--ink`,
      `--accent`, `--accent-2`, `--bg-warm`), sampled from the new logo:
      navy `#152841` + red `#EC0F1D` (was Sure Fix's orange `#FF9800` + navy
      `#0B1B45`)
- [x] `admin/admin.css` colors left unchanged (slate/sky-blue functional
      palette) — matches the existing Sure Fix admin panel, which also
      doesn't follow its own public-site brand colors; flagged to the client
      in case they want the admin panel itself recolored too
- [x] All "Sure Fix" display text (page titles, logo alt text, nav, footer,
      legal page prose, admin chrome) replaced with "Home Sure"
- [x] Code-level identifiers renamed: `window.SUREFIX_SITE_URL` →
      `HOMESURE_SITE_URL`, `window.SUREFIX_MAPS_KEY` → `HOMESURE_MAPS_KEY`
- [x] `sure-fix.in` domain references in legal pages (privacy policy, terms,
      refund policy) replaced with `home-sure.in`

### Open items before Home Sure can go live
- [ ] **Contact email** — `care@home-sure.in` is a placeholder mirroring Sure
      Fix's pattern; confirm the domain/inbox actually exists before launch
- [ ] **Social media accounts** — Instagram/Facebook/X links are placeholders
      (`instagram.com/myhomesure` etc.) pointing at accounts that likely
      don't exist yet; create the real accounts and update
      `includes/config.php`
- [ ] **Google Maps API key** — currently reuses the same key as Sure Fix
      (same Google Cloud project), which is referrer-restricted to
      `sure-fix.in` only. Once Home Sure has a real domain, either add it to
      that key's HTTP referrer allowlist or issue a separate key — otherwise
      the location picker/autocomplete will fail on Home Sure the same way
      it briefly did on Sure Fix
- [ ] **Phone number & address** — currently identical to Sure Fix's (`+91
      855-0000-423`, same Indiranagar address), per client's confirmation
      this round. Revisit if Home Sure ends up being a genuinely separate
      operation rather than a shared front desk/service area
- [ ] Production DB credentials in `config.php` are still local XAMPP
      defaults (`root` / no password) — same flag as Sure Fix, update before
      deploying
- [ ] Admin login password — a random one was generated on setup; change it
      via the Profile page once logged in

Everything else (pages, booking flow, lead capture, blog, admin panel
features) is identical in functionality to Sure Fix — no separate roadmap
needed unless Home Sure's content/features are meant to diverge from Sure
Fix going forward.

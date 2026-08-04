# HKLA launch checklist

## Before launch

- [ ] Confirm JL Jungka webfont license; add WOFF2 files (Latin subset) to `wp-content/themes/hkla/assets/fonts/` and the matching `@font-face` block at the top of `main.css`. The preload link activates automatically once `jl-jungka-regular.woff2` exists.
- [ ] Swap provisional palette hex values in `main.css` and `theme.json` for Kilograph's final values.
- [ ] Replace the placeholder wordmark SVG (`assets/img/hkla-wordmark.svg`) with the approved asset; keep `fill="currentColor"`.
- [ ] Confirm the office address (714 West Olympic Blvd, Suite 735, Los Angeles, CA 90015) in Site Settings.
- [ ] Crawl the old Wix sitemap.xml; enter every legacy project URL into Redirection, mapping to its new `/projects/{slug}/` equivalent. `/process-of-design`, `/journal`, `/people`, `/purpose`, and `/careers` are already handled in code.
- [ ] Enter real projects, people, and page copy; delete seed content.
- [ ] Configure WPForms with honeypot, and paste its shortcode into Site Settings, Contact form shortcode. Send a test from the live form and confirm delivery to the studio inbox and the RFP inbox.
- [ ] Configure SEOPress: titles, XML sitemap, and set the default OG image.
- [ ] Set up Fathom or Plausible analytics.
- [ ] Confirm image optimization (WebP/AVIF) is active at the host or via plugin.
- [ ] Client users created as Editors only. No admin accounts for content staff.

## Verification

- [ ] All legacy URLs 301 to the right pages (spot check every project).
- [ ] Lighthouse mobile: 90+ performance on Home and a media-heavy project page; 100 accessibility.
- [ ] Axe: zero critical issues on every template (Home, Projects index, a project, Process, About, News, Contact, 404).
- [ ] Keyboard-only pass: nav, mobile menu, sector filter, forms, skip link, visible focus everywhere.
- [ ] `prefers-reduced-motion` on: no motion anywhere, all content visible.
- [ ] Hero images under ~350KB delivered; LCP under 2.5s on mid-range mobile.
- [ ] Forms deliver and are spam-protected; error states readable in text.
- [ ] XML sitemap submitted in Search Console; canonical URLs correct.
- [ ] Backups running at the host; staging environment retained.

## After launch

- [ ] Walk the client through `docs/EDITOR_GUIDE.md` and the dashboard widget.
- [ ] Monitor 404 logs in Redirection for missed legacy URLs during the first month.

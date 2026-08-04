# HKLA WordPress Build

Custom WordPress theme for HKLA (Hongjoo Kim Landscape Architects), replacing the Wix site at hklainc.com. Built to the brief in `docs/HKLA_WordPress_Brief_Claude_Code.md`.

## What is in this repo

```
wp-content/themes/hkla/   The custom classic theme (the whole build)
bin/seed-content.php      WP-CLI seed script: pages, three sample projects, people
docs/EDITOR_GUIDE.md      One page guide for HKLA editors
docs/LAUNCH_CHECKLIST.md  Pre-launch verification list
.wp-env.json              Local dev via @wordpress/env (Docker)
composer.json             Documented plugin list for environment reproduction
```

The repo root also contains a legacy static site from a previous project; it is unrelated to the WordPress build and can be removed when convenient.

## Quick design preview (no WordPress needed)

Open `preview/index.html` in any browser. It is a self-contained page built from the theme's actual CSS with placeholder imagery embedded, switchable between the Home, Projects index, and single-project views. Use it for the phase 1 design review.

## Local development

Requires Docker and Node.

```bash
npx @wordpress/env start
```

This boots WordPress at `http://localhost:8888` (admin: `admin` / `password`) with the `hkla` theme mapped and active.

Then install plugins. Without an ACF Pro license, use Secure Custom Fields (the free WordPress.org fork that includes the former Pro features: repeaters, flexible content, gallery, options pages). It keeps the same `get_field`/`have_rows` API and reads the same `acf-json/` field groups, so the theme runs unchanged:

```bash
npx @wordpress/env run cli wp plugin install secure-custom-fields safe-svg wpforms-lite redirection --activate
```

With an ACF Pro license, install it instead (upload the zip or use a Composer auth token) and skip `secure-custom-fields` above. Either works; pick one, not both.

Seed content (pages, sample projects, people, site settings):

```bash
npx @wordpress/env run cli wp eval-file bin/seed-content.php
```

## Required plugins (production)

| Plugin | Purpose |
|---|---|
| ACF Pro | All structured fields. Field groups are versioned as JSON in `wp-content/themes/hkla/acf-json/`. |
| SEOPress (or Yoast) | Meta, XML sitemap, canonical URLs, OG tags |
| Safe SVG | Sanitized SVG uploads (wordmark, sketches) |
| WPForms (or Gravity Forms) | Contact form with honeypot and server-side validation. The theme ships a secure native fallback form used until a form plugin shortcode is configured in Site Settings. |
| Redirection | Legacy 301 map (or use server-level redirects; the theme also handles `/process-of-design` and `/about` in code) |
| Host-level or plugin image optimization | WebP/AVIF conversion on upload |
| Host caching layer | Page + object cache per host (WP Engine, Kinsta, Flywheel) |

## Theme architecture

- Committed sitemap (per the signed scope): Home, Process (POD), Projects + project template, About, News/Recognition + news template, Contact. People, Purpose, and Careers content folds into About; News is the native posts area.
- Classic PHP templates plus `theme.json` used only for editor lockdown: the five brand colors as the palette, custom colors, gradients, and font sizes disabled.
- CPTs and taxonomy registered in code: `inc/post-types.php` (`project` with `sector` taxonomy, `person`).
- ACF field groups saved as JSON in `acf-json/`, loaded automatically.
- Editors never touch layout: page templates have no block editor; every editable string and image is a structured field.
- One compiled CSS file (`assets/css/main.css`), vanilla JS under 50KB (`assets/js/main.js`), no jQuery.
- Brand typeface JL Jungka pending license confirmation; the whole stack hangs off one `--font-sans` custom property so the swap is one line in `main.css` plus a `@font-face` block.

## Review gate

End of phase 1 (front page, projects index, single project with seed content) is the design review moment. Nothing in phase 2 starts until the project template feels right against the identity deck.

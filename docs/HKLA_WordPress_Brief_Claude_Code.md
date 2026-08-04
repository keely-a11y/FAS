# HKLA Website: WordPress Build Brief
### For use with Claude Code. Self-contained: everything needed to scaffold and build is in this file.
Kilograph | August 2026
---
## 1. Context and objective
Build a custom WordPress site for HKLA (Hongjoo Kim Landscape Architects), a Los Angeles landscape architecture practice specializing in civic, educational, healthcare, and public infrastructure work. This replaces a Wix site at hklainc.com.
Non-negotiable requirements:
1. **Client-editable without risk.** HKLA staff (non-technical) must be able to add and edit projects, team bios, careers listings, and selected page copy. They must not be able to break the layout, typography, or palette. The current Wix site has placeholder text visible in production; the new build must make that class of error structurally impossible. Editing happens through structured fields, never through free-form page building.
2. **Narrative-first project template.** Case studies lead with a story, not a photo grid. This is the core of the brand strategy.
3. **Fast, accessible, image-heavy.** Portfolio site performance with WCAG 2.1 AA compliance. HKLA's clients are public agencies; accessibility is both right and commercially relevant.
---
## 2. Brand inputs
### Positioning and voice
- Positioning line: **"Shared spaces. Shared stories."**
- Mission: "HKLA is a civic landscape specialist. Grounded in storytelling and a commitment to community, we shape environments for a shared and sustainable future."
- Tone: Confident, Editorial, Authentic, Thoughtful. Short declarative headlines. No jargon, no hedging. Reference pair from the brand guidelines: instead of "Several existing elements were identified which could have been neglected," write "A struggling tree, overhead flight paths, chain-link fencing. Where others saw problems, we saw potential."
- All placeholder copy in the build should be written in this voice, drawn from the brand narrative. Final copy will be supplied separately.
- Punctuation rule for all copy in the build: no em dashes. Use periods, commas, or colons.
### Identity concept: "In Conversation"
The approved wordmark direction holds two registers in conversation: the measured and the expressive, the built and the living. In practice on the web: disciplined typographic structure (the measured register) against full-bleed landscape photography and hand sketches (the expressive register). Typography-driven, clean, legible, with quiet warmth. Confident without being cold.
### Design tokens (provisional)
Palette sampled from the approved identity deck (v3). Kilograph will supply final adjusted hex values; build with CSS custom properties so swapping is trivial.
```css
:root {
  --color-ink:      #000000;  /* primary text, immersive surfaces */
  --color-paper:    #EFEBE8;  /* warm off-white, default background */
  --color-stone:    #978E7D;  /* warm taupe, secondary surfaces and large display accents */
  --color-signal:   #C9E35B;  /* chartreuse accent, used sparingly */
  --color-white:    #FFFFFF;  /* image captions on dark, form fields */
}
```
Usage rules (these encode contrast compliance, do not deviate):
- Body text is always `--color-ink` on `--color-paper` or `--color-white`.
- `--color-signal` is never used for text on light backgrounds (fails contrast). On `--color-ink` it passes easily: use it there for links, active filter states, and small accents.
- `--color-stone` is never used for body text (fails contrast on paper). It may be used for large display type, surfaces, rules, and captions at 18px+ bold equivalent only if contrast-checked.
- The default page is light and warm (`--color-paper`), not dark. Black is reserved for select immersive moments: the footer, and optionally the project hero treatment. The identity's printed applications are mostly warm neutrals; the site should feel the same. Do not build a dark site with a neon accent.
Typography:
- Brand typeface: **JL Jungka** (Jung-Lee Type Foundry), used for the wordmark and identity. Confirm webfont licensing before launch; self-host WOFF2, subset to Latin, `font-display: swap`. Until the licensed files are supplied, build with a metric-compatible fallback stack and a single `--font-sans` custom property so the swap is one line.
- One family, multiple roles: display (large, tight), body (regular, generous leading), utility (small caps or reduced size for facts bars, captions, labels).
- Fluid type scale via `clamp()`. Display headlines are the personality of the site; be generous at desktop, controlled at mobile.
- Wordmark is lowercase `hkla`. It is an SVG asset, never set in live text.
Layout and motion:
- Generous whitespace, strong grid, editorial rhythm. Wide full-bleed imagery alternating with measured text columns (roughly 65ch).
- One signature motion idea, used with restraint: a drawn-line reveal on sketch spotlights (SVG stroke animation), echoing the hand-drawing method. Everything else limits itself to gentle fades and image reveals on scroll. Respect `prefers-reduced-motion` fully: all motion off, content fully visible.
- No parallax, no cursor effects, no scroll-jacking.
---
## 3. Site map and templates
```
/                     front-page.php       Home
/projects/            archive-project.php  Filterable index
/projects/{slug}/     single-project.php   Narrative case study
/process/             page-process.php
/people/              page-people.php
/careers/             page-careers.php
/purpose/             page-purpose.php
/contact/             page-contact.php
/journal/             home.php + single.php  (phase 2, standard posts)
404                   404.php
```
Global: `header.php` (wordmark left, five nav items: Projects, Process, People, Purpose, Contact), `footer.php` (positioning line, address, careers and journal links, social, on `--color-ink`).
Redirects at launch: `/process-of-design → /process`, `/about → /people`. Before launch, crawl the old Wix sitemap.xml and 301 every legacy project URL to its new `/projects/{slug}/` equivalent.
### Page structure summary
**Home:** hero (featured project image or video + positioning line) → mission statement, set large → 3 or 4 featured project cards (story headline, name, sector, location) → process teaser (one sketch beside one photo of the same place, link to Process) → single quote → purpose stat band → recognition strip (award names) → contact invitation ("Every client works directly with our senior team.").
**Projects index:** framing line → sector filter (Civic + Parks, Education, Healthcare, Infrastructure, Institutional + Commercial) → editorial card grid. Filter works server-side via taxonomy URLs with progressive-enhancement JS; no client-side-only filtering.
**Single project (the core template):** full-bleed hero with project name and location → story headline in display type + narrative intro → facts bar (client, location, size, completion, services) → flexible narrative sections (see content model) → impact block (summary + metrics) → credits (collaborators, awards) → next project (same sector).
**Process:** framing statement ("Before a line is drawn, we find the story.") → four stages, each a short statement, a paragraph, and artifact imagery: 1 Listen, 2 Find the story, 3 Draw by hand, 4 Build together → a section addressed to architecture-firm partners with a partner quote → link to Projects.
**People:** WHO framing → founder feature (Hongjoo Kim) → team grid from the `person` CPT → "Boutique by design" statement → recognition list → careers invitation.
**Careers:** culture statement → studio imagery → open roles from site settings → speculative application contact.
**Purpose:** "Design as a civic act" framing → four commitments (climate resilience, water management, habitat support, long-term maintainability), each with project proof links → community outcomes → aggregate numbers.
**Contact:** senior-access statement → form (name, email, organization, inquiry type select: new project / RFP / collaboration / press / careers, message) → address, phone, email, social. Office: 714 West Olympic Blvd, Suite 735, Los Angeles, CA 90015 (confirm before launch).
---
## 4. Content model
Register CPTs and taxonomies in code (an `inc/post-types.php` in the theme or an mu-plugin), never via a UI plugin. Save all ACF field groups as JSON in `acf-json/` for version control.
### CPT: `project` (public, archive at /projects/, supports title + thumbnail)
Taxonomy `sector` (non-hierarchical, public): `civic-parks`, `education`, `healthcare`, `infrastructure`, `institutional-commercial`.
ACF field groups:
**Overview**
| Field | Type | Notes |
|---|---|---|
| `story_headline` | text | Required. Under 10 words. |
| `story_intro` | wysiwyg (minimal toolbar) | Required. 2 to 3 paragraphs. |
| `hero_image` | image | Required. Min width 2400px, enforce via validation message. |
| `hero_video` | url | Optional. Self-hosted MP4 or Vimeo. |
**Facts**
`client` (text), `location` (text), `size` (text, e.g. "4.2 acres"), `completion` (text, e.g. "2025" or "In progress"), `services` (text).
**Story sections** (`project_sections`, flexible content). Layouts:
| Layout | Fields |
|---|---|
| `text` | `heading` (optional), `body` (wysiwyg minimal) |
| `full_image` | `image`, `caption` |
| `image_pair` | `image_a`, `image_b`, `caption` |
| `gallery` | `images` (gallery) |
| `sketch` | `image`, `caption`. Distinct treatment: white ground, generous margins, drawn-line reveal. |
| `voice` | `quote`, `name`, `role` |
| `stats` | repeater: `value`, `label`, `note` |
| `video` | `video_url`, `poster` |
**Impact**
`impact_summary` (textarea), `impact_metrics` (repeater: `value`, `label`).
**Credits**
`collaborators` (repeater: `name`, `role`), `awards` (repeater: `title`, `organization`, `year`), `related_projects` (relationship, optional; fallback: latest in same sector).
### CPT: `person` (no single view needed; rendered on /people/)
`role_title` (text), `credentials` (text, e.g. "PLA, ASLA"), `bio` (wysiwyg minimal), `headshot` (image), `is_leadership` (true/false). Order via native menu_order with a drag-sort plugin or Post Types Order.
### Options page: Site Settings (ACF options)
`address`, `phone`, `email`, `rfp_email`, `instagram_url`, `linkedin_url`, `footer_line` (default "Shared spaces. Shared stories."), `careers_intro` (wysiwyg minimal), `open_roles` (repeater: `role_title`, `role_type`, `role_location`, `description`, `apply_link`), `default_og_image`.
### Pages
Home, Process, People, Purpose, Careers, Contact each get their own ACF field group mirroring the page structures in section 3 (headline, statement, and image fields plus repeaters where listed). No Gutenberg free-form content on these pages: assign a blank editor and drive everything from fields, so editors change words and images, never structure.
### Journal (phase 2)
Native posts, categories News / Awards / Ideas. This is the one place editors use the block editor, restricted to core text and image blocks via an `allowed_block_types_all` filter.
---
## 5. Editability and guardrails
- Roles: client users are **Editors**. No plugin, theme, or user administration.
- `theme.json`: define the five brand colors as the palette; set `settings.color.custom: false`, `settings.color.customGradient: false`, `settings.typography.customFontSize: false`. Editors physically cannot introduce off-brand colors or sizes.
- WYSIWYG toolbars limited to: paragraph, bold, italic, link, lists. No headings inside body fields (headings come from dedicated fields).
- Alt text: surface a persistent admin notice or use an accessibility plugin check so images without alt text are flagged before publish.
- Image guidance in field instructions: hero 2400px+ wide, gallery 1600px+, headshots 800px square. Uploads auto-converted to WebP/AVIF (plugin or host level).
- Admin cleanup: remove comments, hide unused menus, rename "Posts" to "Journal," add a dashboard widget titled "How to update this site" linking to the editor guide (deliverable in phase 4).
---
## 6. Technical requirements
**Stack**
- Custom classic theme (PHP templates + `theme.json` for editor settings). No page builders, no site editor for structure, no starter theme bloat. Vanilla PHP, one compiled CSS file, small vanilla JS (no jQuery dependency).
- Plugins: ACF Pro, SEOPress or Yoast, a caching layer appropriate to the host, Safe SVG, WP Forms or Gravity Forms (with honeypot + server-side validation), Redirection (or server-level 301s), an image optimization plugin if the host lacks WebP/AVIF.
- ACF JSON committed; CPTs in code; a `composer.json` or documented plugin list so environments reproduce.
- Hosting assumption: managed WordPress (WP Engine, Kinsta, or Flywheel) with staging. Build and demo on a staging URL.
**Performance budget**
- LCP under 2.5s on mid-range mobile, CLS under 0.1, INP under 200ms.
- Responsive images everywhere (`srcset`, explicit width/height), lazy-load below the fold, eager-load the hero with `fetchpriority="high"`.
- Hero images under ~350KB delivered; video posters required; no autoplaying video on mobile data-saver.
- Fonts subset, preloaded, `font-display: swap`. Total JS under 50KB gzipped.
**Accessibility (WCAG 2.1 AA)**
- Follow the palette usage rules in section 2; they are contrast-derived.
- Full keyboard support for nav, filters, galleries, and forms. Visible focus states in `--color-signal` on dark, `--color-ink` on light.
- Semantic landmarks and heading order; skip link; `prefers-reduced-motion` honored; form errors described in text, not color alone.
**SEO**
- Schema.org: `Organization` sitewide (with LA address), `BreadcrumbList`, each project as `CreativeWork`/`Article`.
- Per-project meta via the SEO plugin; XML sitemap; canonical URLs; social OG images (fallback from Site Settings).
- Preserve equity: the 301 map from section 3 goes live at launch.
- Analytics: Fathom or Plausible preferred (privacy-friendly, no consent banner burden); GA4 only if the client requires it.
---
## 7. Build plan for Claude Code
Give Claude Code this file at the repo root (or reference it in CLAUDE.md), plus the asset pack from section 8. Work in phases; each ends in something reviewable.
**Phase 0: Scaffold.** Suggested prompt:
> Read HKLA_WordPress_Brief_Claude_Code.md. Scaffold a custom WordPress theme named `hkla` per section 6: theme.json with the section 2 tokens and editor restrictions, CPT and taxonomy registration per section 4, ACF field groups as JSON in acf-json/, and empty templates for every route in section 3. Set up local dev (wp-env or the host's local tool). No visual design yet.
**Phase 1: Templates with seed content.** Suggested prompt:
> Build front-page, archive-project, and single-project per sections 2 and 3. Seed three sample projects with placeholder imagery and copy written in the brand voice (section 2). Follow the palette usage rules exactly. Desktop and mobile.
**Phase 2: Remaining pages and interactions.** Process, People, Purpose, Careers, Contact, the sector filter, the contact form, and the flexible section renderers including the sketch treatment.
**Phase 3: Polish.** Performance budget, accessibility pass against section 6, schema, redirects, 404, OG images, the drawn-line motion with reduced-motion fallback.
**Phase 4: Handoff.** A one-page editor guide (add a project, edit a bio, post a role), the dashboard widget, a launch checklist (redirect verification, form deliverability, backups, sitemap submission), and content-entry of the real projects.
Review gates: end of phase 1 is the moment to evaluate the design direction against the identity deck before building outward. Keely reviews; nothing in phase 2 starts until the project template feels right.
---
## 8. Asset pack to supply alongside this brief
- [ ] `hkla` wordmark SVGs: primary (ink), reversed (paper/white), favicon set
- [ ] JL Jungka WOFF2 files + webfont license confirmation
- [ ] Final palette hex values (adjustments pending from Kilograph; tokens above are provisional)
- [ ] 15 to 20 project photographs and 3 to 5 hand sketches for seed content
- [ ] Brand DNA PDF and identity deck (source of truth for copy and design decisions)
- [ ] Old-site sitemap export for the 301 map
- [ ] Hosting credentials or the chosen local/staging setup
## 9. Acceptance criteria
- An editor can create a complete project, publish it, and it looks designed, with zero layout decisions made by the editor.
- No path exists in wp-admin for an editor to change colors, fonts, or page structure.
- Lighthouse: 90+ performance on mobile for Home and a media-heavy project page; 100 accessibility.
- Axe or equivalent: zero critical issues on every template.
- All legacy URLs 301 correctly; forms deliver and are spam-protected.
- The site reads as the identity deck's "In Conversation" direction: warm, typographic, editorial, with photography and sketches doing the expressive work.

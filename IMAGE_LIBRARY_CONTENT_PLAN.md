# Mumias Vipers CBO — Image Library Content Plan

**Version:** 1.0  
**Date:** 2026-10-06  
**Status:** Draft for Review

---

## 1. Current State Assessment

### Existing Assets (public/assets/img/)
| Category | Count | Status | Notes |
|----------|-------|--------|-------|
| `/logo/` | 1 (vps.jpeg) | ✅ Optimized | 1600×1123, landscape, logo lockup |
| `/home/` | 8 | ⚠️ Mixed | 7 WhatsApp exports, 1 team photo (teamb.jpg) |
| `/programmes/` | 0 | ❌ Missing | Placeholders only |
| `/gallery/` | 0 | ❌ Missing | Gallery component exists but no images |
| `/stories/` | 0 | ❌ Missing | Story cards use placeholders |
| `/partners/` | 0 | ❌ Missing | Partner cards use named cards, no logos |

### Key Gaps
- **Zero programme-specific imagery** — 4 programmes in config, all use placeholders
- **Gallery empty** — Lightbox component wired but no source images
- **Stories rely on placeholders** — No evidence photography
- **Partner logos absent** — Policy: "No logos without written permission" (correct)

---

## 2. Categorization Framework

### 2.1 Master Taxonomy (Folder Structure)

```
public/assets/img/
├── logo/
│   ├── vps-primary.jpeg          # Primary lockup (current)
│   ├── vps-white.svg             # Knockout for dark backgrounds
│   ├── vps-gold.svg              # Single-color gold variant
│   └── favicon/                  # Generated set (32, 64, 180, 192, 512)
├── hero/
│   ├── home-hero-primary.jpg     # Homepage hero (1920×1080 min)
│   └── home-hero-mobile.jpg      # 1080×1350 portrait crop
├── programmes/
│   ├── football-development/
│   │   ├── hero.jpg              # 16:9, 1920×1080
│   │   ├── gallery-01.jpg        # Action shot
│   │   ├── gallery-02.jpg        # Training detail
│   │   └── card.jpg              # 4:3, 800×600 (programme index card)
│   ├── education-scholarships/
│   │   ├── hero.jpg
│   │   ├── gallery-01.jpg
│   │   ├── gallery-02.jpg
│   │   └── card.jpg
│   ├── health-wellbeing/
│   │   ├── hero.jpg
│   │   ├── gallery-01.jpg
│   │   ├── gallery-02.jpg
│   │   └── card.jpg
│   └── peace-leadership/
│       ├── hero.jpg
│       ├── gallery-01.jpg
│       ├── gallery-02.jpg
│       └── card.jpg
├── impact/
│   ├── evidence-01.jpg           # "500+ youth reached"
│   ├── evidence-02.jpg           # "12 scholarships awarded"
│   ├── evidence-03.jpg           # "3 peace tournaments"
│   └── evidence-04.jpg           # "STEM lab launch"
├── stories/
│   ├── [slug]-01.jpg             # Story hero (16:9)
│   ├── [slug]-02.jpg             # Inline/body image
│   └── [slug]-card.jpg           # 4:3 card thumbnail
├── gallery/
│   ├── 2026-q1/                  # Quarterly batches
│   │   ├── match-01.jpg
│   │   ├── training-02.jpg
│   │   ├── classroom-03.jpg
│   │   └── ...
│   ├── 2026-q2/
│   └── ...
├── team/
│   ├── staff/
│   │   ├── director-jane.jpg     # 1:1, 600×600
│   │   └── ...
│   ├── coaches/
│   └── volunteers/
├── partners/
│   ├── (named-cards-only/)       # Per policy: names only, no logos
│   └── partnership-events/
│       ├── launch-event-01.jpg
│       └── ...
└── raw/                          # Staging area for new uploads
    └── YYYY-MM-DD_source/
```

### 2.2 Metadata Requirements (per image)

| Field | Required | Example |
|-------|----------|---------|
| `filename` | Yes | `football-development-card.jpg` |
| `alt_text` | Yes | "Under-14 players in a passing drill at Mumias Vipers training session" |
| `credit` | Yes | "Photo: Mumias Vipers CBO / Jane Otieno" |
| `date_taken` | Yes | "2026-03-15" |
| `programme` | If applicable | "football-development" |
| `consent_verified` | Yes | true/false (child protection) |
| `usage_rights` | Yes | "org-owned" / "partner-permission" / "cc-by" |
| `crop_16x9` | Auto | Generated |
| `crop_4x3` | Auto | Generated |
| `crop_1x1` | Auto | Generated |
| `webp_1920` | Auto | Generated |
| `webp_800` | Auto | Generated |
| `webp_400` | Auto | Generated |

---

## 3. Visual Consistency Standards

### 3.1 Technical Specifications

| Use Case | Aspect Ratio | Min Width | Formats | Compression |
|----------|--------------|-----------|---------|-------------|
| Hero / Banner | 16:9 | 1920px | WebP + JPEG fallback | 85% quality, ≤200KB |
| Programme Card | 4:3 | 800px | WebP + JPEG fallback | 80% quality, ≤120KB |
| Gallery Grid | 4:3 / 1:1 | 1200px | WebP + JPEG fallback | 80% quality, ≤150KB |
| Story Hero | 16:9 | 1600px | WebP + JPEG fallback | 85% quality, ≤180KB |
| Story Inline | 4:3 / 3:2 | 1200px | WebP + JPEG fallback | 80% quality, ≤150KB |
| Team Portrait | 1:1 | 600px | WebP + JPEG fallback | 80% quality, ≤80KB |
| Logo | Native | 512px | SVG (primary), PNG fallback | Lossless |
| Favicon Set | 1:1 | 512px | ICO, PNG, SVG | — |

### 3.2 Visual Style Guide

| Attribute | Standard |
|-----------|----------|
| **Color palette** | Navy (#062B57), Gold (#D4A843), Cyan (#06B6D4), Green (#10B981) — images should complement, not compete |
| **Lighting** | Natural light preferred; avoid harsh flash; golden hour for outdoor |
| **Composition** | Rule of thirds; leave negative space for text overlay (top/left safe zones) |
| **Subject focus** | Youth agency — children active, not posed; coaches facilitating, not dominating |
| **Diversity** | Gender balance, age range (U10–U18), ability representation |
| **Branding** | Subtle — small Vipers badge on kit OK; no watermarks, no overlays |
| **Prohibited** | Stock imagery, staged "handshake" photos, identifiable medical/health procedures, unconsented minors |

### 3.3 Child Protection & Consent

- **Every image of a minor** must have signed guardian consent on file
- **Filenames** must not contain child names
- **Metadata** must include `consent_verified: true`
- **Cropping** — faces of non-consented children must be blurred/excluded
- **Storage** — consent forms stored in `/docs/consent/` (not public)

---

## 4. Deployment Schedule

### Phase 1: Foundation (Week 1–2) — *Critical Path*

| Task | Owner | Deliverable |
|------|-------|-------------|
| Audit new image batch | Comms Lead | Spreadsheet: filename, source, programme, consent status |
| Rename to taxonomy | Comms + Dev | Files in `/raw/2026-10-XX_source/` with standard names |
| Generate derivatives | Dev (script) | WebP/JPEG at 3 widths per image |
| Upload logo variants | Designer | SVG logo, favicon set |
| Update `config/vipers.php` | Dev | Programme `image` + `image_alt` fields populated |

**Gate:** All 4 programme cards have real images; hero image on homepage.

---

### Phase 2: Gallery & Stories (Week 3–4)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Curate 20–30 gallery images | Comms | Quarterly folders (2026-q1, q2…) with metadata CSV |
| Build gallery index | Dev | `gallery.blade.php` consumes folder structure |
| Create 3–5 story entries | Comms + Dev | Story slugs, heroes, inline images, cards |
| Lightbox QA | Dev | Keyboard nav, swipe, caption rendering |

**Gate:** Gallery page loads with 20+ images; 3 stories render with real imagery.

---

### Phase 3: Impact & Team (Week 5–6)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Evidence photography | Comms | 4 images for impact bar (matching config evidence) |
| Team portraits | Comms | 8–12 staff/coach portraits (1:1, consistent backdrop) |
| About section refresh | Comms | 2–3 new images for "Who we are" split section |
| Partner event photos | Comms | 3–5 partnership event images (no logos) |

**Gate:** Impact bar shows real photography; team page (if added) populated.

---

### Phase 4: Automation & Maintenance (Week 7–8)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Image optimization pipeline | Dev | GitHub Action / Vite plugin: auto WebP, resize, compress on commit |
| Metadata validation | Dev | CI check: every image in `/img/` has matching `.json` sidecar |
| Quarterly review calendar | Comms | Recurring task: "Curate Q{next} gallery batch" |
| Backup & archive | Dev | S3 / cloud sync for raw originals |

**Gate:** New images dropped in `/raw/` → auto-processed → appear in gallery/programmes without manual CSS edits.

---

## 5. Platform Distribution Strategy

### 5.1 Website (Primary)
- **Source of truth** — all optimized derivatives live in `public/assets/img/`
- **Responsive delivery** — `<picture>` with WebP + JPEG, `srcset` at 3 breakpoints
- **Lazy loading** — `loading="lazy"` on all below-fold images
- **CDN-ready** — paths relative, no hardcoded domains

### 5.2 Social Media (Secondary)
| Platform | Ratio | Export from Master |
|----------|-------|-------------------|
| Facebook/LinkedIn | 1.91:1 (1200×628) | Hero 16:9 crop |
| Instagram Feed | 1:1 (1080×1080) | Gallery 1:1 crop |
| Instagram Stories | 9:16 (1080×1920) | Hero mobile crop |
| Twitter/X | 16:9 (1200×675) | Hero 16:9 crop |
| WhatsApp Business | 1:1 / 4:3 | Card/thumb crop |

**Workflow:** Comms exports from master derivatives using a preset (Photoshop / Figma / CLI) — never re-upload originals.

### 5.3 Print & Offline
- **Annual report** — 300 DPI, CMYK exports from master TIFF/RAW
- **Banners/posters** — 150 DPI at final size, PDF/X-1a
- **Partner decks** — 1920×1080 PNG, branded template

---

## 6. Implementation Checklist (Developer)

### 6.1 Config Updates (`config/vipers.php`)

```php
'programmes' => [
    'football-development' => [
        'image' => 'assets/img/programmes/football-development/card.jpg',
        'image_alt' => 'Under-14 players in a passing drill at Mumias Vipers training',
        // ...
    ],
    // ... 3 more
],
'evidence' => [
    ['type' => 'number', 'value' => '500+', 'label' => 'Youth reached', 'image' => 'assets/img/impact/evidence-01.jpg'],
    // ...
],
'stories' => [
    ['slug' => 'first-scholarship', 'hero' => 'assets/img/stories/first-scholarship-hero.jpg', ...],
    // ...
],
```

### 6.2 Component Updates

| Component | Current | Target |
|-----------|---------|--------|
| `programme-card` | Placeholder | `<picture>` with `srcset` (400/800/1200w) |
| `gallery-grid` | Empty | Dynamic: reads `/gallery/{quarter}/` + metadata CSV |
| `story-card` | Placeholder | Real hero + card crop |
| `impact-bar` | Icon only | Icon + evidence thumbnail (optional) |
| `about-split` | WhatsApp photo | Curated `teamb.jpg` (already done) |

### 6.3 Build Pipeline (Vite)

```js
// vite.config.js addition
import { glob } from 'glob'
import imagemin from 'vite-plugin-imagemin'

export default {
  plugins: [
    imagemin({
      gifsicle: { optimizationLevel: 3 },
      mozjpeg: { quality: 80 },
      pngquant: { quality: [0.7, 0.8] },
      webp: { quality: 80 },
    }),
  ],
  build: {
    assetsInlineLimit: 0, // never inline images
  },
}
```

---

## 7. Governance & Roles

| Role | Responsibility |
|------|----------------|
| **Comms Lead** | Curation, consent, metadata, quarterly planning |
| **Developer** | Pipeline, derivatives, component integration, CI |
| **Designer** | Visual standards, logo variants, export presets |
| **Programme Managers** | Source imagery from their activities, flag consent gaps |
| **Director** | Final sign-off on sensitive imagery (health, scholarships) |

---

## 8. Risk Mitigation

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Consent gaps on legacy images | High | Legal/Reputational | Audit all existing images; quarantine unconsented |
| Storage bloat (raw originals) | Medium | Cost/Performance | Auto-archive raw to S3 after processing; keep only derivatives in repo |
| Inconsistent crops | Medium | Visual debt | Enforce aspect ratios via CI; reject non-conforming uploads |
| Missing alt text | High | Accessibility | Required field in metadata CSV; CI fails build if missing |
| Programme imagery stale | Medium | Credibility | Quarterly refresh calendar; each PM owns their programme folder |

---

## 9. Quick-Start: First 48 Hours

1. **Inventory** — Drop all new images into `/raw/2026-10-06_initial-batch/`
2. **Spreadsheet** — Columns: `filename, programme, alt_text, credit, date, consent`
3. **Rename** — `programme-slug-description-N.jpg` (e.g., `football-development-training-01.jpg`)
4. **Run optimizer** — `npx @squoosh/cli --webp '{"quality":80} --resize '{"width":1920}' --resize '{"width":800}' --resize '{"width":400}' raw/**/*.jpg`
5. **Move to taxonomy** — Copy derivatives to correct folders under `/img/`
6. **Update config** — Populate 4 programme `image` + `image_alt` fields
7. **Test** — `npm run build && php artisan test`

---

## 10. Success Metrics (90 Days)

| Metric | Target |
|--------|--------|
| Programme cards with real imagery | 100% (4/4) |
| Gallery images published | ≥40 |
| Stories with hero + card images | ≥5 |
| Average image weight (WebP) | <120KB |
| Lighthouse Performance (images) | ≥90 |
| Zero console 404s for images | ✅ |
| All images have alt + credit metadata | 100% |

---

## Appendix A: Metadata CSV Template

```csv
filename,alt_text,credit,date_taken,programme,consent_verified,usage_rights
football-development-card.jpg,"Under-14 players in a passing drill at Mumias Vipers training","Photo: Mumias Vipers CBO / Jane Otieno",2026-03-15,football-development,true,org-owned
football-development-gallery-01.jpg,"Coach demonstrating technique to U12 squad","Photo: Mumias Vipers CBO / Jane Otieno",2026-03-15,football-development,true,org-owned
education-scholarships-hero.jpg,"Scholarship recipients in classroom setting","Photo: Mumias Vipers CBO / Peter Ochieng",2026-02-20,education-scholarships,true,org-owned
...
```

---

## Appendix B: CLI Tooling (Optional)

```bash
# One-liner to generate all derivatives from a source folder
npx @squoosh/cli \
  --webp '{"quality":80}' \
  --mozjpeg '{"quality":80}' \
  --resize '{"width":1920,"fit":"inside"}' \
  --resize '{"width":800,"fit":"inside"}' \
  --resize '{"width":400,"fit":"inside"}' \
  --output-dir public/assets/img/processed/ \
  raw/2026-10-06_initial-batch/*.jpg
```

---

**Next Step:** Review with Comms Lead + Developer. Assign Phase 1 tasks. Set quarterly calendar invites.
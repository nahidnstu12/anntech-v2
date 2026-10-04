# Phase 5 — Content management (CMS)

**Depends on:** Phase 2 (RBAC, media upload infra). Can run after Phase 3/4 — no blocker either way.

**Goal:** Enovak updates products, services, showcase, company copy, client logos, and images **without** editing PHP or redeploying for copy changes. Public Blade **views stay the same**; only the content **loader** switches from `content.php` to DB (with optional export/sync to PHP for disaster fallback — not required for v1).

Until this phase ships, keep using `resources/data/content.php` and `content-for-client.txt`.

---

## Client CMS checklist (contract)

| Module | Public surface |
| --- | --- |
| Company settings | Footer, contact block, SEO fields, map URLs |
| Services | Landing `#services` cards |
| Product categories + products | `/products`, `/products/{slug}` |
| Showcase | Landing `#showcase` (projects / achievements) |
| Client logos | New landing section or footer strip — **define layout in slice 5.0** |
| Media | Product images, brochures, OG image, leadership photo |
| Nav labels | Optional; hrefs mostly fixed per IA |

**Not in CMS v1:** invoice clients, contact inquiries (separate modules).

---

## Data model (maps from `content.php` keys)

Tables (names indicative):

- `company_settings` — key/value or single row JSON for `company`, `seo`, `landing.hero` partials
- `services` — slug, name, summary, sort_order, is_visible
- `product_categories` — slug, name, description, sort_order
- `products` — slug, category_id, name, short_description, specs JSON, brochure_path, featured bool, sort_order
- `product_images` — product_id, path, sort_order
- `showcase_items` — slug, title, year, industry, process, summary, sort_order, images
- `client_logos` — name, logo_path, url nullable, sort_order, is_visible

Migration strategy: one-time **import command** `php artisan content:import-from-php` reads `content.php` and fills tables.

Public loader:

```php
// App\Support\SiteContent::all(): array — same shape as content.php
```

Blade still uses `$c['products']` etc.

---

## Permissions

From requirements: `manage-company-settings`, `manage-services`, `manage-products`, `manage-showcase`, `manage-client-logos`, `manage-media`.

Split so marketing staff can edit products but not company bank details if needed (optional fine-grained later).

---

## Storage

- Disk `public` or `s3`-compatible for uploads; brochures PDF; images under `/storage` symlink.
- On upload: validate mime/size; generate activity log `content` / `media`.

---

## Activity log

| Event | log_name |
| --- | --- |
| Product updated | content |
| Service reordered | content |
| Showcase item published | content |
| Logo added/removed | content |
| Company phone changed | content |

Super admin feed filters `log_name=content`.

---

## API sketch

REST under `/api/admin/`:

- `company-settings` (GET/PATCH)
- `services`, `product-categories`, `products`, `showcase-items`, `client-logos`
- `media` POST upload → returns path URL

Use Form Requests + API Resources mirroring public JSON shape.

---

## React admin UI

- Sectioned CMS: Company, Landing hero/about (rich text fields), Services, Products (nested images + specs editor), Showcase, Client logos
- Reuse patterns from Users/Inquiries modules (tables, forms, confirm delete)

---

## Implementation slices

| Slice | Deliverable |
| --- | --- |
| **5.0** | Sign-off: client logo section design on landing |
| **5.1** | Migrations + import from `content.php` |
| **5.2** | `SiteContent` loader + feature flag `CONTENT_DRIVER=db` |
| **5.3** | Services + company + hero/about API/UI |
| **5.4** | Products + categories + media |
| **5.5** | Showcase + client logos |
| **5.6** | Remove PHP edit path from docs; keep import/export commands for backup |

---

## Phase 5 done when

- [ ] Changing product name in admin updates `/products/{slug}` without deploy
- [ ] Public array shape matches pre-CMS (regression checklist vs old `content.php`)
- [ ] Brochure upload links on product detail
- [ ] Activity shows who changed what
- [ ] `content-for-client.txt` export command optional for client review workflow

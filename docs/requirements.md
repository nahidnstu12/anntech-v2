# Enovak — Project Build Plan



## Project Overview

Company website + admin for **Enovak** (est. 2007). Industrial / pharmaceutical equipment supply and turnkey engineering, Bangladesh market.

Reference IA/tone (not copy): precisabd.com, dreksassociates.com, sakaint.com — see `docs/competitor.md`.

**Stack:**
- Public site: Laravel + Blade + Tailwind
- Data store: Phase 1 = one static PHP file. Phase 2+ = MySQL
- Admin: React SPA + Tailwind (served at `/admin` or subdomain)
- Auth (Phase 2+): Sanctum
- Later: Spatie Permission, Spatie Activitylog, DomPDF, queued Laravel Mail

**Build approach:** Phase 1 is a working public site with zero DB. Same content shape later becomes MySQL + dashboard CRUD. Do not rewrite the Blade layer when that happens.

---

## Site IA (locked)

Not a 7-page marketing site. Three routes only:

| Route | What it is |
| --- | --- |
| `/` | Landing page. All sections below live here. |
| `/products` | Product catalog (filter by category) |
| `/products/{slug}` | Product detail (specs, images, brochure) |

**Nav (same on every page, follows the landing sections):**

`Home · About · Services · Products · Showcase · Contact`

- On `/`: in-page anchors (`#about`, `#services`, `#showcase`, `#contact`). `Products` is the only item that leaves the page (`/products`).
- On `/products` and `/products/{slug}`: Home/About/Services/Showcase/Contact point back to `/#…`. Products stays active.

**Landing sections (in order):**
1. Hero
2. About (story, what we do, values)
3. Services (grid of offerings — no separate service routes)
4. Featured products teaser → `/products`
5. Client showcase = **project history / achievements**, not a logo wall
6. Contact (form + office info + map)

No `/about`, `/services`, `/clients`, `/portfolio`, `/contact` routes.

---

## Phase 1 — Public site, static content, no DB

**Goal:** Deployable marketing site. Content lives in **one file** (`resources/data/content.php` — shape defined in `docs/data-shape.js`). Blade includes that file and renders. No migrations, no models, no admin, no auth.

**Allowed Laravel surface:**
- Routes + Blade + Tailwind
- Static assets in `public/`
- Contact form: POST to a single controller that **emails** Enovak (queued). Do not persist. No table.

**Not in Phase 1:** MySQL, Eloquent, Sanctum, seeders, storage disks, API.

**Deliverable:** Live landing + products + product detail. Edit content by changing the one PHP file and redeploying.

When adding a field later, add it to the PHP file first using the same keys Phase 2 will store in MySQL. Do not invent a second content shape.

---

## Phase 2 — MySQL + Admin CMS (React + Tailwind)

**Goal:** Same Blade views, new data source. Move `content.php` into MySQL. React+Tailwind dashboard CRUD. Public site keeps reading the same fields.

**Introduced here (not before):**
- MySQL
- Laravel API (Sanctum, one admin user)
- React + Tailwind SPA
- Image upload (local disk, S3-shaped so R2 can swap in later)
- Contact submissions table + inbox (view / mark read)

**Tables (first time they exist):**
`company_settings`, `services`, `product_categories`, `products`, `product_images`, `showcase_items`, `contact_submissions`

`showcase_items` = completed projects / achievements (title, year, summary, images). Not invoice clients.

**Deliverable:** Admin can update all public content without a code deploy. Blade does not change except swapping the content loader (PHP file → Eloquent).

---

## Phase 3 — User Management & Authorization

Multi-user dashboard. Spatie Permission + Activitylog from this phase forward.

**Roles:** Owner (1), Director (1), Admin (1, content/products), Staff (≤5, permission-based)

**Permissions:**
`manage-content`, `manage-products`, `manage-services`, `manage-showcase`, `create-invoice`, `send-invoice`, `view-own-invoices`, `view-all-invoices`, `manage-users`, `view-activity-log`

User CRUD, role + per-user permission overrides, password reset, activity log UI (Owner/Director only).

---

## Phase 4 — Invoice / Order Management

Manual invoices. No payment gateway.

- `invoice_clients` table (company, contact, email, phone, address) — **not** showcase items
- Line items, auto subtotal/tax/total
- Numbering `ENV-2026-0001` (sequential, never reused)
- PDF (DomPDF) + queued email with PDF attached
- Status: Draft → Sent → Paid (manual) → Overdue
- Audit via Phase 3 activity log

---

## Phase 5 (later, out of scope)

- Owner email inbox in dashboard
- Task assignment Owner → Staff

---

## Build Notes

- Phase 1: no DB, no API, no admin scaffolding.
- Blade (public) and React (admin) stay separate. React talks API-only.
- Contact + invoice email always queued.
- Laravel conventions from Phase 2 onward: Form Requests, Policies, API Resources.

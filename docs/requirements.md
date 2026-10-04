# Enovak — Project requirements & phase map

Industrial / pharmaceutical equipment supply and turnkey engineering (Bangladesh). Reference tone/IA: `docs/competitor.md` — not their copy.

---

## Client scope (contract) ↔ delivery

| Client ask | How we deliver | Phase |
| --- | --- | --- |
| Professional homepage, company intro | Landing hero + company block | **1** |
| About, history, mission & vision | Landing `#about` (+ optional mission/vision fields in content) | **1** / copy in **5** |
| Products: categories, details, specs, brochures | `/products`, `/products/{slug}` | **1** (static) → **5** (CMS) |
| Services | Landing `#services` grid (no separate service URLs) | **1** → **5** |
| Completed projects / portfolio | Landing `#showcase` (achievements / process types) | **1** → **5** |
| Client / company showcase | **Not** a logo wall in v1; showcase = project history. Client logos → **5** | **5** |
| Contact + office + map | Landing `#contact` + form | **1** |
| Contact form | POST + email | **1**; saved + inbox → **3** |
| Responsive, industrial design | Tailwind + Blade | **1** |
| Easy CMS (products, services, projects, logos, copy, images) | Admin CRUD | **5** (explicitly after ops features) |
| Inquiry management in system + email | DB + admin inbox | **3** |
| Invoices / quotations, PDF, email, status, customers | Invoice module | **4** |
| Staff users, roles, permissions | Spatie Permission | **2** |
| Who did what (invoices, logins, later content) | Spatie Activitylog + super-admin feed | **2** (base) → grows **3–5** |

**Intentional IA (do not expand without sign-off):** three public routes only — `/`, `/products`, `/products/{slug}`. No `/about`, `/services/{slug}`, `/portfolio` pages. Deep links use landing anchors.

Nav: `Home · About · Services · Products · Showcase · Contact`.

---

## Stack (locked)

| Layer | Choice |
| --- | --- |
| Public | Laravel 12, Blade, Tailwind, Vite |
| Admin | React + Tailwind SPA at `/admin` (or subdomain later) |
| API | `routes/api.php`, JSON `{ data, message }` / `{ message, errors }` |
| Auth (admin) | Sanctum stateful SPA, session cookies, CSRF |
| Public auth | None |
| Queue | `database` (or Redis prod); mail always queued |
| Permissions | `spatie/laravel-permission` |
| Audit | `spatie/laravel-activitylog` |
| PDF (Phase 4) | DomPDF (or project-standard PDF lib) |

App root: `site/`. Docs: repo `docs/`, `phases/`.

---

## Phase summary

Detailed specs: **`phases/README.md`**.

1. **Public site** — Static `resources/data/content.php`. Contact queues email only. No Enovak business tables.
2. **Admin + RBAC + audit base** — Login, super admin / admin / custom roles, permission gates, activity log + super-admin activity feed (filter by user, action, date).
3. **Contact inquiries** — Same public form; persist row; notify email; admin list/detail/status/notes; log actions.
4. **Invoices** — Invoice clients, line items, numbering, PDF, send email, statuses; permission-scoped; full audit on send/state changes.
5. **CMS** — Migrate content shape to MySQL; CRUD for copy, products, services, showcase, client logos, uploads; public loader reads DB instead of PHP file.

**Current focus:** finish Phase 1 gaps if any → Phase 2 → 3 → 4. Phase 5 when Enovak needs self-serve content without deploys.

---

## Roles & permissions (target)

**Built-in roles**

- **super_admin** — all permissions; only role that manages roles/permissions and views global activity feed.
- **admin** — default operational bundle (assign via seeder; tune per Enovak).
- **custom** — created by super_admin; named role + explicit permission checklist.

**Permission keys** (stable API for policies + React guards)

```
# Users & access
manage-users
manage-roles
view-activity-log

# Inquiries (Phase 3)
view-contact-inquiries
manage-contact-inquiries

# Invoices (Phase 4)
manage-invoice-clients
create-invoice
edit-invoice
send-invoice
delete-invoice-draft
view-all-invoices
view-own-invoices

# CMS (Phase 5)
manage-company-settings
manage-services
manage-products
manage-showcase
manage-client-logos
manage-media
```

`view-own-invoices`: staff see only invoices they created unless `view-all-invoices`.

---

## Content source (until Phase 5)

- Runtime: `site/resources/data/content.php`
- Client review export: `site/resources/data/content-for-client.txt` (no nav/SEO)
- Shape reference: `docs/data-shape.js`, `docs/data-shape.md`

Adding a public field: add to PHP array first; Phase 5 tables mirror the same keys.

---

## Out of scope (all phases unless new contract)

- Payment gateway / online pay
- Customer portal login
- Owner email inbox inside dashboard (future idea)
- Task assignment Owner → Staff (future idea)

---

## Build conventions (Phase 2+)

- Controllers: `App\Http\Controllers\Api\Admin\*`
- Form requests, policies, API resources per entity
- No closures in route files
- Activity log: meaningful `log_name` + properties (ids, old/new status, recipient email)
- Tests: only when explicitly requested

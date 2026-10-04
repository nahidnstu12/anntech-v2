# Enovak — phased build (AI / implementation index)

Read **`docs/requirements.md`** first for client scope ↔ phase mapping and locked IA.

| Phase | Doc | Status | Delivers |
| --- | --- | --- | --- |
| **1** | [p1.md](./p1.md) | In progress / largely built | Public Blade site, `content.php`, contact email (no DB) |
| **2** | [p2-admin-auth-rbac.md](./p2-admin-auth-rbac.md) | Next | `/admin` React SPA, Sanctum, users, Spatie roles/permissions, activity log base |
| **3** | [p3-contact-inquiries.md](./p3-contact-inquiries.md) | Planned | Persist inquiries, inbox, email unchanged, audit trail |
| **4** | [p4-invoices.md](./p4-invoices.md) | Planned | Quotations/invoices, PDF, email, statuses, invoice clients |
| **5** | [p5-cms-content.md](./p5-cms-content.md) | Later | Replace `content.php` with DB + CRUD (products, services, showcase, logos, copy) |

**Rule:** Public site stays Blade + same URLs. Admin is API-only (JSON). Do not merge admin into Blade.

**Content until Phase 5:** `site/resources/data/content.php` (+ `content-for-client.txt` for client review). No CMS tables before Phase 5.

**Packages (Phase 2 onward):** `laravel/sanctum`, `spatie/laravel-permission`, `spatie/laravel-activitylog`. Phase 4 adds `barryvdh/laravel-dompdf` (or equivalent).

When implementing a phase, execute slices **in order** inside that phase doc. Do not start a later phase until the previous phase “done when” checklist passes.

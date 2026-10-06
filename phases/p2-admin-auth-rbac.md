# Phase 2 — Admin shell, auth, RBAC, activity log

**Blueprint (read before coding):** [docs/p2-admin-auth-rbac-analysis.md](../docs/p2-admin-auth-rbac-analysis.md), [docs/p2-admin-auth-rbac-schema.md](../docs/p2-admin-auth-rbac-schema.md)

**Depends on:** Phase 1 public site running.  
**Blocks:** Phases 3–5 (all admin features).

**Goal:** Private `/admin` app. Staff log in. Super admin manages users and custom roles. Every sensitive action is permission-checked. Activity log records auth and admin actions; super admin gets a filterable feed.

Public site unchanged. Still no CMS — `content.php` only.

---

## In scope

| Area | Detail |
| --- | --- |
| Sanctum | Stateful SPA: `/sanctum/csrf-cookie`, session guard, `credentials: include` from React |
| Admin UI | React + Tailwind + RTK Query (or project fetch wrapper), built to `public/build/admin` or separate Vite entry |
| Routes | `GET /admin/{any?}` → SPA shell; `routes/api.php` under `/api/admin/*` |
| Users | CRUD (super_admin + `manage-users`), activate/deactivate, password reset flow |
| Roles | super_admin, admin (seeded), custom roles created by super_admin |
| Permissions | Spatie; sync permission list from `docs/requirements.md` (CMS keys inactive until Phase 5) |
| Activity | `spatie/laravel-activitylog` on User model + manual logs for login/logout, user/role changes |
| Dashboard | Minimal home: counts placeholders for inquiries/invoices; **Activity feed** widget for super_admin |

## Out of scope

- Contact persistence (Phase 3)
- Invoices (Phase 4)
- Editing public content in admin (Phase 5)
- Public registration

---

## Packages & config

```bash
composer require laravel/sanctum spatie/laravel-permission spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan migrate
```

- Seed: one `super_admin` user, `admin` role, permission rows, role-permission map.
- `config/permission.php`: teams off unless needed later.
- Activity: `config/activitylog.php` — log only dirty attributes where applicable.

---

## Data model

**users** (existing Laravel table + columns as needed)

- `is_active` boolean default true
- optional `phone`, `job_title`

**spatie tables:** `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`

**activity_log** (package default)

- Use `log_name`: `auth`, `user`, `role`, `system`
- `description`: human-readable sentence for feed
- `properties`: JSON `{ ip, user_agent, target_id, ... }`

---

## API sketch (admin)

Prefix: `/api/admin`. Middleware: `auth:sanctum`, then permission middleware per route.

| Method | Path | Permission |
| --- | --- | --- |
| GET | `/me` | authenticated |
| POST | `/logout` | authenticated |
| GET/POST/PATCH/DELETE | `/users` | `manage-users` |
| GET | `/roles` | `manage-roles` |
| POST/PATCH/DELETE | `/roles` | `manage-roles` |
| GET | `/permissions` | `manage-roles` |
| PUT | `/roles/{id}/permissions` | `manage-roles` |
| GET | `/activity` | `view-activity-log` |

**Activity index query params:** `user_id`, `log_name`, `from`, `to`, `search` (description), `page`.

---

## React admin structure

```
resources/js/admin/
  app.tsx
  routes/
  features/
    auth/
    users/
    roles/
    activity/
  lib/api.ts          # CSRF + credentials
```

- Route guard: fetch `/api/admin/me` + permissions array; hide nav items without permission.
- Super admin only: Roles, Activity log, Permission matrix.

---

## Activity events (Phase 2 minimum)

| Event | log_name | causer |
| --- | --- | --- |
| Login success | auth | user |
| Login failed | auth | null |
| Logout | auth | user |
| User created/updated/deactivated | user | actor |
| Role created/updated/deleted | role | actor |
| Permissions synced on role | role | actor |

Phases 3–5 append events; do not rename `log_name` values once shipped.

---

## Implementation slices

| Slice | Deliverable | Done when |
| --- | --- | --- |
| **2.1** | Sanctum + login API + React login page | Session works; 401 on protected route |
| **2.2** | Spatie install, seed super_admin + permissions | Tinker: user has role + can `can('manage-users')` |
| **2.3** | User CRUD API + UI | super_admin creates staff user |
| **2.4** | Role CRUD + assign permissions | Custom role with subset works |
| **2.5** | Activity log + `/activity` API + feed UI | Filters by user/date/log_name |
| **2.6** | Admin layout, nav, dashboard shell | Deployable `/admin` behind auth |

---

## Phase 2 done when

- [ ] Only authenticated users reach `/admin` routes
- [ ] super_admin can create custom role and assign permissions
- [ ] admin user without `manage-users` gets 403 on user API
- [ ] Activity feed visible only with `view-activity-log`
- [ ] Public site still served with zero admin coupling
- [ ] No `contact_submissions` or invoice tables yet

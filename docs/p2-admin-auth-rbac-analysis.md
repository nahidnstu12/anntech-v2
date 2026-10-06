# Phase 2 — Admin auth & RBAC (analysis)

**Spec:** [phases/p2-admin-auth-rbac.md](../phases/p2-admin-auth-rbac.md)  
**Schema:** [p2-admin-auth-rbac-schema.md](./p2-admin-auth-rbac-schema.md)  
**Permission keys (canonical):** [requirements.md](./requirements.md#roles--permissions-target)  
**Depends on:** Phase 1 public site (`site/`). Phase 1 doc stays as-is.

This document is the implementation blueprint before writing Phase 2 code. No guessing: each feature maps to a concrete rule, table, or API behavior. Open decisions are listed at the end with a recommendation.

---

## 1. Goals (what “done” means)

| Outcome | How we verify |
| --- | --- |
| Private `/admin` React SPA | Unauthenticated browser → login; deep links to `/admin/users` require auth |
| Staff auth via Sanctum **stateful** session | CSRF cookie + session cookie; **no** PAT / refresh-token flow |
| RBAC on every admin API route | Missing permission → `403`; missing session → `401` |
| Super admin bootstraps the org | **Only** super admin account (+ its role) comes from seed; they create staff, roles, permission assignments |
| Audit trail | Activity log for auth + user/role changes; filterable feed for holders of `view-activity-log` |
| Public site untouched | No admin assets required on `/`, `/products/*`; contact still Phase 1 behavior until Phase 3 |

---

## 2. Auth model (Sanctum SPA, 3-day session, no refresh token)

### 2.1 Mechanism

- **Guard:** `web` session (same as Laravel default). Sanctum `auth:sanctum` on `/api/admin/*` resolves to session when `EnsureFrontendRequestsAreStateful` runs and cookies are sent.
- **Login:** `POST /api/admin/login` (or dedicated auth prefix — see API section) validates email/password, checks `is_active`, calls `Auth::attempt()`, regenerates session id, logs activity.
- **Logout:** `POST /api/admin/logout` invalidates session + logs activity.
- **Current user:** `GET /api/admin/me` returns user profile, role name(s), and **effective permission list** for React guards.

**No refresh token:** Correct for this stack. Session expiry is controlled by Laravel `config/session.php` (`lifetime`), not Sanctum token abilities. Do not issue personal access tokens for admin UI.

### 2.2 Three-day expiry

- Set `SESSION_LIFETIME=4320` (minutes = 3 × 24 × 60).
- Default Laravel behavior: **idle** timeout — each authenticated request extends `last_activity` on the `sessions` row. This is standard “stay logged in while working.”
- **Not** a hard “absolute 3 days from login” unless we add custom middleware (out of scope unless you want it — see §8).

### 2.3 CSRF & cookies

- Admin SPA calls `GET /sanctum/csrf-cookie` before login and before mutating requests.
- API client: `credentials: 'include'`, `X-XSRF-TOKEN` from cookie.
- Configure `SANCTUM_STATEFUL_DOMAINS` and `SESSION_DOMAIN` for prod host(s).

### 2.4 Login rejection rules

| Condition | Response | Activity |
| --- | --- | --- |
| Wrong password | `422` or `401` (pick one project-wide) | `auth` / login failed; causer `null`; properties `{ email, ip, user_agent }` |
| Unknown email | Same as wrong password (no user enumeration) | Same |
| `is_active = false` | `403` with clear message | Optional failed attempt log |
| Success | `200` + user payload | `auth` / login success |

### 2.5 Remember me

`users.remember_token` exists from Laravel default. **Recommendation:** omit “Remember me” on admin login UI and do not pass `remember: true` on attempt — session lifetime alone defines persistence. Avoids a second, longer-lived mechanism beside the 3-day session.

---

## 3. Roles & permissions (Spatie)

### 3.1 Concepts

| Concept | Definition |
| --- | --- |
| **Permission** | Stable string key (`manage-users`, …) stored in `permissions`. Used in middleware `@can`, policies, and React nav guards. |
| **Role** | Named bundle assigned to users via `model_has_roles`. |
| **super_admin** | Built-in role name. Holds **all** permissions. Only role that may receive `manage-roles` and `view-activity-log` (enforced in app code, not only in UI). |
| **admin** | **Not seeded.** Operational template Enovak creates once (e.g. copy of default permission set from docs). Phase 3 doc assumes an `admin`-like role exists — super admin creates it. |
| **Custom roles** | Created by super admin: name + selected permissions (subset of catalog). |

### 3.2 Seeding policy (aligned with your direction)

**Seed in production:**

| Entity | Seed? | Notes |
| --- | --- | --- |
| `permissions` rows | **Yes** | Full catalog from `requirements.md` (Phase 2–5 keys). Inactive keys simply have no UI until that phase ships. |
| `super_admin` role | **Yes** | Single built-in role row. |
| `role_has_permissions` for `super_admin` | **Yes** | All permissions attached (or `Gate::before` shortcut — pick one; see §3.4). |
| `admin` role | **No** | Super admin creates when ready. |
| Users | **One** | Super admin only; credentials from env (`SUPER_ADMIN_EMAIL`, `SUPER_ADMIN_PASSWORD`) in seeder — never hardcode prod passwords. |

**Do not seed:** staff users, `admin` role, custom roles, or role-permission maps for non–super-admin roles.

**Phase doc note:** [p2-admin-auth-rbac.md](../phases/p2-admin-auth-rbac.md) still mentions seeding `admin` role — treat this analysis as the seed source of truth until that line is updated at implementation time.

### 3.3 Permission catalog (Phase 2 active vs dormant)

**Active in Phase 2 (middleware + UI):**

| Key | Purpose |
| --- | --- |
| `manage-users` | User CRUD, activate/deactivate, assign role |
| `manage-roles` | Role CRUD, sync permissions on role (**super_admin role only**) |
| `view-activity-log` | Paginated activity index + dashboard widget (**super_admin role only**) |

**Seeded but dormant until later phases** (no routes yet; appear in permission matrix grouped by prefix):

- Inquiries: `view-contact-inquiries`, `manage-contact-inquiries`
- Invoices: `manage-invoice-clients`, `create-invoice`, `edit-invoice`, `send-invoice`, `delete-invoice-draft`, `view-all-invoices`, `view-own-invoices`
- CMS: `manage-company-settings`, `manage-services`, `manage-products`, `manage-showcase`, `manage-client-logos`, `manage-media`

React should group by comment blocks in `requirements.md` and disable toggles for keys with no backend routes yet (optional) or allow pre-assigning for future-proofing (recommended: allow assign — no harm).

### 3.4 Super admin “all permissions” implementation

Pick **one** approach (avoid double logic):

| Approach | Pros | Cons |
| --- | --- | --- |
| **A.** Seed all permissions onto `super_admin` role | Spatie `can()` works everywhere; `/me` lists permissions normally | Must re-sync seeder when new keys added |
| **B.** `Gate::before(fn ($user, $ability) => $user->hasRole('super_admin') ? true : null)` | New permissions auto-work for super admin | `/me` must explicitly merge “all” for UI; Spatie role sync simpler |

**Recommendation:** **A** for Phase 2 — explicit, matches activity audit expectations, `/me` stays honest. Add new permission keys in one seeder class when Phases 3–5 add keys (idempotent `firstOrCreate`).

### 3.5 Guards

- Single guard: `web` (Spatie `guard_name = 'web'` on roles and permissions).
- API routes use Sanctum + session; no separate `api` guard for staff.

### 3.6 Assignment rules (business logic)

| Rule | Rationale |
| --- | --- |
| Staff get permissions **via role only** | Do not use `model_has_permissions` for normal users — keeps audit and UI simple. |
| **One role per user** (recommended) | Enovak SMB model; UI single select. DB still allows multiple via Spatie if you later relax — enforce in `UserStoreRequest` / service. |
| Cannot delete `super_admin` role | Hard-coded protect |
| Cannot remove `manage-roles` / `view-activity-log` from `super_admin` role | Hard-coded protect |
| When syncing permissions onto any non–`super_admin` role, **strip** `manage-roles` and `view-activity-log` | Matches requirements “only super_admin … manages roles … views global activity feed” |
| At least one active user with `super_admin` role | Block demote/deactivate/delete if last |
| User cannot deactivate themselves | Prevent lockout |
| Super admin may CRUD custom roles; rename optional | Slug/name unique per guard |

### 3.7 “Create permissions” vs catalog

Spatie supports creating permission rows at runtime. **Recommendation:** **fixed catalog** seeded from `requirements.md`; super admin **assigns** keys to roles, does not invent new keys in UI. New keys ship with phase migrations/seeders. Reason: stable React guards, policies, and docs across phases.

If you need ad-hoc abilities later, add a phase and a key — do not build a free-text permission creator in Phase 2.

---

## 4. Users (staff accounts)

### 4.1 Fields

See schema doc for columns. Phase 2 additions on `users`:

- `is_active` (default `true`)
- `phone`, `job_title` optional nullable strings

Keep: `name`, `email` unique, `password` hashed, timestamps. `email_verified_at` unused unless you add verification later.

### 4.2 User lifecycle

| Action | Who | Permission | Side effects |
| --- | --- | --- | --- |
| List / show users | Super admin or delegate | `manage-users` | — |
| Create user | Same | `manage-users` | Set password (see §4.3); assign one role; activity `user` |
| Update profile fields | Same | `manage-users` | Activity with dirty fields |
| Change role | Same | `manage-users` | Activity; cannot assign `super_admin` role unless actor is super admin (recommended: only super admin may create another super admin) |
| Deactivate | Same | `manage-users` | `is_active=false`; invalidate sessions optional (recommended: delete `sessions` rows for that `user_id`) |
| Delete | **Recommendation:** soft or disallow | — | Prefer **deactivate only** in Phase 2 to preserve audit FK integrity; hard delete complicates activity causer |

### 4.3 Password reset flow (Phase 2)

Phase spec mentions “password reset flow.” For **staff-only** admin with no public registration:

| Option | Description |
| --- | --- |
| **P1 — Admin sets password** | On create/reset, super admin (or `manage-users`) sets temporary password; user must change on first login (optional Phase 2.1 — can defer “must change” flag). |
| **P2 — Email reset link** | Reuse Laravel `password_reset_tokens`; admin UI “Send reset email” queues notification. |

**Recommendation:** **P1 for MVP slice 2.3** (simplest, no mail dependency for onboarding). Add **P2** as a follow-up slice if Enovak wants self-serve password reset without calling super admin. Table `password_reset_tokens` already exists.

---

## 5. Activity log (Spatie Activitylog)

### 5.1 Configuration

- `log_name` values (stable): `auth`, `user`, `role`, `system` — Phases 3–5 add `inquiry`, `invoice`, `content` without renaming existing names.
- User model: log fillable changes where useful (`is_active`, `name`, …) with `LogsActivity` trait; password never logged.
- Manual entries: login, logout, failed login, permission sync on role.

### 5.2 Minimum events (Phase 2)

| Event | log_name | causer | properties (JSON) |
| --- | --- | --- | --- |
| Login success | `auth` | user | `{ ip, user_agent }` |
| Login failed | `auth` | null | `{ email, ip, user_agent }` |
| Logout | `auth` | user | `{ ip }` |
| User created/updated/deactivated | `user` | actor | `{ target_user_id, … }` |
| Role created/updated/deleted | `role` | actor | `{ role_id, name }` |
| Permissions synced on role | `role` | actor | `{ role_id, permission_names[] }` |

### 5.3 Activity index API

- `GET /api/admin/activity` — permission `view-activity-log` + **actor has `super_admin` role** (defense in depth).
- Query: `user_id`, `log_name`, `from`, `to`, `search` (description), `page`.
- Response: paginated; include causer name, description, log_name, created_at, properties subset safe for UI.

Dashboard widget: same data, limited rows, only rendered if `/me` includes `view-activity-log`.

---

## 6. API surface (admin)

Prefix: `/api/admin`. Middleware stack: `auth:sanctum` → optional `permission:…` or policy.

| Method | Path | Access |
| --- | --- | --- |
| POST | `/login` | Public (throttled) |
| POST | `/logout` | Authenticated |
| GET | `/me` | Authenticated |
| GET/POST/PATCH/DELETE | `/users` | `manage-users` |
| GET/POST/PATCH/DELETE | `/roles` | `manage-roles` + super_admin role |
| GET | `/permissions` | `manage-roles` + super_admin role |
| PUT | `/roles/{id}/permissions` | `manage-roles` + super_admin role |
| GET | `/activity` | `view-activity-log` + super_admin role |

JSON shape per project convention: `{ data, message }` / validation `{ message, errors }`.

**Rate limiting:** login route — Laravel `throttle:login` or 5/min per IP+email.

---

## 7. React admin (structure & guards)

Matches phase spec tree under `resources/js/admin/`.

| Concern | Behavior |
| --- | --- |
| Boot | Load `/me` once; store permissions[] and role name |
| Route guard | No `/me` → redirect login; missing permission → 403 page |
| Nav | Hide Users without `manage-users`; Roles / Activity only if super_admin role (and permission) |
| Login page | Email/password; no remember me; show session expiry hint optional (“Sessions last up to 3 days of inactivity”) |

Build: separate Vite entry → `public/build/admin` (or project-standard path).

---

## 8. Cross-phase dependencies

| Phase | Needs from Phase 2 |
| --- | --- |
| 3 | Permissions seeded; roles assignable; activity `log_name` pattern |
| 4 | `view-own-invoices` vs `view-all-invoices` — enforce on invoice queries by `created_by_user_id` |
| 5 | CMS permission keys already in catalog |

When Phase 3 ships, add middleware to inquiry routes — no permission table rename.

---

## 9. Security checklist (Phase 2)

- [ ] HTTPS-only cookies in prod (`SESSION_SECURE_COOKIE=true`)
- [ ] `SameSite=lax` or `strict` per deployment
- [ ] Session driver `database` (already default in `site/config/session.php`) once MySQL live
- [ ] Invalidate sessions on password change and deactivate
- [ ] No permission escalation via role sync (strip super-admin-only permissions)
- [ ] Failed login logging without leaking which field failed

---

## 10. Implementation slices (unchanged intent)

Map directly to [p2-admin-auth-rbac.md](../phases/p2-admin-auth-rbac.md#implementation-slices):

1. **2.1** Sanctum + login/logout/me + React login  
2. **2.2** Spatie migrate + seed catalog + super_admin user/role  
3. **2.3** User CRUD + UI  
4. **2.4** Role CRUD + permission sync UI  
5. **2.5** Activity API + feed UI  
6. **2.6** Layout, nav, dashboard placeholders  

---

## 11. Decisions for you to confirm

| # | Topic | Recommendation |
| --- | --- | --- |
| 1 | Seed scope | Seed **permission catalog + super_admin role + one super_admin user**; **do not** seed `admin` role or staff. |
| 2 | New permission keys in UI | **No** — catalog-only; super admin assigns existing keys. |
| 3 | Session semantics | **3-day idle** via `SESSION_LIFETIME=4320`; no refresh token; no remember-me. OK? |
| 4 | One role per user | **Yes** — single role select in UI. |
| 5 | Password onboarding | **Admin-set password** on create for MVP; email reset later if needed. |
| 6 | User delete | **Deactivate only** in Phase 2 (no hard delete). |
| 7 | Second super admin | Only existing super admin can assign `super_admin` role to another user. |
| 8 | Super admin permission implementation | **Seed all permissions** on `super_admin` role (approach A). |
| 9 | Activity feed access | Require **`super_admin` role** plus `view-activity-log`, not permission alone on custom roles. |
| 10 | Dormant permissions in matrix | Show all seeded keys grouped by phase; allow pre-assignment for future modules. |

Reply with yes/no or tweaks on §11 before Phase 2.1 code starts — especially **#3** (idle vs absolute 3-day) and **#5** (password flow).

---

## 12. Doc drift to fix when implementing

- Update p2 seed bullet: remove seeded `admin` role; point to this analysis §3.2.
- Set `.env.example`: `SESSION_LIFETIME=4320`, super admin seeder vars, Sanctum stateful domains.

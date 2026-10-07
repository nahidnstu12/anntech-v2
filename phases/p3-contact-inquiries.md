# Phase 3 — Customer inquiry management

# Phase 3 — Contact inquiries

**Blueprint:** [docs/p3-contact-inquiries-analysis.md](../docs/p3-contact-inquiries-analysis.md)

**Depends on:** Phase 2 (auth + `view-contact-inquiries` / `manage-contact-inquiries`).  
**Goal:** Public contact form unchanged for visitors; every submission stored; email to Enovak still sent; staff work inquiries in admin.

---

## Public behavior (keep Phase 1 UX)

- `POST /contact` (web route, not API) — same fields: name, email, phone, message
- Validation: `ContactRequest` (unchanged rules)
- On success: redirect `/#contact` + flash; **also** persist DB row
- `Mail::to(company email)->queue(ContactEnquiry)` — keep queued

Optional: include inquiry `#id` in email subject for phone follow-up.

---

## Data model

**contact_inquiries**

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint PK | |
| name, email, phone, message | string/text | snapshot from form |
| status | enum/string | `new`, `in_progress`, `closed`, `spam` |
| assigned_to_user_id | FK users nullable | optional Phase 3.1 |
| internal_notes | text nullable | staff only |
| ip_address | string nullable | fraud/debug |
| user_agent | string nullable | |
| read_at | timestamp nullable | first opened in admin |
| created_at, updated_at | | |

Index: `status`, `created_at`, `email`.

---

## Permissions

| Permission | Allows |
| --- | --- |
| `view-contact-inquiries` | List + detail |
| `manage-contact-inquiries` | Status, notes, assign, mark spam |

Default: `admin` role gets both. Custom roles: grant view-only if needed.

---

## API (admin)

Prefix `/api/admin/contact-inquiries`. JSON resources hide internal notes from view-only if split later; for now both permissions see notes.

| Method | Path | Action |
| --- | --- | --- |
| GET | `/` | Paginated list; filters: `status`, `q` (name/email/phone), `from`, `to` |
| GET | `/{id}` | Detail; set `read_at` if null |
| PATCH | `/{id}` | `status`, `internal_notes`, `assigned_to_user_id` |

---

## Activity log

| Event | log_name | properties |
| --- | --- | --- |
| Inquiry received | inquiry | `{ inquiry_id }` (system/causer null) |
| Status changed | inquiry | `{ inquiry_id, from, to }` |
| Notes updated | inquiry | `{ inquiry_id }` |
| Assigned | inquiry | `{ inquiry_id, assignee_id }` |

Feed: super_admin filters `log_name=inquiry`.

---

## React admin UI

- **Inquiries** nav item (permission gated)
- List: status badge, date, name, email, phone excerpt
- Detail: full message, status select, notes textarea, timeline of activity (optional read from activity_log)

Dashboard widget (Phase 2 shell): count `new` inquiries.

---

## Implementation slices

| Slice | Deliverable |
| --- | --- |
| **3.1** | Migration + model + persist in `ContactController` inside DB transaction with mail queue |
| **3.2** | Admin list/detail API + policies |
| **3.3** | React inbox UI |
| **3.4** | Activity logging + dashboard count |

---

## Phase 3 done when

- [ ] Submitting form creates row + queues email (test with `Mail::fake`)
- [ ] Duplicate submit = two rows (expected); no dedupe unless product asks
- [ ] Staff without permission cannot list inquiries
- [ ] Status change appears in activity feed
- [ ] Public site does not expose inquiry IDs

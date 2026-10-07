# Phase 3 — Contact inquiries (analysis)

**Spec:** [phases/p3-contact-inquiries.md](../phases/p3-contact-inquiries.md)  
**Depends on:** Phase 2 permissions `view-contact-inquiries`, `manage-contact-inquiries`.

## Locked behavior

| Area | Decision |
| --- | --- |
| Public form | Unchanged UX; `POST /contact` persists row + queues mail in one DB transaction |
| Dedupe | None — each submit = new row |
| Email | Keep queued `ContactEnquiry`; subject includes `#id` for phone follow-up |
| Permissions | List/detail: either inquiry permission; PATCH: `manage-contact-inquiries` only |
| Notes | Both permission levels see `internal_notes` (per phase spec “for now”) |
| Assign | Column + API supported; **no assign UI** in Phase 3 (keep inbox simple) |
| Activity | `log_name=inquiry`; public submit logs with null causer |
| Dashboard | Count inquiries with `status=new` for users with view permission |

## Out of scope

- Public inquiry IDs, customer portal, email inbox in admin
- Timeline UI from activity_log (optional in spec — skipped)
- Phase 3.1 assign dropdown in React

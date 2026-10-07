# Phase 4 — Invoices & quotations (analysis)

**Spec:** [phases/p4-invoices.md](../phases/p4-invoices.md)  
**Depends on:** Phase 2 RBAC + activity log. Phase 3 optional.

## Billing ownership (flexible)

Two layers work together:

| Layer | Mechanism |
| --- | --- |
| **Permissions** | Spatie keys from `requirements.md` — who can create, edit, send, delete drafts, view all vs own |
| **Per-invoice assignee** | `billing_user_id` — staff member responsible for this quote/invoice (dropdown on draft) |

**View access**

- `view-all-invoices` → any invoice
- `view-own-invoices` → rows where `created_by_user_id = me` **or** `billing_user_id = me`
- List API requires at least one of: `view-all-invoices`, `view-own-invoices`, or `create-invoice` (so creators can open their drafts)

**Assign billing user**

- On create/update **draft**: actor with `edit-invoice` may set `billing_user_id` to any active staff user (defaults to creator)
- Super admin follows same rules via permissions
- After **sent**, `billing_user_id` is locked (audit); only status/notes per policy

**Roles:** Super admin creates roles (e.g. “Billing”) with `manage-invoice-clients`, `create-invoice`, `edit-invoice`, `send-invoice`, `view-own-invoices` or `view-all-invoices` as needed — no separate “billing role” seed.

## Numbering & totals

- Issue on **send**: transaction + `lockForUpdate` on `invoice_sequences` → `ENV-{YYYY}-{4-digit}` never reused
- Draft label: `DRAFT-{id}` until sent
- Line totals and header tax recalculated **server-side** on every save — client totals ignored

## Activity (`log_name=invoice`)

| Event | When |
| --- | --- |
| Invoice created | Draft saved first time |
| Invoice updated | Material draft edit (optional: only status/type/client change to reduce noise — log create, send, status) |
| Sent | Email queued + number assigned |
| Status changed | paid / overdue / cancelled |
| Draft deleted | Hard delete draft only |

Skip PDF download logging (spec: optional noisy).

## UI (simple, not over-built)

- **Clients** — table + inline modal form
- **Invoices** — filterable list; editor with client select, billing assignee, line rows, totals panel
- Actions: Save draft, Preview PDF (draft uses temp number on PDF), Send confirm, Mark paid / cancel on detail

## Out of scope (Phase 4)

- Payment gateway, customer portal, overdue cron (optional 4.5 — skip unless requested)
- Marketing showcase / `content.php` unchanged

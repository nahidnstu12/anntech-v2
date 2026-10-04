# Phase 4 — Invoice & quotation management

**Depends on:** Phase 2 (RBAC + activity log). Phase 3 optional (no hard dependency).

**Goal:** Create professional quotations/invoices inside admin. Auto numbering. PDF download. Email PDF to customer. Track status manually. Separate **invoice clients** from marketing showcase content.

No payment gateway. No customer portal.

---

## Concepts

- **Invoice client** — B2B customer record (company, contact person, email, phone, address, VAT/bin optional).
- **Invoice** — header + line items; types `quotation` | `invoice` (same engine; label/PDF title differs).
- **Numbering** — sequential, never reused: `ENV-{YYYY}-{4-digit}` e.g. `ENV-2026-0001`. Store `number` on issue (draft may use `DRAFT-{id}` until finalized).

---

## Data model

**invoice_clients**

| Column | Notes |
| --- | --- |
| company_name | required |
| contact_name | |
| email | required for send |
| phone, address | |
| notes | internal |
| created_by_user_id | FK |

**invoices**

| Column | Notes |
| --- | --- |
| invoice_client_id | FK |
| created_by_user_id | FK |
| number | unique nullable until issued |
| type | quotation \| invoice |
| status | draft, sent, paid, overdue, cancelled |
| issue_date, due_date | |
| currency | default BDT |
| subtotal, tax_rate, tax_amount, total | decimals; recalc on save |
| notes_public | on PDF |
| notes_internal | admin only |
| sent_at, paid_at | timestamps |
| pdf_path | storage path after first generate |

**invoice_line_items**

| Column | Notes |
| --- | --- |
| invoice_id | FK |
| sort_order | int |
| description | text |
| quantity | decimal |
| unit_price | decimal |
| line_total | decimal computed |

**invoice_sequences** (or row lock on single table)

- `year`, `last_number` — increment in transaction when assigning `number`.

---

## Status rules

| Status | Meaning | Transitions |
| --- | --- | --- |
| draft | Editable | → sent (assign number, generate PDF) |
| sent | Emailed to client | → paid, overdue, cancelled |
| paid | Manual mark | from sent/overdue |
| overdue | Manual or scheduled job (optional) | from sent when `due_date` passed |
| cancelled | Void | from draft/sent |

Overdue job: optional slice 4.5 — `Schedule::daily` marks sent + past due.

---

## Permissions

| Permission | Allows |
| --- | --- |
| `manage-invoice-clients` | CRUD clients |
| `create-invoice` | Create draft |
| `edit-invoice` | Edit draft; limited edit sent (policy: block if paid) |
| `send-invoice` | Send email + lock key fields |
| `delete-invoice-draft` | Delete draft only |
| `view-all-invoices` | See all |
| `view-own-invoices` | See `created_by_user_id = me` |

Policy: `view` if own OR `view-all-invoices`. `send` requires `send-invoice`.

---

## API sketch

`/api/admin/invoice-clients` — CRUD  
`/api/admin/invoices` — CRUD (create with nested line items)  
`POST /api/admin/invoices/{id}/send` — queue mail with PDF  
`GET /api/admin/invoices/{id}/pdf` — download/stream  
`PATCH /api/admin/invoices/{id}/status` — paid, overdue, cancelled  

Recalculate totals server-side on every save; never trust client totals.

---

## PDF & email

- Blade view `resources/views/pdf/invoice.blade.php` — Enovak branding, line table, totals, notes_public.
- DomPDF (or agreed lib) → store on `local` disk `invoices/{year}/{number}.pdf`.
- Mailable `InvoiceSent` implements `ShouldQueue`; attach PDF; log recipient.

Activity:

| Event | log_name | example description |
| --- | --- | --- |
| Invoice created | invoice | Created draft #… |
| Sent | invoice | Sent invoice ENV-2026-0001 to client@… |
| Status → paid | invoice | Marked paid ENV-2026-0001 |
| PDF downloaded | invoice | optional, noisy — skip unless audit requires |

---

## React admin UI

- Clients list/form
- Invoice builder: client select, line items dynamic rows, live preview totals
- List filters: status, client, date, “mine only”
- Actions: Save draft, Preview PDF, Send (confirm modal), Mark paid

---

## Implementation slices

| Slice | Deliverable |
| --- | --- |
| **4.1** | Clients CRUD |
| **4.2** | Invoice + line items CRUD + totals |
| **4.3** | Numbering + PDF generation |
| **4.4** | Send email + status sent |
| **4.5** | Status paid/overdue + policies + activity |
| **4.6** | React UI end-to-end |

---

## Phase 4 done when

- [ ] Number never duplicates under concurrent send (transaction + lock)
- [ ] User with only `view-own-invoices` cannot open others’ invoices
- [ ] Send appears in activity log with causer + client email
- [ ] PDF matches totals on screen
- [ ] Marketing `showcase` / `content.php` untouched

import { FormEvent, useEffect, useMemo, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { api } from '../../lib/api';
import { useAuth } from '../../lib/auth';
import {
    calcPreview,
    INVOICE_STATUS_LABELS,
    type InvoiceClientDto,
    type InvoiceDto,
    type InvoiceLine,
} from './types';

type StaffOption = { id: number; name: string; email: string };

const emptyLine = (): InvoiceLine => ({ description: '', quantity: '1', unit_price: '0' });

export function InvoiceEditorPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const { can, user } = useAuth();
    const isNew = !id || id === 'new';

    const [clients, setClients] = useState<InvoiceClientDto[]>([]);
    const [staff, setStaff] = useState<StaffOption[]>([]);
    const [invoice, setInvoice] = useState<InvoiceDto | null>(null);
    const [clientId, setClientId] = useState('');
    const [billingUserId, setBillingUserId] = useState('');
    const [type, setType] = useState<'quotation' | 'invoice'>('quotation');
    const [issueDate, setIssueDate] = useState('');
    const [dueDate, setDueDate] = useState('');
    const [taxRate, setTaxRate] = useState('0');
    const [notesPublic, setNotesPublic] = useState('');
    const [notesInternal, setNotesInternal] = useState('');
    const [lines, setLines] = useState<InvoiceLine[]>([emptyLine()]);
    const [error, setError] = useState<string | null>(null);
    const [saving, setSaving] = useState(false);

    const preview = useMemo(() => calcPreview(lines, taxRate), [lines, taxRate]);
    const editable = isNew || invoice?.status === 'draft';
    const canEdit = can('edit-invoice') || (isNew && can('create-invoice'));
    const canSend = can('send-invoice') && invoice?.status === 'draft';
    const canDelete = can('delete-invoice-draft') && invoice?.status === 'draft';
    const canAssignBilling = can('edit-invoice');

    useEffect(() => {
        void Promise.all([
            api<{ data: InvoiceClientDto[] }>('/invoice-clients?per_page=200'),
            can('create-invoice') || can('edit-invoice')
                ? api<{ data: StaffOption[] }>('/invoices/assignable-users')
                : Promise.resolve({ data: [] as StaffOption[] }),
        ])
            .then(([c, s]) => {
                setClients(c.data);
                setStaff(s.data);
            })
            .catch((e) => setError(e instanceof Error ? e.message : 'Load failed'));
    }, []);

    useEffect(() => {
        if (isNew) {
            setBillingUserId(String(user?.id ?? ''));
            return;
        }
        void api<{ data: InvoiceDto }>(`/invoices/${id}`)
            .then(({ data }) => {
                setInvoice(data);
                setClientId(String(data.invoice_client_id));
                setBillingUserId(String(data.billing_user_id));
                setType(data.type as 'quotation' | 'invoice');
                setIssueDate(data.issue_date ?? '');
                setDueDate(data.due_date ?? '');
                setTaxRate(String(data.tax_rate));
                setNotesPublic(data.notes_public ?? '');
                setNotesInternal(data.notes_internal ?? '');
                setLines(
                    data.line_items.length
                        ? data.line_items.map((l) => ({
                              description: l.description,
                              quantity: String(l.quantity),
                              unit_price: String(l.unit_price),
                          }))
                        : [emptyLine()],
                );
            })
            .catch((e) => setError(e instanceof Error ? e.message : 'Load failed'));
    }, [id, isNew, user?.id]);

    function payload() {
        return {
            invoice_client_id: Number(clientId),
            billing_user_id: Number(billingUserId),
            type,
            issue_date: issueDate || null,
            due_date: dueDate || null,
            tax_rate: taxRate,
            notes_public: notesPublic || null,
            notes_internal: notesInternal || null,
            line_items: lines.map((l) => ({
                description: l.description,
                quantity: l.quantity,
                unit_price: l.unit_price,
            })),
        };
    }

    async function saveDraft(e?: FormEvent) {
        e?.preventDefault();
        if (!canEdit || !editable) {
            return;
        }
        setSaving(true);
        setError(null);
        try {
            const body = JSON.stringify(payload());
            if (isNew) {
                const res = await api<{ data: InvoiceDto }>('/invoices', { method: 'POST', body });
                navigate(`/invoices/${res.data.id}`, { replace: true });
            } else {
                const res = await api<{ data: InvoiceDto }>(`/invoices/${id}`, { method: 'PATCH', body });
                setInvoice(res.data);
            }
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Save failed');
        } finally {
            setSaving(false);
        }
    }

    async function sendInvoice() {
        if (!invoice || !canSend) {
            return;
        }
        if (!confirm(`Send ${invoice.display_number} to client email?`)) {
            return;
        }
        try {
            const res = await api<{ data: InvoiceDto }>(`/invoices/${invoice.id}/send`, { method: 'POST' });
            setInvoice(res.data);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Send failed');
        }
    }

    async function markStatus(status: string) {
        if (!invoice) {
            return;
        }
        try {
            const res = await api<{ data: InvoiceDto }>(`/invoices/${invoice.id}/status`, {
                method: 'PATCH',
                body: JSON.stringify({ status }),
            });
            setInvoice(res.data);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Status update failed');
        }
    }

    async function removeDraft() {
        if (!invoice || !canDelete) {
            return;
        }
        if (!confirm('Delete this draft?')) {
            return;
        }
        await api(`/invoices/${invoice.id}`, { method: 'DELETE' });
        navigate('/invoices');
    }

    function openPdf() {
        if (!invoice) {
            return;
        }
        window.open(`/api/admin/invoices/${invoice.id}/pdf`, '_blank', 'noopener');
    }

    return (
        <div className="max-w-4xl">
            <div className="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <Link to="/invoices" className="text-sm text-sky-400 hover:underline">
                        ← Invoices
                    </Link>
                    <h1 className="mt-2 text-2xl font-semibold text-white">
                        {isNew ? 'New draft' : invoice?.display_number ?? 'Invoice'}
                    </h1>
                    {invoice && (
                        <p className="text-sm text-slate-400">
                            {INVOICE_STATUS_LABELS[invoice.status] ?? invoice.status}
                        </p>
                    )}
                </div>
                <div className="flex flex-wrap gap-2">
                    {invoice && (
                        <button type="button" onClick={openPdf} className="rounded-md border border-slate-600 px-3 py-2 text-sm">
                            PDF
                        </button>
                    )}
                    {canDelete && (
                        <button type="button" onClick={() => void removeDraft()} className="rounded-md border border-red-800 px-3 py-2 text-sm text-red-300">
                            Delete
                        </button>
                    )}
                    {canSend && (
                        <button type="button" onClick={() => void sendInvoice()} className="rounded-md bg-emerald-600 px-3 py-2 text-sm text-white">
                            Send
                        </button>
                    )}
                </div>
            </div>

            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}

            {!editable && invoice && (
                <div className="mt-4 flex flex-wrap gap-2">
                    {['paid', 'overdue', 'cancelled'].map((s) => (
                        <button
                            key={s}
                            type="button"
                            disabled={invoice.status === s}
                            onClick={() => void markStatus(s)}
                            className="rounded-md border border-slate-600 px-3 py-1 text-xs uppercase disabled:opacity-40"
                        >
                            Mark {s}
                        </button>
                    ))}
                </div>
            )}

            <form onSubmit={saveDraft} className="mt-6 space-y-6">
                <div className="grid gap-4 md:grid-cols-2">
                    <label className="block text-sm text-slate-400">
                        Client
                        <select
                            required
                            disabled={!editable}
                            value={clientId}
                            onChange={(e) => setClientId(e.target.value)}
                            className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm disabled:opacity-60"
                        >
                            <option value="">Select client</option>
                            {clients.map((c) => (
                                <option key={c.id} value={c.id}>
                                    {c.company_name}
                                </option>
                            ))}
                        </select>
                    </label>
                    <label className="block text-sm text-slate-400">
                        Billing assignee
                        <select
                            disabled={!editable || !canAssignBilling}
                            value={billingUserId}
                            onChange={(e) => setBillingUserId(e.target.value)}
                            className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm disabled:opacity-60"
                        >
                            {staff.map((s) => (
                                <option key={s.id} value={s.id}>
                                    {s.name}
                                </option>
                            ))}
                        </select>
                    </label>
                    <label className="block text-sm text-slate-400">
                        Type
                        <select
                            disabled={!editable}
                            value={type}
                            onChange={(e) => setType(e.target.value as 'quotation' | 'invoice')}
                            className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        >
                            <option value="quotation">Quotation</option>
                            <option value="invoice">Invoice</option>
                        </select>
                    </label>
                    <label className="block text-sm text-slate-400">
                        Tax rate (%)
                        <input
                            disabled={!editable}
                            value={taxRate}
                            onChange={(e) => setTaxRate(e.target.value)}
                            className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        />
                    </label>
                    <label className="block text-sm text-slate-400">
                        Issue date
                        <input
                            type="date"
                            disabled={!editable}
                            value={issueDate}
                            onChange={(e) => setIssueDate(e.target.value)}
                            className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        />
                    </label>
                    <label className="block text-sm text-slate-400">
                        Due date
                        <input
                            type="date"
                            disabled={!editable}
                            value={dueDate}
                            onChange={(e) => setDueDate(e.target.value)}
                            className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        />
                    </label>
                </div>

                <div>
                    <div className="flex items-center justify-between">
                        <h2 className="text-sm font-medium text-slate-300">Line items</h2>
                        {editable && (
                            <button
                                type="button"
                                onClick={() => setLines([...lines, emptyLine()])}
                                className="text-sm text-sky-400 hover:underline"
                            >
                                + Line
                            </button>
                        )}
                    </div>
                    <div className="mt-2 space-y-2">
                        {lines.map((line, idx) => (
                            <div key={idx} className="grid gap-2 rounded-md border border-slate-800 p-3 md:grid-cols-12">
                                <input
                                    required
                                    disabled={!editable}
                                    placeholder="Description"
                                    value={line.description}
                                    onChange={(e) => {
                                        const next = [...lines];
                                        next[idx] = { ...line, description: e.target.value };
                                        setLines(next);
                                    }}
                                    className="md:col-span-6 rounded-md border border-slate-700 bg-slate-950 px-2 py-2 text-sm"
                                />
                                <input
                                    disabled={!editable}
                                    value={line.quantity}
                                    onChange={(e) => {
                                        const next = [...lines];
                                        next[idx] = { ...line, quantity: e.target.value };
                                        setLines(next);
                                    }}
                                    className="md:col-span-2 rounded-md border border-slate-700 bg-slate-950 px-2 py-2 text-sm"
                                />
                                <input
                                    disabled={!editable}
                                    value={line.unit_price}
                                    onChange={(e) => {
                                        const next = [...lines];
                                        next[idx] = { ...line, unit_price: e.target.value };
                                        setLines(next);
                                    }}
                                    className="md:col-span-3 rounded-md border border-slate-700 bg-slate-950 px-2 py-2 text-sm"
                                />
                                {editable && lines.length > 1 && (
                                    <button
                                        type="button"
                                        onClick={() => setLines(lines.filter((_, i) => i !== idx))}
                                        className="text-sm text-red-400"
                                    >
                                        ×
                                    </button>
                                )}
                            </div>
                        ))}
                    </div>
                </div>

                <div className="rounded-lg border border-slate-800 bg-slate-900/40 p-4 text-sm">
                    <p className="flex justify-between text-slate-400">
                        <span>Subtotal</span>
                        <span>{preview.subtotal.toFixed(2)}</span>
                    </p>
                    <p className="mt-1 flex justify-between text-slate-400">
                        <span>Tax</span>
                        <span>{preview.tax.toFixed(2)}</span>
                    </p>
                    <p className="mt-2 flex justify-between text-lg font-semibold text-white">
                        <span>Total</span>
                        <span>{preview.total.toFixed(2)}</span>
                    </p>
                </div>

                <label className="block text-sm text-slate-400">
                    Notes (on PDF)
                    <textarea
                        disabled={!editable}
                        rows={2}
                        value={notesPublic}
                        onChange={(e) => setNotesPublic(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                </label>
                <label className="block text-sm text-slate-400">
                    Internal notes
                    <textarea
                        disabled={!editable}
                        rows={2}
                        value={notesInternal}
                        onChange={(e) => setNotesInternal(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                </label>

                {editable && canEdit && (
                    <button
                        type="submit"
                        disabled={saving}
                        className="rounded-md bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-500 disabled:opacity-50"
                    >
                        {saving ? 'Saving…' : 'Save draft'}
                    </button>
                )}
            </form>
        </div>
    );
}

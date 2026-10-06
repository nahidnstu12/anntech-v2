import { FormEvent, useEffect, useState } from 'react';
import { api } from '../../lib/api';
import { useAuth } from '../../lib/auth';
import type { InvoiceClientDto } from './types';

export function InvoiceClientsPage() {
    const { can } = useAuth();
    const canManage = can('manage-invoice-clients');
    const [clients, setClients] = useState<InvoiceClientDto[]>([]);
    const [form, setForm] = useState({
        company_name: '',
        contact_name: '',
        email: '',
        phone: '',
        address: '',
    });
    const [editId, setEditId] = useState<number | null>(null);
    const [error, setError] = useState<string | null>(null);

    async function load() {
        const json = await api<{ data: InvoiceClientDto[] }>('/invoice-clients?per_page=100');
        setClients(json.data);
    }

    useEffect(() => {
        void load().catch((e) => setError(e instanceof Error ? e.message : 'Load failed'));
    }, []);

    async function onSubmit(e: FormEvent) {
        e.preventDefault();
        if (!canManage) {
            return;
        }
        try {
            if (editId) {
                await api(`/invoice-clients/${editId}`, { method: 'PATCH', body: JSON.stringify(form) });
            } else {
                await api('/invoice-clients', { method: 'POST', body: JSON.stringify(form) });
            }
            setForm({ company_name: '', contact_name: '', email: '', phone: '', address: '' });
            setEditId(null);
            await load();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Save failed');
        }
    }

    return (
        <div>
            <h1 className="text-2xl font-semibold text-white">Invoice clients</h1>
            <p className="mt-1 text-sm text-slate-400">B2B customers for quotations and invoices.</p>
            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}

            {canManage && (
                <form onSubmit={onSubmit} className="mt-6 grid gap-3 rounded-lg border border-slate-800 p-4 md:grid-cols-2">
                    <input
                        required
                        placeholder="Company name"
                        value={form.company_name}
                        onChange={(e) => setForm({ ...form, company_name: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                    <input
                        placeholder="Contact name"
                        value={form.contact_name}
                        onChange={(e) => setForm({ ...form, contact_name: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                    <input
                        required
                        type="email"
                        placeholder="Email"
                        value={form.email}
                        onChange={(e) => setForm({ ...form, email: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                    <input
                        placeholder="Phone"
                        value={form.phone}
                        onChange={(e) => setForm({ ...form, phone: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                    <textarea
                        placeholder="Address"
                        value={form.address}
                        onChange={(e) => setForm({ ...form, address: e.target.value })}
                        className="md:col-span-2 rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        rows={2}
                    />
                    <div className="md:col-span-2 flex gap-2">
                        <button type="submit" className="rounded-md bg-sky-600 px-4 py-2 text-sm text-white">
                            {editId ? 'Update client' : 'Add client'}
                        </button>
                        {editId && (
                            <button
                                type="button"
                                className="rounded-md border border-slate-600 px-4 py-2 text-sm"
                                onClick={() => {
                                    setEditId(null);
                                    setForm({ company_name: '', contact_name: '', email: '', phone: '', address: '' });
                                }}
                            >
                                Cancel
                            </button>
                        )}
                    </div>
                </form>
            )}

            <div className="mt-8 overflow-x-auto rounded-lg border border-slate-800">
                <table className="min-w-full text-left text-sm">
                    <thead className="bg-slate-900 text-slate-400">
                        <tr>
                            <th className="px-4 py-3">Company</th>
                            <th className="px-4 py-3">Contact</th>
                            <th className="px-4 py-3">Email</th>
                            <th className="px-4 py-3" />
                        </tr>
                    </thead>
                    <tbody>
                        {clients.map((c) => (
                            <tr key={c.id} className="border-t border-slate-800">
                                <td className="px-4 py-3 text-white">{c.company_name}</td>
                                <td className="px-4 py-3">{c.contact_name ?? '—'}</td>
                                <td className="px-4 py-3">{c.email}</td>
                                <td className="px-4 py-3 text-right">
                                    {canManage && (
                                        <button
                                            type="button"
                                            className="text-sky-400 hover:underline"
                                            onClick={() => {
                                                setEditId(c.id);
                                                setForm({
                                                    company_name: c.company_name,
                                                    contact_name: c.contact_name ?? '',
                                                    email: c.email,
                                                    phone: c.phone ?? '',
                                                    address: c.address ?? '',
                                                });
                                            }}
                                        >
                                            Edit
                                        </button>
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

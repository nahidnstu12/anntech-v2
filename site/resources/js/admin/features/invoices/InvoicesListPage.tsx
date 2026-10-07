import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../../lib/api';
import { useAuth } from '../../lib/auth';
import { INVOICE_STATUS_LABELS, type InvoiceDto } from './types';

type Paginated = { data: InvoiceDto[] };

export function InvoicesListPage() {
    const { can } = useAuth();
    const [rows, setRows] = useState<InvoiceDto[]>([]);
    const [status, setStatus] = useState('');
    const [mine, setMine] = useState(false);
    const [error, setError] = useState<string | null>(null);

    async function load() {
        const params = new URLSearchParams();
        if (status) {
            params.set('status', status);
        }
        if (mine && can('view-all-invoices')) {
            params.set('mine', '1');
        }
        const json = await api<Paginated>(`/invoices?${params.toString()}`);
        setRows(json.data);
    }

    useEffect(() => {
        void load().catch((e) => setError(e instanceof Error ? e.message : 'Load failed'));
    }, [status, mine]);

    return (
        <div>
            <div className="flex flex-wrap items-center justify-between gap-3">
                <h1 className="text-2xl font-semibold text-white">Invoices</h1>
                {can('create-invoice') && (
                    <Link to="/invoices/new" className="rounded-md bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-500">
                        New draft
                    </Link>
                )}
            </div>

            <div className="mt-4 flex flex-wrap gap-3">
                <select
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                    className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                >
                    <option value="">All statuses</option>
                    {Object.entries(INVOICE_STATUS_LABELS).map(([v, l]) => (
                        <option key={v} value={v}>
                            {l}
                        </option>
                    ))}
                </select>
                {can('view-all-invoices') && (
                    <label className="flex items-center gap-2 text-sm text-slate-300">
                        <input type="checkbox" checked={mine} onChange={(e) => setMine(e.target.checked)} />
                        Mine only
                    </label>
                )}
            </div>

            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}

            <div className="mt-6 overflow-x-auto rounded-lg border border-slate-800">
                <table className="min-w-full text-left text-sm">
                    <thead className="bg-slate-900 text-slate-400">
                        <tr>
                            <th className="px-4 py-3">Number</th>
                            <th className="px-4 py-3">Client</th>
                            <th className="px-4 py-3">Billing</th>
                            <th className="px-4 py-3">Status</th>
                            <th className="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {rows.map((row) => (
                            <tr key={row.id} className="border-t border-slate-800 hover:bg-slate-900/50">
                                <td className="px-4 py-3">
                                    <Link to={`/invoices/${row.id}`} className="text-sky-400 hover:underline">
                                        {row.display_number}
                                    </Link>
                                    <span className="ml-2 text-xs uppercase text-slate-500">{row.type}</span>
                                </td>
                                <td className="px-4 py-3">{row.client?.company_name ?? '—'}</td>
                                <td className="px-4 py-3 text-slate-400">{row.billing_user?.name ?? '—'}</td>
                                <td className="px-4 py-3">{INVOICE_STATUS_LABELS[row.status] ?? row.status}</td>
                                <td className="px-4 py-3">
                                    {row.currency} {row.total}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

import { FormEvent, useEffect, useState } from 'react';
import { Link, useNavigate, useParams, useSearchParams } from 'react-router-dom';
import { api } from '../../lib/api';
import { useAuth } from '../../lib/auth';

export type ContactInquiry = {
    id: number;
    name: string;
    email: string;
    phone: string;
    message: string;
    status: string;
    internal_notes: string | null;
    read_at: string | null;
    created_at: string;
    message_excerpt?: string;
};

type Paginated<T> = { data: T[] };

const STATUS_LABELS: Record<string, string> = {
    new: 'New',
    in_progress: 'In progress',
    closed: 'Closed',
    spam: 'Spam',
};

function StatusBadge({ status }: { status: string }) {
    const colors: Record<string, string> = {
        new: 'bg-sky-500/20 text-sky-200',
        in_progress: 'bg-amber-500/20 text-amber-200',
        closed: 'bg-emerald-500/20 text-emerald-200',
        spam: 'bg-slate-600 text-slate-300',
    };
    return (
        <span className={`rounded-full px-2 py-0.5 text-xs font-medium ${colors[status] ?? 'bg-slate-700'}`}>
            {STATUS_LABELS[status] ?? status}
        </span>
    );
}

export function InquiriesPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const { can } = useAuth();
    const canManage = can('manage-contact-inquiries');

    const [items, setItems] = useState<ContactInquiry[]>([]);
    const [detail, setDetail] = useState<ContactInquiry | null>(null);
    const [statusFilter, setStatusFilter] = useState('');
    const [searchParams] = useSearchParams();

    useEffect(() => {
        const initial = searchParams.get('status') ?? '';
        if (initial) {
            setStatusFilter(initial);
        }
    }, [searchParams]);
    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [saving, setSaving] = useState(false);

    const [editStatus, setEditStatus] = useState('new');
    const [editNotes, setEditNotes] = useState('');

    async function loadList() {
        const params = new URLSearchParams();
        if (statusFilter) {
            params.set('status', statusFilter);
        }
        if (search.trim()) {
            params.set('q', search.trim());
        }
        const json = await api<Paginated<ContactInquiry>>(`/contact-inquiries?${params.toString()}`);
        setItems(json.data);
    }

    async function loadDetail(inquiryId: string) {
        const json = await api<{ data: ContactInquiry }>(`/contact-inquiries/${inquiryId}`);
        setDetail(json.data);
        setEditStatus(json.data.status);
        setEditNotes(json.data.internal_notes ?? '');
    }

    useEffect(() => {
        setLoading(true);
        setError(null);
        void loadList()
            .catch((err) => setError(err instanceof Error ? err.message : 'Failed to load'))
            .finally(() => setLoading(false));
    }, [statusFilter]);

    useEffect(() => {
        if (!id) {
            setDetail(null);
            return;
        }
        void loadDetail(id).catch((err) => setError(err instanceof Error ? err.message : 'Failed to load'));
    }, [id]);

    async function onSearch(e: FormEvent) {
        e.preventDefault();
        setLoading(true);
        try {
            await loadList();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Search failed');
        } finally {
            setLoading(false);
        }
    }

    async function saveDetail(e: FormEvent) {
        e.preventDefault();
        if (!detail || !canManage) {
            return;
        }
        setSaving(true);
        setError(null);
        try {
            const json = await api<{ data: ContactInquiry }>(`/contact-inquiries/${detail.id}`, {
                method: 'PATCH',
                body: JSON.stringify({
                    status: editStatus,
                    internal_notes: editNotes,
                }),
            });
            setDetail(json.data);
            await loadList();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Save failed');
        } finally {
            setSaving(false);
        }
    }

    return (
        <div>
            <div className="flex flex-wrap items-center justify-between gap-3">
                <h1 className="text-2xl font-semibold text-white">Inquiries</h1>
                {id && (
                    <button
                        type="button"
                        onClick={() => navigate('/inquiries')}
                        className="text-sm text-sky-400 hover:underline"
                    >
                        ← Back to list
                    </button>
                )}
            </div>

            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}

            {!id && (
                <>
                    <form onSubmit={onSearch} className="mt-4 flex flex-wrap gap-2">
                        <select
                            value={statusFilter}
                            onChange={(e) => setStatusFilter(e.target.value)}
                            className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        >
                            <option value="">All statuses</option>
                            {Object.entries(STATUS_LABELS).map(([value, label]) => (
                                <option key={value} value={value}>
                                    {label}
                                </option>
                            ))}
                        </select>
                        <input
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Search name, email, phone"
                            className="min-w-[200px] flex-1 rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                        />
                        <button type="submit" className="rounded-md bg-slate-700 px-4 py-2 text-sm text-white">
                            Search
                        </button>
                    </form>

                    {loading ? (
                        <p className="mt-6 text-slate-400">Loading…</p>
                    ) : (
                        <ul className="mt-6 divide-y divide-slate-800 rounded-lg border border-slate-800">
                            {items.length === 0 && (
                                <li className="p-6 text-center text-sm text-slate-500">No inquiries found.</li>
                            )}
                            {items.map((row) => (
                                <li key={row.id}>
                                    <Link
                                        to={`/inquiries/${row.id}`}
                                        className="flex flex-wrap items-center justify-between gap-3 px-4 py-4 hover:bg-slate-900/60"
                                    >
                                        <div>
                                            <p className="font-medium text-white">
                                                {row.name}
                                                {!row.read_at && (
                                                    <span className="ml-2 text-xs text-sky-400">Unread</span>
                                                )}
                                            </p>
                                            <p className="text-sm text-slate-400">
                                                {row.email} · {row.phone}
                                            </p>
                                            <p className="mt-1 text-sm text-slate-500">{row.message_excerpt ?? row.message}</p>
                                        </div>
                                        <div className="text-right">
                                            <StatusBadge status={row.status} />
                                            <p className="mt-2 text-xs text-slate-500">
                                                {new Date(row.created_at).toLocaleString()}
                                            </p>
                                        </div>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </>
            )}

            {id && detail && (
                <div className="mt-6 max-w-3xl space-y-6">
                    <div className="rounded-lg border border-slate-800 bg-slate-900/40 p-5">
                        <div className="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h2 className="text-lg font-semibold text-white">{detail.name}</h2>
                                <p className="mt-1 text-sm text-slate-400">
                                    <a href={`mailto:${detail.email}`} className="text-sky-400 hover:underline">
                                        {detail.email}
                                    </a>{' '}
                                    · {detail.phone}
                                </p>
                                <p className="mt-1 text-xs text-slate-500">
                                    Received {new Date(detail.created_at).toLocaleString()}
                                </p>
                            </div>
                            <StatusBadge status={detail.status} />
                        </div>
                        <p className="mt-4 whitespace-pre-wrap text-sm leading-relaxed text-slate-200">{detail.message}</p>
                    </div>

                    <form onSubmit={saveDetail} className="rounded-lg border border-slate-800 p-5">
                        <h3 className="text-sm font-medium text-slate-300">Staff</h3>
                        {!canManage && (
                            <p className="mt-2 text-sm text-slate-500">View only — you cannot edit status or notes.</p>
                        )}
                        <label className="mt-4 block text-sm text-slate-400">
                            Status
                            <select
                                disabled={!canManage}
                                value={editStatus}
                                onChange={(e) => setEditStatus(e.target.value)}
                                className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm disabled:opacity-60"
                            >
                                {Object.entries(STATUS_LABELS).map(([value, label]) => (
                                    <option key={value} value={value}>
                                        {label}
                                    </option>
                                ))}
                            </select>
                        </label>
                        <label className="mt-4 block text-sm text-slate-400">
                            Internal notes
                            <textarea
                                disabled={!canManage}
                                rows={4}
                                value={editNotes}
                                onChange={(e) => setEditNotes(e.target.value)}
                                className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm disabled:opacity-60"
                                placeholder="Follow-up details for the team…"
                            />
                        </label>
                        {canManage && (
                            <button
                                type="submit"
                                disabled={saving}
                                className="mt-4 rounded-md bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-500 disabled:opacity-50"
                            >
                                {saving ? 'Saving…' : 'Save changes'}
                            </button>
                        )}
                    </form>
                </div>
            )}
        </div>
    );
}

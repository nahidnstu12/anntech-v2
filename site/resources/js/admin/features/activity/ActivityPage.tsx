import { useEffect, useState } from 'react';
import { ActivityRow, api } from '../../lib/api';

type Paginated = { data: ActivityRow[] };

export function ActivityPage() {
    const [rows, setRows] = useState<ActivityRow[]>([]);
    const [logName, setLogName] = useState('');
    const [search, setSearch] = useState('');
    const [error, setError] = useState<string | null>(null);

    async function load() {
        const params = new URLSearchParams();
        if (logName) {
            params.set('log_name', logName);
        }
        if (search) {
            params.set('search', search);
        }
        const json = await api<Paginated>(`/activity?${params.toString()}`);
        setRows(json.data);
    }

    useEffect(() => {
        void load().catch((err) => setError(err instanceof Error ? err.message : 'Load failed'));
    }, []);

    return (
        <div>
            <h1 className="text-2xl font-semibold text-white">Activity log</h1>
            <div className="mt-4 flex flex-wrap gap-2">
                <select
                    value={logName}
                    onChange={(e) => setLogName(e.target.value)}
                    className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                >
                    <option value="">All types</option>
                    <option value="auth">auth</option>
                    <option value="user">user</option>
                    <option value="role">role</option>
                    <option value="inquiry">inquiry</option>
                </select>
                <input
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="Search description"
                    className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                />
                <button
                    type="button"
                    onClick={() => void load()}
                    className="rounded-md bg-sky-600 px-4 py-2 text-sm text-white"
                >
                    Filter
                </button>
            </div>
            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}
            <ul className="mt-6 space-y-3">
                {rows.map((row) => (
                    <li key={row.id} className="rounded-lg border border-slate-800 bg-slate-900/40 p-4">
                        <div className="flex flex-wrap items-center justify-between gap-2 text-sm">
                            <span className="rounded bg-slate-800 px-2 py-0.5 text-xs uppercase text-slate-300">
                                {row.log_name}
                            </span>
                            <time className="text-slate-500">{new Date(row.created_at).toLocaleString()}</time>
                        </div>
                        <p className="mt-2 text-white">{row.description}</p>
                        <p className="mt-1 text-xs text-slate-500">
                            {row.causer ? `By ${row.causer.name}` : 'System'}
                        </p>
                    </li>
                ))}
            </ul>
        </div>
    );
}

import { useEffect, useState } from 'react';
import { api, ErrorLogDetail, ErrorLogRow } from '../../lib/api';

type Paginated = { data: ErrorLogRow[] };

export function ErrorLogsPage() {
    const [rows, setRows] = useState<ErrorLogRow[]>([]);
    const [search, setSearch] = useState('');
    const [requestId, setRequestId] = useState('');
    const [error, setError] = useState<string | null>(null);
    const [detail, setDetail] = useState<ErrorLogDetail | null>(null);
    const [detailLoading, setDetailLoading] = useState(false);

    async function load() {
        const params = new URLSearchParams();
        if (search) {
            params.set('search', search);
        }
        if (requestId) {
            params.set('request_id', requestId);
        }
        const json = await api<Paginated>(`/error-logs?${params.toString()}`);
        setRows(json.data);
    }

    async function openDetail(id: number) {
        setDetailLoading(true);
        setDetail(null);
        try {
            const json = await api<{ data: ErrorLogDetail }>(`/error-logs/${id}`);
            setDetail(json.data);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Load failed');
        } finally {
            setDetailLoading(false);
        }
    }

    useEffect(() => {
        void load().catch((err) => setError(err instanceof Error ? err.message : 'Load failed'));
    }, []);

    return (
        <div>
            <h1 className="text-2xl font-semibold text-white">Error log</h1>
            <p className="mt-1 text-sm text-slate-400">
                Server errors with request id, stack trace, and request context (passwords redacted).
            </p>
            <div className="mt-4 flex flex-wrap gap-2">
                <input
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="Search message or URL"
                    className="min-w-[12rem] flex-1 rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                />
                <input
                    value={requestId}
                    onChange={(e) => setRequestId(e.target.value)}
                    placeholder="Request ID"
                    className="w-72 rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm font-mono text-xs"
                />
                <button
                    type="button"
                    onClick={() => void load().catch((err) => setError(err instanceof Error ? err.message : 'Load failed'))}
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
                            <span className="font-mono text-xs text-amber-300/90">
                                {row.status_code ?? '—'} · {row.exception_class.split('\\').pop()}
                            </span>
                            <time className="text-slate-500">{new Date(row.created_at).toLocaleString()}</time>
                        </div>
                        <p className="mt-2 text-white">{row.message}</p>
                        <p className="mt-1 truncate text-xs text-slate-500">{row.url}</p>
                        <div className="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                            {row.request_id && (
                                <span className="font-mono">ID {row.request_id}</span>
                            )}
                            {row.user && <span>{row.user.name}</span>}
                            <button
                                type="button"
                                onClick={() => void openDetail(row.id)}
                                className="text-sky-400 hover:underline"
                            >
                                Trace
                            </button>
                        </div>
                    </li>
                ))}
            </ul>
            {(detailLoading || detail) && (
                <div
                    className="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/70 p-4 pt-12"
                    role="dialog"
                    onClick={() => setDetail(null)}
                >
                    <div
                        className="max-h-[85vh] w-full max-w-3xl overflow-y-auto rounded-lg border border-slate-700 bg-slate-900 p-6 shadow-xl"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {detailLoading && <p className="text-slate-400">Loading…</p>}
                        {detail && (
                            <>
                                <div className="flex items-start justify-between gap-4">
                                    <h2 className="text-lg font-semibold text-white">Error trace</h2>
                                    <button
                                        type="button"
                                        onClick={() => setDetail(null)}
                                        className="text-sm text-slate-400 hover:text-white"
                                    >
                                        Close
                                    </button>
                                </div>
                                <dl className="mt-4 space-y-2 text-sm">
                                    <div>
                                        <dt className="text-slate-500">Request ID</dt>
                                        <dd className="font-mono text-sky-300">{detail.request_id ?? '—'}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-slate-500">Exception</dt>
                                        <dd className="font-mono text-xs text-slate-200">{detail.exception_class}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-slate-500">Location</dt>
                                        <dd className="font-mono text-xs text-slate-300">
                                            {detail.file}:{detail.line}
                                        </dd>
                                    </div>
                                </dl>
                                <pre className="mt-4 max-h-64 overflow-auto rounded bg-slate-950 p-3 text-xs text-slate-300 whitespace-pre-wrap">
                                    {detail.stack_trace ?? 'No stack trace'}
                                </pre>
                                {detail.context && (
                                    <pre className="mt-4 max-h-48 overflow-auto rounded bg-slate-950 p-3 text-xs text-slate-400">
                                        {JSON.stringify(detail.context, null, 2)}
                                    </pre>
                                )}
                            </>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}

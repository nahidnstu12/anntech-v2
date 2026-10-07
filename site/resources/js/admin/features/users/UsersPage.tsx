import { FormEvent, useEffect, useState } from 'react';
import { AdminRole, AdminUser, api } from '../../lib/api';

type Paginated<T> = {
    data: T[];
    meta?: { current_page: number; last_page: number };
};

export function UsersPage() {
    const [users, setUsers] = useState<AdminUser[]>([]);
    const [roles, setRoles] = useState<AdminRole[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [showForm, setShowForm] = useState(false);
    const [form, setForm] = useState({
        name: '',
        email: '',
        password: '',
        role_id: '',
        phone: '',
        job_title: '',
    });

    async function load() {
        setLoading(true);
        try {
            const [usersRes, rolesRes] = await Promise.all([
                api<Paginated<AdminUser>>('/users'),
                api<{ data: { id: number; name: string }[] }>('/roles/options'),
            ]);
            setUsers(usersRes.data);
            setRoles(
                rolesRes.data.map((r) => ({
                    id: r.id,
                    name: r.name,
                    is_system: false,
                    permissions: [],
                })),
            );
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to load users.');
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        void load();
    }, []);

    async function onCreate(e: FormEvent) {
        e.preventDefault();
        setError(null);
        try {
            await api('/users', {
                method: 'POST',
                body: JSON.stringify({
                    ...form,
                    role_id: Number(form.role_id),
                }),
            });
            setShowForm(false);
            setForm({ name: '', email: '', password: '', role_id: '', phone: '', job_title: '' });
            await load();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Create failed.');
        }
    }

    async function toggleActive(user: AdminUser) {
        if (!confirm(`${user.is_active ? 'Deactivate' : 'Activate'} ${user.name}?`)) {
            return;
        }
        try {
            await api(`/users/${user.id}`, {
                method: 'PATCH',
                body: JSON.stringify({ is_active: !user.is_active }),
            });
            await load();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Update failed.');
        }
    }

    if (loading) {
        return <p className="text-slate-400">Loading users…</p>;
    }

    return (
        <div>
            <div className="flex items-center justify-between">
                <h1 className="text-2xl font-semibold text-white">Users</h1>
                <button
                    type="button"
                    onClick={() => setShowForm((v) => !v)}
                    className="rounded-md bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-500"
                >
                    {showForm ? 'Cancel' : 'New user'}
                </button>
            </div>
            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}
            {showForm && (
                <form onSubmit={onCreate} className="mt-6 grid gap-3 rounded-lg border border-slate-800 p-4 md:grid-cols-2">
                    <input
                        placeholder="Name"
                        required
                        value={form.name}
                        onChange={(e) => setForm({ ...form, name: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    />
                    <input
                        placeholder="Email"
                        type="email"
                        required
                        value={form.email}
                        onChange={(e) => setForm({ ...form, email: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    />
                    <input
                        placeholder="Password"
                        type="password"
                        required
                        value={form.password}
                        onChange={(e) => setForm({ ...form, password: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    />
                    <select
                        required
                        value={form.role_id}
                        onChange={(e) => setForm({ ...form, role_id: e.target.value })}
                        className="rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    >
                        <option value="">Select role</option>
                        {roles.map((r) => (
                            <option key={r.id} value={r.id}>
                                {r.name}
                            </option>
                        ))}
                    </select>
                    <button type="submit" className="md:col-span-2 rounded-md bg-emerald-600 py-2 text-white">
                        Create user
                    </button>
                </form>
            )}
            <div className="mt-6 overflow-x-auto rounded-lg border border-slate-800">
                <table className="min-w-full text-left text-sm">
                    <thead className="bg-slate-900 text-slate-400">
                        <tr>
                            <th className="px-4 py-3">Name</th>
                            <th className="px-4 py-3">Email</th>
                            <th className="px-4 py-3">Role</th>
                            <th className="px-4 py-3">Status</th>
                            <th className="px-4 py-3" />
                        </tr>
                    </thead>
                    <tbody>
                        {users.map((u) => (
                            <tr key={u.id} className="border-t border-slate-800">
                                <td className="px-4 py-3 text-white">{u.name}</td>
                                <td className="px-4 py-3">{u.email}</td>
                                <td className="px-4 py-3">{u.role?.name ?? '—'}</td>
                                <td className="px-4 py-3">{u.is_active ? 'Active' : 'Inactive'}</td>
                                <td className="px-4 py-3 text-right">
                                    {!u.is_super_admin && (
                                        <button
                                            type="button"
                                            onClick={() => void toggleActive(u)}
                                            className="text-sky-400 hover:underline"
                                        >
                                            {u.is_active ? 'Deactivate' : 'Activate'}
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

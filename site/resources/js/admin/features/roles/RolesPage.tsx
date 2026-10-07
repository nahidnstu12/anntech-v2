import { FormEvent, useEffect, useState } from 'react';
import { AdminRole, api } from '../../lib/api';

type PermissionPayload = {
    data: {
        catalog: string[];
        groups: Record<string, string[]>;
        super_admin_only: string[];
    };
};

export function RolesPage() {
    const [roles, setRoles] = useState<AdminRole[]>([]);
    const [groups, setGroups] = useState<Record<string, string[]>>({});
    const [superAdminOnly, setSuperAdminOnly] = useState<string[]>([]);
    const [selectedId, setSelectedId] = useState<number | null>(null);
    const [newName, setNewName] = useState('');
    const [checked, setChecked] = useState<string[]>([]);
    const [error, setError] = useState<string | null>(null);

    const selected = roles.find((r) => r.id === selectedId) ?? null;

    async function load() {
        const [rolesRes, permRes] = await Promise.all([
            api<{ data: AdminRole[] }>('/roles'),
            api<PermissionPayload>('/permissions'),
        ]);
        setRoles(rolesRes.data.filter((r) => !r.is_system));
        setGroups(permRes.data.groups);
        setSuperAdminOnly(permRes.data.super_admin_only);
    }

    useEffect(() => {
        void load().catch((err) => setError(err instanceof Error ? err.message : 'Load failed'));
    }, []);

    useEffect(() => {
        if (selected) {
            setChecked(selected.permissions);
        }
    }, [selected]);

    async function createRole(e: FormEvent) {
        e.preventDefault();
        try {
            await api('/roles', { method: 'POST', body: JSON.stringify({ name: newName }) });
            setNewName('');
            await load();
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Create failed');
        }
    }

    async function savePermissions() {
        if (!selected) {
            return;
        }
        try {
            await api(`/roles/${selected.id}/permissions`, {
                method: 'PUT',
                body: JSON.stringify({ permissions: checked }),
            });
            await load();
            setError(null);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Save failed');
        }
    }

    function togglePermission(name: string) {
        if (superAdminOnly.includes(name)) {
            return;
        }
        setChecked((prev) =>
            prev.includes(name) ? prev.filter((p) => p !== name) : [...prev, name],
        );
    }

    return (
        <div className="grid gap-8 lg:grid-cols-[240px,1fr]">
            <div>
                <h1 className="text-2xl font-semibold text-white">Roles</h1>
                <form onSubmit={createRole} className="mt-4 flex gap-2">
                    <input
                        value={newName}
                        onChange={(e) => setNewName(e.target.value)}
                        placeholder="New role name"
                        required
                        className="flex-1 rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm"
                    />
                    <button type="submit" className="rounded-md bg-sky-600 px-3 py-2 text-sm text-white">
                        Add
                    </button>
                </form>
                <ul className="mt-4 space-y-1">
                    {roles.map((r) => (
                        <li key={r.id}>
                            <button
                                type="button"
                                onClick={() => setSelectedId(r.id)}
                                className={`w-full rounded-md px-3 py-2 text-left text-sm ${
                                    selectedId === r.id ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/50'
                                }`}
                            >
                                {r.name}
                                <span className="ml-2 text-xs text-slate-500">({r.users_count ?? 0})</span>
                            </button>
                        </li>
                    ))}
                </ul>
            </div>
            <div>
                {error && <p className="mb-4 text-sm text-red-300">{error}</p>}
                {!selected && <p className="text-slate-400">Select a role to edit permissions.</p>}
                {selected && (
                    <>
                        <h2 className="text-lg font-medium text-white">{selected.name}</h2>
                        {Object.entries(groups).map(([label, names]) => (
                            <div key={label} className="mt-6">
                                <h3 className="text-sm font-medium text-slate-400">{label}</h3>
                                <div className="mt-2 grid gap-2 sm:grid-cols-2">
                                    {names.map((name) => (
                                        <label key={name} className="flex items-center gap-2 text-sm text-slate-200">
                                            <input
                                                type="checkbox"
                                                checked={checked.includes(name)}
                                                disabled={superAdminOnly.includes(name)}
                                                onChange={() => togglePermission(name)}
                                            />
                                            {name}
                                        </label>
                                    ))}
                                </div>
                            </div>
                        ))}
                        <button
                            type="button"
                            onClick={() => void savePermissions()}
                            className="mt-6 rounded-md bg-emerald-600 px-4 py-2 text-sm text-white"
                        >
                            Save permissions
                        </button>
                    </>
                )}
            </div>
        </div>
    );
}

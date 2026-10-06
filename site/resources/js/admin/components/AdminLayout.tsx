import { Link, NavLink, Outlet } from 'react-router-dom';
import { useAuth } from '../lib/auth';

const linkClass = ({ isActive }: { isActive: boolean }) =>
    `block rounded-md px-3 py-2 text-sm ${isActive ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'}`;

export function AdminLayout() {
    const { user, logout } = useAuth();

    return (
        <div className="min-h-screen lg:flex">
            <aside className="w-full border-b border-slate-800 bg-slate-900 p-4 lg:w-64 lg:border-b-0 lg:border-r">
                <div className="mb-6">
                    <p className="text-lg font-semibold text-white">Enovak Admin</p>
                    <p className="text-xs text-slate-400">{user?.email}</p>
                </div>
                <nav className="space-y-1">
                    <NavLink to="/" end className={linkClass}>
                        Dashboard
                    </NavLink>
                    {user?.permissions.includes('manage-users') && (
                        <NavLink to="/users" className={linkClass}>
                            Users
                        </NavLink>
                    )}
                    {user?.is_super_admin && (
                        <>
                            <NavLink to="/roles" className={linkClass}>
                                Roles
                            </NavLink>
                            <NavLink to="/activity" className={linkClass}>
                                Activity
                            </NavLink>
                        </>
                    )}
                    <NavLink to="/account/password" className={linkClass}>
                        Change password
                    </NavLink>
                </nav>
                <button
                    type="button"
                    onClick={() => void logout()}
                    className="mt-6 w-full rounded-md border border-slate-700 px-3 py-2 text-sm text-slate-300 hover:bg-slate-800"
                >
                    Sign out
                </button>
            </aside>
            <main className="flex-1 p-6">
                <Outlet />
            </main>
        </div>
    );
}

export function DashboardPage() {
    const { user } = useAuth();

    return (
        <div>
            <h1 className="text-2xl font-semibold text-white">Dashboard</h1>
            <p className="mt-2 text-slate-400">Welcome, {user?.name}.</p>
            <div className="mt-8 grid gap-4 sm:grid-cols-2">
                <div className="rounded-lg border border-slate-800 bg-slate-900/50 p-4">
                    <p className="text-sm text-slate-400">Inquiries</p>
                    <p className="text-2xl font-semibold text-slate-200">—</p>
                    <p className="text-xs text-slate-500">Phase 3</p>
                </div>
                <div className="rounded-lg border border-slate-800 bg-slate-900/50 p-4">
                    <p className="text-sm text-slate-400">Invoices</p>
                    <p className="text-2xl font-semibold text-slate-200">—</p>
                    <p className="text-xs text-slate-500">Phase 4</p>
                </div>
            </div>
            {user?.is_super_admin && (
                <p className="mt-6 text-sm text-slate-500">
                    Recent activity: open{' '}
                    <Link to="/activity" className="text-sky-400 hover:underline">
                        Activity log
                    </Link>
                    .
                </p>
            )}
        </div>
    );
}

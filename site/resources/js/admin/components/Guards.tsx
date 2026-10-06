import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '../lib/auth';

export function RequireAuth() {
    const { user, loading } = useAuth();

    if (loading) {
        return (
            <div className="flex min-h-screen items-center justify-center text-slate-400">
                Loading…
            </div>
        );
    }

    if (!user) {
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
}

export function RequirePermission({ permission }: { permission: string }) {
    const { can } = useAuth();

    if (!can(permission)) {
        return (
            <div className="rounded-lg border border-amber-500/40 bg-amber-500/10 p-6 text-amber-100">
                You do not have permission to view this page.
            </div>
        );
    }

    return <Outlet />;
}

export function RequireSuperAdmin() {
    const { user } = useAuth();

    if (!user?.is_super_admin) {
        return (
            <div className="rounded-lg border border-amber-500/40 bg-amber-500/10 p-6 text-amber-100">
                Super admin only.
            </div>
        );
    }

    return <Outlet />;
}

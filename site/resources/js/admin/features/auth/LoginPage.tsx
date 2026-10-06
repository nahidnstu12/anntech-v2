import { FormEvent, useState } from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../../lib/auth';

export function LoginPage() {
    const { user, login } = useAuth();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState<string | null>(null);
    const [submitting, setSubmitting] = useState(false);

    if (user) {
        return <Navigate to="/" replace />;
    }

    async function onSubmit(e: FormEvent) {
        e.preventDefault();
        setError(null);
        setSubmitting(true);
        try {
            await login(email, password);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Login failed.');
        } finally {
            setSubmitting(false);
        }
    }

    return (
        <div className="flex min-h-screen items-center justify-center px-4">
            <form
                onSubmit={onSubmit}
                className="w-full max-w-md rounded-xl border border-slate-800 bg-slate-900 p-8 shadow-xl"
            >
                <h1 className="text-xl font-semibold text-white">Admin sign in</h1>
                <p className="mt-1 text-sm text-slate-400">Session expires after 3 days of inactivity.</p>
                {error && (
                    <p className="mt-4 rounded-md bg-red-500/10 px-3 py-2 text-sm text-red-300">{error}</p>
                )}
                <label className="mt-6 block text-sm text-slate-300">
                    Email
                    <input
                        type="email"
                        required
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-white"
                    />
                </label>
                <label className="mt-4 block text-sm text-slate-300">
                    Password
                    <input
                        type="password"
                        required
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-white"
                    />
                </label>
                <button
                    type="submit"
                    disabled={submitting}
                    className="mt-6 w-full rounded-md bg-sky-600 py-2 font-medium text-white hover:bg-sky-500 disabled:opacity-50"
                >
                    {submitting ? 'Signing in…' : 'Sign in'}
                </button>
            </form>
        </div>
    );
}

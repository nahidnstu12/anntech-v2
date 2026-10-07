import { FormEvent, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { api } from '../../lib/api';
import { useAuth } from '../../lib/auth';

export function ChangePasswordPage() {
    const navigate = useNavigate();
    const { refresh } = useAuth();
    const [currentPassword, setCurrentPassword] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [error, setError] = useState<string | null>(null);
    const [message, setMessage] = useState<string | null>(null);

    async function onSubmit(e: FormEvent) {
        e.preventDefault();
        setError(null);
        try {
            const res = await api<{ message: string }>('/me/password', {
                method: 'PUT',
                body: JSON.stringify({
                    current_password: currentPassword,
                    password,
                    password_confirmation: passwordConfirmation,
                }),
            });
            setMessage(res.message);
            await refresh();
            setTimeout(() => navigate('/login', { replace: true }), 800);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Update failed.');
        }
    }

    return (
        <div className="max-w-md">
            <h1 className="text-2xl font-semibold text-white">Change password</h1>
            {error && <p className="mt-4 text-sm text-red-300">{error}</p>}
            {message && <p className="mt-4 text-sm text-emerald-300">{message}</p>}
            <form onSubmit={onSubmit} className="mt-6 space-y-4">
                <label className="block text-sm text-slate-300">
                    Current password
                    <input
                        type="password"
                        required
                        value={currentPassword}
                        onChange={(e) => setCurrentPassword(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    />
                </label>
                <label className="block text-sm text-slate-300">
                    New password
                    <input
                        type="password"
                        required
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    />
                </label>
                <label className="block text-sm text-slate-300">
                    Confirm new password
                    <input
                        type="password"
                        required
                        value={passwordConfirmation}
                        onChange={(e) => setPasswordConfirmation(e.target.value)}
                        className="mt-1 w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2"
                    />
                </label>
                <button type="submit" className="rounded-md bg-sky-600 px-4 py-2 text-white">
                    Update password
                </button>
            </form>
        </div>
    );
}

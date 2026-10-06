import { createRoot } from 'react-dom/client';
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { AdminLayout, DashboardPage } from './components/AdminLayout';
import { RequireAuth, RequirePermission, RequireSuperAdmin } from './components/Guards';
import { ChangePasswordPage } from './features/account/ChangePasswordPage';
import { ActivityPage } from './features/activity/ActivityPage';
import { LoginPage } from './features/auth/LoginPage';
import { RolesPage } from './features/roles/RolesPage';
import { UsersPage } from './features/users/UsersPage';
import { AuthProvider } from './lib/auth';

const root = document.getElementById('admin-root');

if (root) {
    createRoot(root).render(
        <AuthProvider>
            <BrowserRouter basename="/admin">
                <Routes>
                    <Route path="/login" element={<LoginPage />} />
                    <Route element={<RequireAuth />}>
                        <Route element={<AdminLayout />}>
                            <Route index element={<DashboardPage />} />
                            <Route element={<RequirePermission permission="manage-users" />}>
                                <Route path="users" element={<UsersPage />} />
                            </Route>
                            <Route element={<RequireSuperAdmin />}>
                                <Route path="roles" element={<RolesPage />} />
                                <Route path="activity" element={<ActivityPage />} />
                            </Route>
                            <Route path="account/password" element={<ChangePasswordPage />} />
                        </Route>
                    </Route>
                    <Route path="*" element={<Navigate to="/" replace />} />
                </Routes>
            </BrowserRouter>
        </AuthProvider>,
    );
}

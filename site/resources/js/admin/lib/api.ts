export type AdminUser = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    job_title: string | null;
    is_active: boolean;
    role: { id: number; name: string } | null;
    permissions: string[];
    is_super_admin: boolean;
};

export type AdminRole = {
    id: number;
    name: string;
    is_system: boolean;
    permissions: string[];
    users_count?: number;
};

export type ActivityRow = {
    id: number;
    log_name: string;
    description: string;
    properties: Record<string, unknown> | null;
    causer: { id: number; name: string } | null;
    created_at: string;
};

export type ErrorLogRow = {
    id: number;
    level: string;
    exception_class: string;
    message: string;
    file: string | null;
    line: number | null;
    request_id: string | null;
    user: { id: number; name: string; email: string } | null;
    http_method: string | null;
    url: string | null;
    status_code: number | null;
    created_at: string;
};

export type ErrorLogDetail = ErrorLogRow & {
    stack_trace?: string | null;
    context?: Record<string, unknown> | null;
    route_name?: string | null;
    ip_address?: string | null;
    user_agent?: string | null;
};

function getCookie(name: string): string | null {
    const match = document.cookie.match(new RegExp(`(^|; )${name}=([^;]*)`));
    return match ? decodeURIComponent(match[2]) : null;
}

async function ensureCsrf(): Promise<void> {
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
}

export async function api<T>(
    path: string,
    options: RequestInit = {},
): Promise<T> {
    const method = (options.method ?? 'GET').toUpperCase();
    if (method !== 'GET' && method !== 'HEAD') {
        await ensureCsrf();
    }

    const xsrf = getCookie('XSRF-TOKEN');
    const headers: HeadersInit = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(options.headers ?? {}),
    };
    if (xsrf) {
        (headers as Record<string, string>)['X-XSRF-TOKEN'] = xsrf;
    }

    const response = await fetch(`/api/admin${path}`, {
        ...options,
        headers,
        credentials: 'include',
    });

    const body = await response.json().catch(() => ({}));

    if (!response.ok) {
        const message =
            body.message ??
            body.errors?.email?.[0] ??
            'Request failed.';
        throw new Error(message);
    }

    return body as T;
}

export async function fetchMe(): Promise<AdminUser> {
    const json = await api<{ data: AdminUser }>('/me');
    return json.data;
}

export async function login(email: string, password: string): Promise<AdminUser> {
    await ensureCsrf();
    const json = await api<{ data: AdminUser }>('/login', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
    });
    return json.data;
}

export async function logout(): Promise<void> {
    await api('/logout', { method: 'POST' });
}

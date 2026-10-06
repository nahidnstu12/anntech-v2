export const INVOICE_PERMISSIONS = [
    'manage-invoice-clients',
    'create-invoice',
    'edit-invoice',
    'send-invoice',
    'delete-invoice-draft',
    'view-all-invoices',
    'view-own-invoices',
] as const;

export const INVOICE_STATUS_LABELS: Record<string, string> = {
    draft: 'Draft',
    sent: 'Sent',
    paid: 'Paid',
    overdue: 'Overdue',
    cancelled: 'Cancelled',
};

export type InvoiceLine = {
    description: string;
    quantity: string;
    unit_price: string;
};

export type InvoiceDto = {
    id: number;
    display_number: string;
    type: string;
    status: string;
    invoice_client_id: number;
    billing_user_id: number;
    issue_date: string | null;
    due_date: string | null;
    currency: string;
    tax_rate: string;
    subtotal: string;
    tax_amount: string;
    total: string;
    notes_public: string | null;
    notes_internal: string | null;
    line_items: Array<{
        description: string;
        quantity: string;
        unit_price: string;
        line_total: string;
    }>;
    client?: { id: number; company_name: string; email: string };
    billing_user?: { id: number; name: string };
};

export type InvoiceClientDto = {
    id: number;
    company_name: string;
    contact_name: string | null;
    email: string;
    phone: string | null;
    address: string | null;
    notes: string | null;
};

export function calcPreview(lines: InvoiceLine[], taxRate: string) {
    let subtotal = 0;
    for (const line of lines) {
        const q = parseFloat(line.quantity) || 0;
        const p = parseFloat(line.unit_price) || 0;
        subtotal += Math.round(q * p * 100) / 100;
    }
    const rate = parseFloat(taxRate) || 0;
    const tax = Math.round(subtotal * (rate / 100) * 100) / 100;
    return { subtotal, tax, total: subtotal + tax };
}

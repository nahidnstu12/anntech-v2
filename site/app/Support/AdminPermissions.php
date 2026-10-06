<?php

namespace App\Support;

final class AdminPermissions
{
    public const MANAGE_USERS = 'manage-users';

    public const MANAGE_ROLES = 'manage-roles';

    public const VIEW_ACTIVITY_LOG = 'view-activity-log';

    /** Permissions only valid on the super_admin role. */
    public const SUPER_ADMIN_ONLY = [
        self::MANAGE_ROLES,
        self::VIEW_ACTIVITY_LOG,
    ];

    /** @return list<string> */
    public static function catalog(): array
    {
        return [
            self::MANAGE_USERS,
            self::MANAGE_ROLES,
            self::VIEW_ACTIVITY_LOG,
            'view-contact-inquiries',
            'manage-contact-inquiries',
            'manage-invoice-clients',
            'create-invoice',
            'edit-invoice',
            'send-invoice',
            'delete-invoice-draft',
            'view-all-invoices',
            'view-own-invoices',
            'manage-company-settings',
            'manage-services',
            'manage-products',
            'manage-showcase',
            'manage-client-logos',
            'manage-media',
        ];
    }

    /** @param list<string> $names */
    public static function stripSuperAdminOnly(array $names): array
    {
        return array_values(array_diff($names, self::SUPER_ADMIN_ONLY));
    }
}

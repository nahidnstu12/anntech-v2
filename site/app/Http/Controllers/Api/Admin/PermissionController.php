<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminPermissions;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        $groups = [
            'Users & access' => [
                AdminPermissions::MANAGE_USERS,
                AdminPermissions::MANAGE_ROLES,
                AdminPermissions::VIEW_ACTIVITY_LOG,
            ],
            'Inquiries (Phase 3)' => [
                'view-contact-inquiries',
                'manage-contact-inquiries',
            ],
            'Invoices (Phase 4)' => [
                'manage-invoice-clients',
                'create-invoice',
                'edit-invoice',
                'send-invoice',
                'delete-invoice-draft',
                'view-all-invoices',
                'view-own-invoices',
            ],
            'CMS (Phase 5)' => [
                'manage-company-settings',
                'manage-services',
                'manage-products',
                'manage-showcase',
                'manage-client-logos',
                'manage-media',
            ],
        ];

        return response()->json([
            'data' => [
                'catalog' => AdminPermissions::catalog(),
                'groups' => $groups,
                'super_admin_only' => AdminPermissions::SUPER_ADMIN_ONLY,
            ],
        ]);
    }
}

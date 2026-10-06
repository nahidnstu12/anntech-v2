<?php

use App\Http\Controllers\Api\Admin\ActivityController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ContactInquiryController;
use App\Http\Controllers\Api\Admin\MePasswordController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/me/password', [MePasswordController::class, 'update']);

        Route::get('/summary', [ContactInquiryController::class, 'summary']);

        Route::middleware('permission:view-contact-inquiries|manage-contact-inquiries')->group(function (): void {
            Route::get('/contact-inquiries', [ContactInquiryController::class, 'index']);
            Route::get('/contact-inquiries/{contactInquiry}', [ContactInquiryController::class, 'show']);
        });

        Route::patch('/contact-inquiries/{contactInquiry}', [ContactInquiryController::class, 'update'])
            ->middleware('permission:manage-contact-inquiries');

        Route::middleware('permission:manage-users')->group(function (): void {
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users', [UserController::class, 'store']);
            Route::get('/users/{user}', [UserController::class, 'show']);
            Route::patch('/users/{user}', [UserController::class, 'update']);
            Route::get('/roles/options', [RoleController::class, 'options']);
        });

        Route::middleware(['permission:manage-roles', 'super_admin'])->group(function (): void {
            Route::get('/roles', [RoleController::class, 'index']);
            Route::post('/roles', [RoleController::class, 'store']);
            Route::patch('/roles/{role}', [RoleController::class, 'update']);
            Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
            Route::put('/roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
            Route::get('/permissions', [PermissionController::class, 'index']);
        });

        Route::middleware(['permission:view-activity-log', 'super_admin'])->group(function (): void {
            Route::get('/activity', [ActivityController::class, 'index']);
        });
    });
});

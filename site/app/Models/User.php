<?php

namespace App\Models;

use App\Support\AdminRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'phone',
        'job_title',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active', 'phone', 'job_title'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(AdminRoles::SUPER_ADMIN);
    }

    public function isProtectedSuperAdmin(): bool
    {
        $email = config('admin.super_admin_email');

        return $email && strcasecmp($this->email, $email) === 0;
    }

    public function revokeAllSessions(): void
    {
        DB::table('sessions')->where('user_id', $this->id)->delete();
    }

    /** @return list<string> */
    public function permissionNames(): array
    {
        return $this->getAllPermissions()->pluck('name')->sort()->values()->all();
    }
}

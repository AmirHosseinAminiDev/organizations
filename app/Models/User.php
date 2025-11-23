<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = [
        'organization_id',
        'role_id',
        'name',
        'last_name',
        'national_code',
        'phone',
        'password'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Permission check
    public function hasPermission($permission)
    {
        return $this->role
            ->permissions
            ->pluck('name')
            ->contains($permission);
    }
}

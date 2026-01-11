<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }

    // Helper
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }
}


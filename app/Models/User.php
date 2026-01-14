<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Fakultas;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password','role_id','fakultas_id','prodi_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }

    public function fakultas()
{
    return $this->belongsTo(Fakultas::class, 'fakultas_id');
}



       // ===== HELPER ROLE =====
  public function hasRole($role): bool
{
    if (is_array($role)) {
        // Jika role adalah array, cek apakah user memiliki salah satu role
        return $this->role && in_array($this->role->name, $role);
    }
    
    // Jika role adalah string
    return $this->role && $this->role->name === $role;
}

public function hasAnyRole(array $roles): bool
{
    return $this->role && in_array($this->role->name, $roles);
}

public function hasAllRoles(array $roles): bool
{
    // Karena user hanya punya satu role, tidak mungkin punya semua role
    // Tapi untuk konsistensi API
    return $this->role && in_array($this->role->name, $roles) && count($roles) === 1;
}

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }
}


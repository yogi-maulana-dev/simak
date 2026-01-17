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

  

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }

    public function fakultas()
{
    return $this->belongsTo(Fakultas::class, 'fakultas_id');
}



    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Helper methods
    public function isSuperadmin()
    {
        return $this->role_id === 1 || ($this->role && $this->role->name === 'superadmin');
    }

    public function isUser()
    {
        return $this->role_id === 3 || ($this->role && $this->role->name === 'user');
    }

    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasRoleId($roleId)
    {
        return $this->role_id == $roleId;
    }

}


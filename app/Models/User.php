<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'fakultas_id',
        'prodi_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }
    
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
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

     

    /**
     * Scope untuk user yang memiliki fakultas.
     */
    public function scopeHasFakultas($query)
    {
        return $query->whereNotNull('fakultas_id');
    }

    /**
     * Scope untuk user yang memiliki prodi.
     */
    public function scopeHasProdi($query)
    {
        return $query->whereNotNull('prodi_id');
    }

    /**
     * Scope untuk user berdasarkan role.
     */
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }


     public function hasValidResetToken(): bool
    {
        return $this->reset_password_token && 
               $this->reset_password_token_expires_at &&
               Carbon::now()->lt($this->reset_password_token_expires_at);
    }

    /**
     * Check if token matches
     */
    public function verifyResetToken($token): bool
    {
        return $this->hasValidResetToken() && 
               Hash::check($token, $this->reset_password_token);
    }

    /**
     * Clear reset token
     */
    public function clearResetToken(): void
    {
        $this->update([
            'reset_password_token' => null,
            'reset_password_token_expires_at' => null
        ]);
    }

    /**
     * Scope for users with valid reset tokens
     */
    public function scopeWithValidResetToken($query)
    {
        return $query->whereNotNull('reset_password_token')
                    ->where('reset_password_token_expires_at', '>', Carbon::now());
    }

}
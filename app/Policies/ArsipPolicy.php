<?php

namespace App\Policies;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArsipPolicy
{
    /**
     * SUPERADMIN & ADMIN UNIV → FULL AKSES
     */
    private function isGlobalAdmin(User $user)
    {
        return $user->hasRole('superadmin') || $user->hasRole('admin_univ');
    }

    /**
     * VIEW LIST / DETAIL
     */
    public function view(User $user, Arsip $arsip)
    {
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        if ($user->hasRole('admin_fakultas') || $user->hasRole('asesor_fakultas')) {
            return $user->fakultas_id === $arsip->fakultas_id;
        }

        if ($user->hasRole('admin_prodi') || $user->hasRole('asesor_prodi')) {
            return $user->prodi_id === $arsip->prodi_id;
        }

        return false;
    }

    /**
     * CREATE
     */
    public function create(User $user)
    {
        return ! $user->hasRole('asesor_fakultas')
            && ! $user->hasRole('asesor_prodi');
    }

    /**
     * UPDATE
     */
    public function update(User $user, Arsip $arsip)
    {
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        if ($user->hasRole('admin_fakultas')) {
            return $user->fakultas_id === $arsip->fakultas_id;
        }

        if ($user->hasRole('admin_prodi')) {
            return $user->prodi_id === $arsip->prodi_id;
        }

        return false;
    }

    /**
     * DELETE
     */
    public function delete(User $user, Arsip $arsip)
    {
        return $this->isGlobalAdmin($user);
    }
}

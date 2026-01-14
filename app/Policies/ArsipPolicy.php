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
    private function isGlobalSuperAdmin(User $user)
    {
        return $user->hasRole('superadmin') || $user->hasRole('admin_univ');
    }

    private function isGlobalAdmin(User $user)
    {
        return
            $user->hasRole('admin_univ') ||
            $user->hasRole('admin_fakultas') ||
            $user->hasRole('admin_prodi');
    }



    /**
     * Cek apakah arsip terkait dengan fakultas user
     */
    private function isInUserFakultas(User $user, Arsip $arsip)
    {
        return $arsip->dataFakultas()
            ->where('fakultas_id', $user->fakultas_id)
            ->exists();
    }

    /**
     * Cek apakah arsip terkait dengan prodi user
     */
    private function isInUserProdi(User $user, Arsip $arsip)
    {
        // Jika ada relasi dataProdi
        return $arsip->dataProdi()
            ->where('prodi_id', $user->prodi_id)
            ->exists();
        // Atau jika prodi melalui fakultas
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
            return $this->isInUserFakultas($user, $arsip);
        }

        if ($user->hasRole('admin_prodi') || $user->hasRole('asesor_prodi')) {
            return $this->isInUserProdi($user, $arsip);
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
            return $this->isInUserFakultas($user, $arsip);
        }

        if ($user->hasRole('admin_prodi')) {
            return $this->isInUserProdi($user, $arsip);
        }

        return false;
    }

    /**
     * DELETE
     */
   // Tambahkan method ini di ArsipPolicy
public function viewAny(User $user)
{
    // Semua user yang login bisa melihat daftar
    return true;
}

// Perbaiki method delete untuk lebih spesifik
public function delete(User $user, Arsip $arsip)
{
    // Hanya superadmin & admin univ bisa delete
    if ($user->hasRole('superadmin') || $user->hasRole('admin_univ')) {
        return true;
    }

    // Admin fakultas hanya bisa delete arsip di fakultasnya
    if ($user->hasRole('admin_fakultas')) {
        return $this->isInUserFakultas($user, $arsip);
    }

    return false;
}
}

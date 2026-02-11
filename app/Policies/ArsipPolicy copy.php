<?php

namespace App\Policies;

use App\Models\Arsip;
use App\Models\User;

class ArsipPolicy
{
    private function roleId(User $user)
    {
        return optional($user->role)->id;
    }

    private function isSuperAdmin(User $user)   { return $this->roleId($user) == 1; }
    private function isAdminUniv(User $user)    { return $this->roleId($user) == 2; }
    private function isAdminFakultas(User $user){ return $this->roleId($user) == 3; }
    private function isAdminProdi(User $user)   { return $this->roleId($user) == 4; }
    private function isAsesorFakultas(User $user){ return $this->roleId($user) == 5; }

    private function isOwner(User $user, Arsip $arsip)
    {
        return $arsip->user_id == $user->id;
    }

    private function isInUserFakultas(User $user, Arsip $arsip)
    {
        return $arsip->fakultas_id == $user->fakultas_id;
    }

    private function isInUserProdi(User $user, Arsip $arsip)
    {
        return $arsip->prodi_id == $user->prodi_id;
    }

    //==============================================

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Arsip $arsip)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdminUniv($user) || $this->isAsesorFakultas($user) || $this->isAdminProdi($user)) {
            return $this->isOwner($user, $arsip);
        }

        if ($this->isAdminFakultas($user)) {
            return $this->isInUserFakultas($user, $arsip);
        }

        return false;
    }

    public function create(User $user)
    {
        // asesor tidak boleh create
        return ! $this->isAsesorFakultas($user);
    }

    public function update(User $user, Arsip $arsip)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdminUniv($user) || $this->isAdminProdi($user) || $this->isAsesorFakultas($user)) {
            return $this->isOwner($user, $arsip);
        }

        if ($this->isAdminFakultas($user)) {
            return $this->isInUserFakultas($user, $arsip);
        }

        return false;
    }

    public function delete(User $user, Arsip $arsip)
    {
        // Super Admin → TIDAK BOLEH DELETE
        if ($this->isSuperAdmin($user)) {
            return false;
        }

        // Admin Univ → hanya arsip miliknya
        if ($this->isAdminUniv($user)) {
            return $this->isOwner($user, $arsip);
        }

        // Admin Fakultas → arsip fakultasnya
        if ($this->isAdminFakultas($user)) {
            return $this->isInUserFakultas($user, $arsip);
        }

        // Admin Prodi → arsip miliknya sendiri
        if ($this->isAdminProdi($user)) {
            return $this->isOwner($user, $arsip);
        }

        // Asesor → tidak boleh delete
        return false;
    }
}

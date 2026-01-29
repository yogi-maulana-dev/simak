<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UserResetPassword extends Component
{
    public $user;
    public $resetType = 'default';
    public $customPassword = '';
    public $customPasswordConfirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function getShowCustomFieldsProperty()
    {
        return $this->resetType === 'custom';
    }

    public function updatedResetType()
    {
        $this->customPassword = '';
        $this->customPasswordConfirmation = '';
        $this->resetErrorBag();
    }

    public function resetPassword()
    {
        if ($this->resetType === 'custom') {
            $this->validate([
                'customPassword' => 'required|min:8|confirmed',
                'customPasswordConfirmation' => 'required',
            ], [
                'customPassword.required' => 'Password wajib diisi',
                'customPassword.min' => 'Password minimal 8 karakter',
                'customPassword.confirmed' => 'Konfirmasi password tidak cocok',
                'customPasswordConfirmation.required' => 'Konfirmasi password wajib diisi',
            ]);
        }

        $password = $this->resetType === 'default' ? 'admin@1234' : $this->customPassword;

        $this->user->update([
            'password' => Hash::make($password),
        ]);

        session()->flash('success', 'Password berhasil direset!');
        
        $this->dispatch('password-reset-success');
    }

    public function render()
    {
        return view('livewire.admin.user-reset-password');
    }
}
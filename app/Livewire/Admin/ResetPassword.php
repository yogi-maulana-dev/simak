<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPassword extends Component
{
    public User $user;
    public $resetType = 'default'; // 'default' or 'custom'
    public $customPassword = '';
    public $customPassword_confirmation = ''; // PERUBAHAN: tambah underscore
    public $showPassword = false;
    public $showPasswordConfirmation = false;
    
    public $defaultPassword = 'admin@1234';
    
    protected $rules = [
        'customPassword' => 'required|min:8|confirmed',
    ];

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
        $this->customPassword_confirmation = '';
        $this->resetErrorBag();
    }

    public function togglePasswordVisibility($field)
    {
        if ($field === 'customPassword') {
            $this->showPassword = !$this->showPassword;
        } elseif ($field === 'customPassword_confirmation') {
            $this->showPasswordConfirmation = !$this->showPasswordConfirmation;
        }
    }

    public function generateRandomPassword()
    {
        $this->customPassword = Str::password(12);
        $this->customPassword_confirmation = $this->customPassword;
        $this->showPassword = true;
        $this->showPasswordConfirmation = true;
        $this->resetType = 'custom';
    }

    public function resetPassword()
    {
        if ($this->resetType === 'custom') {
            $this->validate([
                'customPassword' => 'required|min:8|confirmed',
            ], [
                'customPassword.required' => 'Password wajib diisi',
                'customPassword.min' => 'Password minimal 8 karakter',
                'customPassword.confirmed' => 'Konfirmasi password tidak cocok',
            ]);
        }

        $password = $this->resetType === 'default' ? $this->defaultPassword : $this->customPassword;

        $this->user->update([
            'password' => Hash::make($password),
            'password_changed_at' => $this->resetType === 'default' ? null : now(),
        ]);

        session()->flash('success', 'Password untuk user ' . $this->user->name . ' berhasil direset!');
        
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.reset-password')->layout('layouts.app');
    }
}
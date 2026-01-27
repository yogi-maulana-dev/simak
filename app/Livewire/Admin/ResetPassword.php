<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\PasswordResetTokenMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ResetPassword extends Component
{
    public User $user;
    public $password;
    public $password_confirmation;
    
    // ✅ TAMBAHKAN VARIABEL INI
    public $showPassword = false;
    public $newPassword = '';
    
    // Modal state
    public $showModal = false;
    public $showConfirmation = false;
    public $activeTab = 'direct'; // 'direct' or 'email'
    
    // Email token
    public $generatedToken;
    public $tokenExpiresAt;
    
    // Untuk menentukan jika ini halaman standalone atau modal
    public $isStandalonePage = true;
    
    // Password default
    public $defaultPassword = 'admin@1234';
    
    protected $rules = [
        'password' => 'required|min:8|confirmed',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        
        // Check authorization
        $this->checkAuthorization();
        
        // Jika diakses dari route langsung, show modal langsung
        $this->showModal = true;
    }

    protected function checkAuthorization()
    {
        // Superadmin bisa reset semua user
        if (auth()->user()->hasRole('superadmin')) {
            return;
        }
        
        // Admin hanya bisa reset user sendiri
        if (auth()->user()->id !== $this->user->id) {
            abort(403, 'Unauthorized action. Anda hanya bisa reset password akun sendiri.');
        }
    }

    // Method 1: Reset dengan password default
    public function resetWithDefaultPassword()
    {
        try {
            // Update password dengan default
            $this->user->update([
                'password' => Hash::make($this->defaultPassword),
                'password_changed_at' => null, // Force user to change on next login
            ]);

            // Set newPassword untuk ditampilkan
            $this->newPassword = $this->defaultPassword;
            
            // Show confirmation
            $this->showConfirmation = true;
            $this->showModal = false;

            session()->flash('success', 'Password berhasil direset ke default untuk ' . $this->user->name);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    // Method 2: Generate and send 6-digit token via email
    public function sendTokenEmail()
    {
        try {
            // Generate 6-digit token
            $this->generatedToken = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->tokenExpiresAt = Carbon::now()->addHours(1);

            // Save token to user (hashed for security)
            $this->user->update([
                'reset_password_token' => Hash::make($this->generatedToken),
                'reset_password_token_expires_at' => $this->tokenExpiresAt,
            ]);

            // Send email with token
            Mail::to($this->user->email)->send(new PasswordResetTokenMail(
                $this->user,
                $this->generatedToken,
                $this->tokenExpiresAt
            ));

            session()->flash('success', 'Token reset password telah dikirim ke email ' . $this->user->email);
            
            // Show confirmation with token (for admin reference)
            $this->showConfirmation = true;
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim token: ' . $e->getMessage());
        }
    }

    // Method 3: Show token directly (without email)
    public function generateTokenOnly()
    {
        try {
            // Generate 6-digit token
            $this->generatedToken = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->tokenExpiresAt = Carbon::now()->addHours(1);

            // Save token to user (hashed for security)
            $this->user->update([
                'reset_password_token' => Hash::make($this->generatedToken),
                'reset_password_token_expires_at' => $this->tokenExpiresAt,
            ]);

            // Show confirmation with token
            $this->showConfirmation = true;
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat token: ' . $e->getMessage());
        }
    }

    // Method 4: Reset password dengan input manual
    public function resetPasswordDirectly()
    {
        $this->validate();

        try {
            // Update password
            $this->user->update([
                'password' => Hash::make($this->password),
                'password_changed_at' => now(),
            ]);

            // Set newPassword untuk ditampilkan
            $this->newPassword = $this->password;
            
            // Clear form
            $this->reset(['password', 'password_confirmation']);

            // Show confirmation with new password
            $this->showConfirmation = true;
            $this->showModal = false;

            session()->flash('success', 'Password berhasil direset untuk ' . $this->user->name);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    // Method 5: Generate random password
    public function generatePassword()
    {
        $this->password = Str::password(12);
        $this->password_confirmation = $this->password;
    }

    // Method 6: Copy token/password to clipboard
    public function copyToClipboard($text)
    {
        $this->dispatch('copy-to-clipboard', text: $text);
        session()->flash('info', 'Berhasil disalin ke clipboard!');
    }

    // Method 7: Close modal
    public function closeModal()
    {
        if ($this->isStandalonePage) {
            return redirect()->route('admin.users.index');
        }
        
        $this->reset(['showModal', 'password', 'password_confirmation', 'activeTab', 'showPassword']);
    }

    // Method 8: Close confirmation
    public function closeConfirmation()
    {
        if ($this->isStandalonePage) {
            return redirect()->route('admin.users.index');
        }
        
        $this->reset(['showConfirmation', 'generatedToken', 'tokenExpiresAt', 'password', 'newPassword']);
    }

    // Method 9: Reset token (generate new one)
    public function regenerateToken()
    {
        $this->sendTokenEmail();
    }

    // ✅ TAMBAHKAN METHOD UNTUK TOGGLE PASSWORD VISIBILITY
    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.admin.reset-password')
            ->layout('layouts.app');
    }
}
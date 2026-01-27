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
    
    public $showPassword = false;
    public $newPassword = '';
    
    // Modal state
    public $showModal = false;
    public $showConfirmation = false;
    public $activeTab = 'direct'; // 'direct' or 'email'
    
    // ✅ TAMBAHKAN PROPERTY INI
    public $confirmingDefaultReset = false;
    public $showSuccessModal = false;
    
    // Email token
    public $generatedToken;
    public $tokenExpiresAt;
    
    public $isStandalonePage = true;
    public $defaultPassword = 'admin@1234';
    
    protected $rules = [
        'password' => 'required|min:8|confirmed',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->checkAuthorization();
        $this->showModal = true;
    }

    public function confirmDefaultReset()
    {
        $this->user->update([
            'password' => Hash::make($this->defaultPassword),
            'password_changed_at' => null,
        ]);

        $this->newPassword = $this->defaultPassword;
        $this->showSuccessModal = true;
        $this->confirmingDefaultReset = false;

        session()->flash('success', 'Password berhasil direset ke default!');
    }

    protected function checkAuthorization()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return;
        }
        
        if (auth()->user()->id !== $this->user->id) {
            abort(403, 'Unauthorized action. Anda hanya bisa reset password akun sendiri.');
        }
    }

    // ✅ RENAMED: resetWithCustomPassword (dari resetPasswordDirectly)
public function resetWithCustomPassword()
{
    $this->validate();

    try {
        $this->user->update([
            'password' => Hash::make($this->password),
            'password_changed_at' => now(),
        ]);

        // Tampilkan alert success
        session()->flash('success', 'Password untuk user ' . $this->user->name . ' berhasil direset!');

        // Redirect ke halaman users index
        return redirect()->route('admin.users.index');

    } catch (\Exception $e) {
        session()->flash('error', 'Gagal mereset password: ' . $e->getMessage());
    }
}

    // Method 1: Reset dengan password default (dari modal konfirmasi)
 public function resetWithDefaultPassword()
{
    try {
        $this->user->update([
            'password' => Hash::make($this->defaultPassword),
            'password_changed_at' => null,
        ]);

        session()->flash('success', 'Password untuk ' . $this->user->name . ' berhasil direset ke default!');
        
        // Redirect ke index
        return redirect()->route('admin.users.index');

    } catch (\Exception $e) {
        session()->flash('error', 'Gagal mereset password: ' . $e->getMessage());
    }
}

public function sendResetLink()
{
    try {
        $this->generatedToken = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->tokenExpiresAt = Carbon::now()->addHours(24);

        $this->user->update([
            'reset_password_token' => Hash::make($this->generatedToken),
            'reset_password_token_expires_at' => $this->tokenExpiresAt,
        ]);

        Mail::to($this->user->email)->send(new PasswordResetTokenMail(
            $this->user,
            $this->generatedToken,
            $this->tokenExpiresAt
        ));

        session()->flash('success', 'Link reset password telah dikirim ke email ' . $this->user->email);
        
        // Redirect ke index
        return redirect()->route('admin.users.index');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal mengirim link reset: ' . $e->getMessage());
    }
}


    // ✅ RENAMED: generateRandomPassword (dari generatePassword)
    public function generateRandomPassword()
    {
        $this->password = Str::password(12);
        $this->password_confirmation = $this->password;
    }



    // Method: Generate and send 6-digit token via email
    public function sendTokenEmail()
    {
        try {
            $this->generatedToken = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->tokenExpiresAt = Carbon::now()->addHours(1);

            $this->user->update([
                'reset_password_token' => Hash::make($this->generatedToken),
                'reset_password_token_expires_at' => $this->tokenExpiresAt,
            ]);

            Mail::to($this->user->email)->send(new PasswordResetTokenMail(
                $this->user,
                $this->generatedToken,
                $this->tokenExpiresAt
            ));

            session()->flash('success', 'Token reset password telah dikirim ke email ' . $this->user->email);
            
            $this->showSuccessModal = true;
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim token: ' . $e->getMessage());
        }
    }

    // Method: Show token directly
    public function generateTokenOnly()
    {
        try {
            $this->generatedToken = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->tokenExpiresAt = Carbon::now()->addHours(1);

            $this->user->update([
                'reset_password_token' => Hash::make($this->generatedToken),
                'reset_password_token_expires_at' => $this->tokenExpiresAt,
            ]);

            $this->showSuccessModal = true;
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat token: ' . $e->getMessage());
        }
    }

    // ✅ TAMBAHKAN: Method untuk close success modal
    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        if ($this->isStandalonePage) {
            return redirect()->route('admin.users.index');
        }
    }

    // ✅ TAMBAHKAN: Method untuk send password via email
    public function sendPasswordViaEmail()
    {
        try {
            // Buat email untuk mengirim password
            // Anda perlu membuat Mail class untuk ini
            Mail::to($this->user->email)->send(new \App\Mail\NewPasswordMail(
                $this->user,
                $this->newPassword
            ));

            session()->flash('success', 'Password baru telah dikirim ke email ' . $this->user->email);
            $this->showSuccessModal = false;
            
            if ($this->isStandalonePage) {
                return redirect()->route('admin.users.index');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    // Method: Copy token/password to clipboard
    public function copyToClipboard($text)
    {
        $this->dispatch('copy-to-clipboard', text: $text);
        session()->flash('info', 'Berhasil disalin ke clipboard!');
    }

    // Method: Close modal
    public function closeModal()
    {
        if ($this->isStandalonePage) {
            return redirect()->route('admin.users.index');
        }
        
        $this->reset(['showModal', 'password', 'password_confirmation', 'activeTab', 'showPassword']);
    }

    // Method: Toggle password visibility
    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.admin.reset-password');
    }
}
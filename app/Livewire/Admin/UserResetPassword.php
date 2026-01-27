<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class UserResetPassword extends Component
{
    public User $user;
    public $activeTab = 'direct';
    public $password = '';
    public $password_confirmation = '';
    public $showPassword = false;
    public $newPassword = '';
    
    public $confirmingDefaultPasswordReset = false;
    public $showSuccessModal = false;
    public $resetLinkSent = false;

    protected $rules = [
        'password' => 'required|min:8|confirmed',
    ];

    protected $messages = [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        
        // Authorization check
        if (!auth()->user()->can('resetPassword', $user)) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function getPasswordStrengthProperty()
    {
        if (empty($this->password)) return null;
        
        $score = 0;
        
        // Length
        if (strlen($this->password) >= 8) $score++;
        if (strlen($this->password) >= 12) $score++;
        
        // Complexity
        if (preg_match('/[A-Z]/', $this->password)) $score++;
        if (preg_match('/[a-z]/', $this->password)) $score++;
        if (preg_match('/[0-9]/', $this->password)) $score++;
        if (preg_match('/[^A-Za-z0-9]/', $this->password)) $score++;
        
        if ($score <= 3) return 'weak';
        if ($score <= 5) return 'medium';
        return 'strong';
    }

    public function confirmDefaultPasswordReset()
    {
        $this->confirmingDefaultPasswordReset = true;
    }

    public function resetWithDefaultPassword()
    {
        $this->newPassword = 'admin@1234';
        
        $this->user->update([
            'password' => Hash::make($this->newPassword),
            'password_changed_at' => null,
            'must_change_password' => true,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->user)
            ->log('reset password ke default');

        $this->confirmingDefaultPasswordReset = false;
        $this->showSuccessModal = true;
        
        session()->flash('status', 'password-reset');
        session()->flash('message', 'Password berhasil direset ke default.');
    }

    public function generateRandomPassword()
    {
        $this->password = Str::password(12, true, true, true, true);
        $this->password_confirmation = $this->password;
    }

    public function resetWithCustomPassword()
    {
        $this->validate();

        $this->newPassword = $this->password;
        
        $this->user->update([
            'password' => Hash::make($this->newPassword),
            'password_changed_at' => now(),
            'must_change_password' => false,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->user)
            ->log('reset password dengan custom password');

        $this->reset(['password', 'password_confirmation']);
        $this->showSuccessModal = true;
        
        session()->flash('status', 'password-reset');
        session()->flash('message', 'Password berhasil direset.');
    }

    public function sendResetLink()
    {
        // Generate reset token
        $token = Str::random(60);
        
        // Save to password_resets table
        \DB::table('password_resets')->updateOrInsert(
            ['email' => $this->user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Send email
        Mail::to($this->user->email)->send(new PasswordResetMail($this->user, $token));

        $this->resetLinkSent = true;
        
        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->user)
            ->log('mengirim link reset password via email');
        
        session()->flash('status', 'reset-link-sent');
    }

    public function sendPasswordViaEmail()
    {
        // Kirim password via email
        Mail::to($this->user->email)->send(new \App\Mail\NewPasswordMail($this->user, $this->newPassword));

        $this->showSuccessModal = false;
        
        session()->flash('status', 'password-email-sent');
        session()->flash('message', 'Password berhasil dikirim via email.');
    }

    public function copyToClipboard($text)
    {
        $this->dispatch('password-copied');
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->redirect(route('users.index'));
    }

    public function render()
    {
        return view('livewire.user-reset-password');
    }
}
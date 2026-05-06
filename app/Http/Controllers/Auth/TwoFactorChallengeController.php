<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\TwoFactorAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorChallengeController extends Controller
{
    /** Halaman tantangan kode OTP / recovery code. */
    public function show(Request $request): View|RedirectResponse
    {
        // Pastikan ada sesi 2FA pending
        if (! $this->hasPendingChallenge($request)) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('2fa.user_id');
        $user   = User::find($userId);

        return view('auth.two-factor-challenge', [
            'maskedEmail' => $this->maskEmail($user?->email ?? ''),
        ]);
    }

    /** Verifikasi kode TOTP atau recovery code. */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Masukkan kode 6-digit atau recovery code.',
        ]);

        if (! $this->hasPendingChallenge($request)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi login kedaluwarsa. Silakan login ulang.']);
        }

        $userId   = $request->session()->get('2fa.user_id');
        $remember = (bool) $request->session()->get('2fa.remember', false);
        $user     = User::findOrFail($userId);

        $code = trim((string) $request->input('code'));
        $verified = false;
        $usedRecovery = null;

        // 1) Coba sebagai TOTP code (6 digit angka)
        if (preg_match('/^\d{6}$/', $code)) {
            $verified = TwoFactorAuth::verify($user->two_factor_secret, $code);
        }

        // 2) Coba sebagai recovery code (XXXX-XXXX)
        if (! $verified && preg_match('/^[A-Z0-9]{4}-?[A-Z0-9]{4}$/i', $code)) {
            $normalized = strtoupper(str_replace(' ', '', $code));
            if (! str_contains($normalized, '-') && strlen($normalized) === 8) {
                $normalized = substr($normalized, 0, 4) . '-' . substr($normalized, 4);
            }

            $codes = $user->two_factor_recovery_codes ?? [];
            if (in_array($normalized, $codes, true)) {
                $verified = true;
                $usedRecovery = $normalized;

                // Hapus recovery code yang dipakai (one-time use)
                $remaining = array_values(array_diff($codes, [$normalized]));
                $user->forceFill(['two_factor_recovery_codes' => $remaining])->save();
            }
        }

        if (! $verified) {
            ActivityLogger::log(
                action: 'tfa_failed',
                description: "Verifikasi 2FA gagal untuk {$user->email}",
                user: $user,
            );

            throw ValidationException::withMessages([
                'code' => 'Kode salah atau sudah kedaluwarsa. Coba kode terbaru dari aplikasi authenticator.',
            ]);
        }

        // ── SUKSES — full login ──
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Bersihkan session 2FA pending
        $request->session()->forget(['2fa.user_id', '2fa.remember', '2fa.expires']);

        ActivityLogger::log(
            action: $usedRecovery ? 'tfa_recovery_used' : 'tfa_success',
            description: $usedRecovery
                ? "Login menggunakan recovery code (sisa: " . count($user->two_factor_recovery_codes ?? []) . ")"
                : 'Verifikasi 2FA berhasil',
            user: $user,
            metadata: $usedRecovery ? ['code' => $usedRecovery] : [],
        );

        return redirect()->intended(route('dashboard'));
    }

    /** Batal challenge — kembali ke login. */
    public function cancel(Request $request): RedirectResponse
    {
        $request->session()->forget(['2fa.user_id', '2fa.remember', '2fa.expires']);
        return redirect()->route('login');
    }

    private function hasPendingChallenge(Request $request): bool
    {
        $userId  = $request->session()->get('2fa.user_id');
        $expires = (int) $request->session()->get('2fa.expires', 0);

        if (! $userId || $expires < now()->timestamp) {
            $request->session()->forget(['2fa.user_id', '2fa.remember', '2fa.expires']);
            return false;
        }
        return true;
    }

    private function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) return $email;
        [$name, $domain] = explode('@', $email, 2);
        $masked = strlen($name) <= 2
            ? str_repeat('*', strlen($name))
            : substr($name, 0, 2) . str_repeat('*', max(1, strlen($name) - 2));
        return "{$masked}@{$domain}";
    }
}

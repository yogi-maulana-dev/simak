<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use App\Support\TwoFactorAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    /** Halaman utama 2FA di profile. */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Kalau belum aktif & belum pernah generate, generate secret baru sementara
        // (disimpan di session, baru di-commit ke DB setelah dikonfirmasi)
        if (! $user->hasTwoFactorEnabled() && ! session()->has('2fa_pending_secret')) {
            session(['2fa_pending_secret' => TwoFactorAuth::generateSecret()]);
        }

        $pendingSecret = session('2fa_pending_secret');
        $otpauthUri    = $pendingSecret
            ? TwoFactorAuth::provisioningUri($pendingSecret, $user->email, config('app.name', 'SIMAK UML'))
            : null;

        return view('profile.two-factor', [
            'enabled'       => $user->hasTwoFactorEnabled(),
            'pendingSecret' => $pendingSecret,
            'qrUrl'         => $otpauthUri ? TwoFactorAuth::qrCodeUrl($otpauthUri, 220) : null,
            'otpauthUri'    => $otpauthUri,
            'recoveryCodes' => session('2fa_recovery_codes', []),
        ]);
    }

    /** User submit kode OTP untuk konfirmasi setup. */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ], [
            'code.required' => 'Masukkan kode 6 digit dari aplikasi authenticator.',
            'code.digits'   => 'Kode harus 6 angka.',
        ]);

        $secret = session('2fa_pending_secret');
        abort_unless($secret, 400, 'Sesi setup 2FA tidak ditemukan.');

        if (! TwoFactorAuth::verify($secret, $request->input('code'))) {
            return back()->withErrors([
                'code' => 'Kode salah. Pastikan jam HP Anda akurat dan coba kode terbaru.',
            ]);
        }

        $user = $request->user();
        $recoveryCodes = TwoFactorAuth::generateRecoveryCodes(8);

        $user->forceFill([
            'two_factor_secret'         => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at'   => now(),
        ])->save();

        ActivityLogger::log(
            action: 'tfa_enabled',
            description: '2FA Google Authenticator diaktifkan',
            user: $user,
        );

        // Hapus pending, simpan recovery codes 1x untuk ditampilkan
        session()->forget('2fa_pending_secret');
        session()->flash('2fa_recovery_codes', $recoveryCodes);

        return redirect()->route('two-factor.show')
            ->with('status', 'Two-factor authentication berhasil diaktifkan! Simpan kode pemulihan di tempat aman.');
    }

    /** Disable 2FA — butuh password konfirmasi. */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Password salah.',
        ]);

        $user = $request->user();

        $user->forceFill([
            'two_factor_secret'         => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at'   => null,
        ])->save();

        ActivityLogger::log(
            action: 'tfa_disabled',
            description: '2FA Google Authenticator dinonaktifkan',
            user: $user,
        );

        session()->forget(['2fa_pending_secret', '2fa_recovery_codes']);

        return redirect()->route('two-factor.show')
            ->with('status', 'Two-factor authentication telah dinonaktifkan.');
    }

    /** Generate ulang recovery codes. */
    public function regenerateCodes(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        abort_unless($user->hasTwoFactorEnabled(), 400);

        $codes = TwoFactorAuth::generateRecoveryCodes(8);
        $user->forceFill(['two_factor_recovery_codes' => $codes])->save();

        ActivityLogger::log(
            action: 'tfa_regen_codes',
            description: 'Membuat ulang recovery codes 2FA',
            user: $user,
        );

        session()->flash('2fa_recovery_codes', $codes);

        return redirect()->route('two-factor.show')
            ->with('status', 'Recovery codes baru berhasil dibuat. Simpan baik-baik!');
    }
}

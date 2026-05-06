<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\MathCaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        $captcha = MathCaptcha::generate();
        return view('auth.login', compact('captcha'));
    }

    /**
     * Handle login.
     *
     * Flow:
     *   1. Validasi captcha + password (di LoginRequest::authenticate())
     *   2. User sudah ter-Auth::login() di sini.
     *   3. Cek apakah user punya 2FA aktif:
     *      - Tidak → langsung lanjut ke dashboard.
     *      - Ya → logout sementara, simpan ID di session, redirect ke challenge.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            // Logout sementara — user belum benar-benar masuk sampai OTP terverifikasi
            Auth::logout();

            $request->session()->put('2fa.user_id',  $user->id);
            $request->session()->put('2fa.remember', $request->boolean('remember'));
            $request->session()->put('2fa.expires',  now()->addMinutes(5)->timestamp);

            return redirect()->route('two-factor.challenge');
        }

        // Tidak ada 2FA — lanjutkan login normal
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

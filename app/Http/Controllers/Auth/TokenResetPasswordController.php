<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TokenResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password-token', [
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string|size:6',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Check token validity
        if (!$user->reset_password_token || 
            !$user->reset_password_token_expires_at ||
            Carbon::now()->gt($user->reset_password_token_expires_at)) {
            return back()->withErrors(['token' => 'Token sudah kadaluarsa.']);
        }

        // Verify token
        if (!Hash::check($request->token, $user->reset_password_token)) {
            return back()->withErrors(['token' => 'Token tidak valid.']);
        }

        try {
            // Update password
            $user->update([
                'password' => Hash::make($request->password),
                'password_reset_at' => now(),
                'reset_password_token' => null,
                'reset_password_token_expires_at' => null,
            ]);

            return redirect()->route('login')
                ->with('status', 'Password berhasil direset. Silakan login dengan password baru.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mereset password.']);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\PasswordResetTokenMail;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function testEmail()
    {
        $user = User::first();
        
        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }
        
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addHours(1);
        
        try {
            Mail::to($user->email)->send(new PasswordResetTokenMail($user, $token, $expiresAt));
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent to ' . $user->email,
                'token' => $token,
                'expires_at' => $expiresAt->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
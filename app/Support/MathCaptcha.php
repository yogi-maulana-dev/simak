<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Session;

/**
 * Captcha matematika sederhana — dua angka acak + operator (+, -, ×).
 * Tidak butuh koneksi internet, tidak butuh GD library.
 * Disimpan di session dengan TTL 5 menit.
 */
class MathCaptcha
{
    private const SESSION_KEY = 'math_captcha';
    private const TTL_SECONDS = 300; // 5 menit

    /**
     * Generate soal baru. Return: ['question' => '5 + 3', 'token' => 'abc...'].
     */
    public static function generate(): array
    {
        $operators = ['+', '-', 'x'];
        $op        = $operators[array_rand($operators)];

        // Range yang ramah: hindari hasil negatif & angka besar
        switch ($op) {
            case '+':
                $a = random_int(1, 20);
                $b = random_int(1, 20);
                $answer = $a + $b;
                break;
            case '-':
                $a = random_int(10, 30);
                $b = random_int(1, $a); // Pastikan hasil non-negatif
                $answer = $a - $b;
                break;
            case 'x':
            default:
                $a = random_int(2, 9);
                $b = random_int(2, 9);
                $answer = $a * $b;
                break;
        }

        $token = bin2hex(random_bytes(8));

        Session::put(self::SESSION_KEY, [
            'token'      => $token,
            'answer'     => $answer,
            'expires_at' => now()->addSeconds(self::TTL_SECONDS)->timestamp,
        ]);

        return [
            'question' => "{$a} {$op} {$b}",
            'token'    => $token,
        ];
    }

    /**
     * Verifikasi jawaban user. Soal akan di-invalidate setelah dicek.
     */
    public static function verify(string $token, string|int|null $userAnswer): bool
    {
        $data = Session::get(self::SESSION_KEY);

        if (! $data || ! is_array($data)) {
            return false;
        }

        // Cek expiry
        if (($data['expires_at'] ?? 0) < now()->timestamp) {
            Session::forget(self::SESSION_KEY);
            return false;
        }

        // Token harus cocok
        if (! hash_equals((string) ($data['token'] ?? ''), $token)) {
            return false;
        }

        // Cek jawaban
        $expected = (int) $data['answer'];
        $given    = is_numeric($userAnswer) ? (int) $userAnswer : -99999;

        // Selalu invalidate setelah verifikasi (one-shot)
        Session::forget(self::SESSION_KEY);

        return $expected === $given;
    }

    /**
     * Hapus captcha dari session (mis. saat reset form).
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}

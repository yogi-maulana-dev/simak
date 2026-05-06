<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Implementasi TOTP (Time-based One-Time Password) sesuai RFC 6238.
 * Kompatibel 100% dengan Google Authenticator, Authy, Microsoft Authenticator,
 * 1Password, dll. Tidak memerlukan library eksternal.
 */
class TwoFactorAuth
{
    /** Panjang kode OTP (digit) — standard 6. */
    private const DIGITS = 6;

    /** Periode dalam detik — standard 30. */
    private const PERIOD = 30;

    /** Algoritma HMAC — standard SHA1. */
    private const ALGO = 'sha1';

    /** Toleransi window (jumlah periode sebelum/sesudah yang juga diterima). */
    private const WINDOW = 1;

    /**
     * Generate secret key acak (Base32, 16 karakter — 80 bit).
     */
    public static function generateSecret(int $length = 16): string
    {
        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Generate URL otpauth:// untuk dipindai oleh Authenticator app.
     *
     * @param  string $secret    Base32 secret
     * @param  string $accountName  Biasanya email user
     * @param  string $issuer    Nama aplikasi (akan ditampilkan di app)
     */
    public static function provisioningUri(string $secret, string $accountName, string $issuer): string
    {
        $label = rawurlencode("{$issuer}:{$accountName}");
        $params = http_build_query([
            'secret'    => $secret,
            'issuer'    => $issuer,
            'algorithm' => 'SHA1',
            'digits'    => self::DIGITS,
            'period'    => self::PERIOD,
        ]);
        return "otpauth://totp/{$label}?{$params}";
    }

    /**
     * Generate kode TOTP saat ini (untuk testing/debug).
     */
    public static function currentCode(string $secret, ?int $timestamp = null): string
    {
        $timestamp ??= time();
        $counter = (int) floor($timestamp / self::PERIOD);
        return self::hotp($secret, $counter);
    }

    /**
     * Verifikasi kode 6-digit yang diinput user terhadap secret.
     * Toleransi ±1 window (30 detik) untuk antisipasi clock drift.
     */
    public static function verify(string $secret, string $code): bool
    {
        $code = preg_replace('/\D/', '', $code);
        if (strlen($code) !== self::DIGITS) {
            return false;
        }

        $timestamp = time();
        $counter   = (int) floor($timestamp / self::PERIOD);

        for ($i = -self::WINDOW; $i <= self::WINDOW; $i++) {
            $candidate = self::hotp($secret, $counter + $i);
            if (hash_equals($candidate, $code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Hitung HOTP (HMAC-based One-Time Password) — algoritma inti TOTP.
     */
    private static function hotp(string $secret, int $counter): string
    {
        $key    = self::base32Decode($secret);
        // Counter sebagai 8-byte big-endian
        $binCounter = pack('N*', 0, $counter);
        $hash = hash_hmac(self::ALGO, $binCounter, $key, true);

        // Dynamic truncation
        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
        $code = (
            ((ord($hash[$offset])     & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8)  |
             (ord($hash[$offset + 3]) & 0xFF)
        );

        $code = $code % (10 ** self::DIGITS);
        return str_pad((string) $code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * Decode Base32 (RFC 4648) menjadi binary.
     */
    private static function base32Decode(string $input): string
    {
        $input = strtoupper(rtrim($input, '='));
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

        $bits = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $pos = strpos($alphabet, $input[$i]);
            if ($pos === false) {
                continue;
            }
            $bits .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $output = '';
        $len = strlen($bits);
        for ($i = 0; $i + 8 <= $len; $i += 8) {
            $output .= chr(bindec(substr($bits, $i, 8)));
        }
        return $output;
    }

    /**
     * Generate URL QR-code via API publik (api.qrserver.com — gratis, no-auth).
     * Cocok untuk dipasang di tag <img>.
     */
    public static function qrCodeUrl(string $otpauthUri, int $size = 200): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?'
            . http_build_query([
                'size'    => "{$size}x{$size}",
                'data'    => $otpauthUri,
                'margin'  => 0,
                'ecc'     => 'M',
            ]);
    }

    /**
     * Generate satu set recovery codes (8 kode, format XXXX-XXXX).
     * @return array<string>
     */
    public static function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $a = strtoupper(bin2hex(random_bytes(2)));
            $b = strtoupper(bin2hex(random_bytes(2)));
            $codes[] = "{$a}-{$b}";
        }
        return $codes;
    }
}

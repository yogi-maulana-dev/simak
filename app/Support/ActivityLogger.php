<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Catat satu aktivitas.
     *
     * @param  string                            $action       Kode aksi (login, file_upload, ...)
     * @param  string|null                       $description  Deskripsi bebas
     * @param  Model|null                        $subject      Objek terkait (File, Folder, ...)
     * @param  User|int|null                     $user         User pelaku (default: user yang login)
     * @param  array<string,mixed>               $metadata     Data tambahan
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?Model $subject = null,
        User|int|null $user = null,
        array $metadata = [],
    ): void {
        try {
            // Resolve user
            $userId    = null;
            $userName  = null;
            $userEmail = null;

            if ($user instanceof User) {
                $userId    = $user->id;
                $userName  = $user->name;
                $userEmail = $user->email;
            } elseif (is_int($user)) {
                $userId = $user;
                if ($u = User::find($user)) {
                    $userName  = $u->name;
                    $userEmail = $u->email;
                }
            } elseif (Auth::check()) {
                $u = Auth::user();
                $userId    = $u->id;
                $userName  = $u->name;
                $userEmail = $u->email;
            }

            $ip     = self::clientIp();
            $coords = self::resolveCoordinates($ip);

            ActivityLog::create([
                'user_id'      => $userId,
                'user_name'    => $userName,
                'user_email'   => $userEmail,
                'action'       => $action,
                'description'  => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->getKey(),
                'source'       => 'app',
                'ip_address'   => $ip,
                'latitude'     => $coords['lat'] ?? null,
                'longitude'    => $coords['lng'] ?? null,
                'user_agent'   => substr((string) Request::userAgent(), 0, 500),
                'metadata'     => empty($metadata) ? null : $metadata,
                'created_at'   => now(),
            ]);
        } catch (\Throwable $e) {
            // Jangan ganggu request user kalau logger error.
            \Log::warning('ActivityLogger gagal: '.$e->getMessage());
        }
    }

    private static function clientIp(): ?string
    {
        try {
            return Request::ip();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Resolusi koordinat dari IP menggunakan ip-api.com (gratis, di-cache 1 jam per IP).
     * Mengembalikan ['lat' => float, 'lng' => float] atau [] jika gagal/lokal.
     *
     * @return array{lat?: float, lng?: float}
     */
    private static function resolveCoordinates(?string $ip): array
    {
        if (! $ip || in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return [];
        }

        // Private/lokal range — tidak bisa di-geolookup
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return [];
        }

        return Cache::remember("geo_ip:{$ip}", 3600, function () use ($ip): array {
            try {
                $res = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,lat,lon',
                ]);

                if ($res->successful() && $res->json('status') === 'success') {
                    return [
                        'lat' => (float) $res->json('lat'),
                        'lng' => (float) $res->json('lon'),
                    ];
                }
            } catch (\Throwable) {
                // Jangan ganggu request jika geolookup gagal
            }

            return [];
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SharedLink;
use App\Support\ActivityLogger;

class SharedLinkObserver
{
    public function created(SharedLink $link): void
    {
        $type = $link->shareable_type === \App\Models\File::class ? 'file' : 'folder';
        $subject = $link->shareable;

        ActivityLogger::log(
            action: 'share_create',
            description: "Membuat link berbagi {$type} (izin: {$link->permission})",
            subject: $subject,
            metadata: [
                'token'      => $link->token,
                'permission' => $link->permission,
                'expires_at' => $link->expires_at?->toIso8601String(),
            ],
        );
    }

    public function deleted(SharedLink $link): void
    {
        $type = $link->shareable_type === \App\Models\File::class ? 'file' : 'folder';
        $subject = $link->shareable;

        ActivityLogger::log(
            action: 'share_revoke',
            description: "Mencabut link berbagi {$type}",
            subject: $subject,
            metadata: ['token' => $link->token],
        );
    }
}

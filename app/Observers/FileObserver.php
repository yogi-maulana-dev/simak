<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\File;
use App\Support\ActivityLogger;

class FileObserver
{
    public function created(File $file): void
    {
        ActivityLogger::log(
            action: 'file_upload',
            description: "Mengunggah file \"{$file->original_name}\"",
            subject: $file,
        );
    }

    public function updated(File $file): void
    {
        if ($file->wasChanged('original_name')) {
            $old = $file->getOriginal('original_name');
            ActivityLogger::log(
                action: 'file_rename',
                description: "Mengubah nama file \"{$old}\" → \"{$file->original_name}\"",
                subject: $file,
                metadata: ['from' => $old, 'to' => $file->original_name],
            );
        }
    }

    public function deleted(File $file): void
    {
        ActivityLogger::log(
            action: 'file_delete',
            description: "Menghapus file \"{$file->original_name}\"",
            subject: $file,
        );
    }
}

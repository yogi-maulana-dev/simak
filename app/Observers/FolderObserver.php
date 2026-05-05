<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Folder;
use App\Support\ActivityLogger;

class FolderObserver
{
    public function created(Folder $folder): void
    {
        ActivityLogger::log(
            action: 'folder_create',
            description: "Membuat folder \"{$folder->name}\"",
            subject: $folder,
        );
    }

    public function updated(Folder $folder): void
    {
        if ($folder->wasChanged('name')) {
            $old = $folder->getOriginal('name');
            ActivityLogger::log(
                action: 'folder_rename',
                description: "Mengubah nama folder \"{$old}\" → \"{$folder->name}\"",
                subject: $folder,
                metadata: ['from' => $old, 'to' => $folder->name],
            );
        }
    }

    public function deleted(Folder $folder): void
    {
        ActivityLogger::log(
            action: 'folder_delete',
            description: "Menghapus folder \"{$folder->name}\"",
            subject: $folder,
        );
    }
}

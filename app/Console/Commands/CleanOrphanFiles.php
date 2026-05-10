<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOrphanFiles extends Command
{
    protected $signature = 'files:clean-orphan';
    protected $description = 'Hapus record file yang fisiknya tidak ada';

    public function handle()
    {
        $deleted = 0;
        File::chunk(100, function ($files) use (&$deleted) {
            foreach ($files as $file) {
                if (empty($file->stored_name) || !Storage::disk($file->disk ?? 'public')->exists($file->storagePath())) {
                    $file->forceDelete();
                    $deleted++;
                }
            }
        });
        $this->info("Deleted $deleted orphan file records.");
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\File;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Storage;

class FilePreviewController extends Controller
{
    /** Halaman viewer (auth required) */
    public function view(int $id)
    {
        $file = File::whereNull('deleted_at')->findOrFail($id);
        $ext  = $file->extension();

        abort_unless(
            in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']),
            415,
            'Tipe file tidak didukung untuk pratinjau.'
        );

        $isOffice  = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
        $publicUrl = Storage::disk($file->disk)->url($file->storagePath());

        $viewerUrl = $isOffice
            ? 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($publicUrl)
            : route('file.stream', $id);   // PDF stream inline

        ActivityLogger::log(
            action: 'file_view',
            description: "Membuka pratinjau file \"{$file->original_name}\"",
            subject: $file,
        );

        return view('file.viewer', compact('file', 'viewerUrl', 'isOffice'));
    }

    /** Stream PDF inline tanpa Content-Disposition: attachment */
    public function stream(int $id)
    {
        $file = File::whereNull('deleted_at')->findOrFail($id);
        abort_unless($file->extension() === 'pdf', 415);

        $path = Storage::disk($file->disk)->path($file->storagePath());

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}

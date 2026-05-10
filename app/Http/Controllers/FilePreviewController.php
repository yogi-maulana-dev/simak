<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Storage;

class FilePreviewController extends Controller
{
    public function view(int $id)
    {
        $file = File::withTrashed()->findOrFail($id);

        // Cek keberadaan file fisik
        if (!$file->fileExists()) {
            $fileName = $file->original_name;
            $file->forceDelete();
            ActivityLogger::log(
                action: 'file_delete_orphan',
                description: "File \"{$fileName}\" tidak ditemukan di storage, record dihapus.",
            );
            return redirect()
                ->route('explorer')
                ->with('error', "File \"{$fileName}\" sudah tidak ada di server. Data telah dibersihkan.");
        }

        $ext = $file->extension();
        abort_unless(
            in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']),
            415,
            'Tipe file tidak didukung untuk pratinjau.'
        );

        $isOffice = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
        $publicUrl = Storage::disk($file->disk)->url($file->storagePath());

        if ($isOffice) {
            $viewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($publicUrl);
            return view('file.viewer', compact('file', 'viewerUrl', 'isOffice'));
        } else {
            // Untuk PDF, kita akan menggunakan PDF.js di view
            // Kirim URL file mentah ke view
            $viewerUrl = route('file.stream', $id);
            return view('file.viewer', compact('file', 'viewerUrl', 'isOffice'));
        }

        ActivityLogger::log(
            action: 'file_view',
            description: "Membuka pratinjau file \"{$file->original_name}\"",
            subject: $file,
        );
    }

    public function stream(int $id)
    {
        $file = File::findOrFail($id);
        abort_unless($file->extension() === 'pdf', 415);

        if (!$file->fileExists()) {
            $fileName = $file->original_name;
            $file->forceDelete();
            ActivityLogger::log(
                action: 'file_delete_orphan',
                description: "File PDF \"{$fileName}\" tidak ditemukan di storage, record dihapus.",
            );
            return redirect()
                ->route('explorer')
                ->with('error', "File \"{$fileName}\" sudah tidak ada di server. Data telah dibersihkan.");
        }

        $path = Storage::disk($file->disk)->path($file->storagePath());
        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    // public function viewSharedFile(string $token)
    // {
    //     $share = SharedLink::where('token', $token)
    //         ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
    //         ->firstOrFail();

    //     $shareable = $share->shareable;

    //     if ($shareable instanceof Folder) {
    //         return view('share.folder', [
    //     'file'       => $shareable,
    //     'token'      => $token,
    //     'permission' => $share->permission,
    //     'expiresAt'  => $share->expires_at,
    //     ]);
    //     } else {
    //         return view('share.file', [
    //     'file'       => $shareable,
    //     'token'      => $token,
    //     'permission' => $share->permission,
    //     'expiresAt'  => $share->expires_at,
    //     ]);
    //     }
    // }
}
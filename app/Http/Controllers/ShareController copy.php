<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\SharedLink;
use Illuminate\Support\Facades\Storage;

class ShareController extends Controller
{
public function show(string $token)
{
    $share = SharedLink::where('token', $token)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail();

    $shareable = $share->shareable; // bisa Folder atau File

    if ($shareable instanceof \App\Models\Folder) {
        return view('share.folder', [
        'folder'     => $shareable,   // <-- kunci 'folder'
        'token'      => $token,
        'permission' => $share->permission,
        'expiresAt'  => $share->expires_at,
        ]);
    } else {
       return view('share.file', [
    'file'       => $shareable,
    'token'      => $token,
    'permission' => $share->permission,
    'expiresAt'  => $share->expires_at,
]);
    }
}

    public function viewFile(string $token, int $fileId)
{
    $share = SharedLink::where('token', $token)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail();

    $file = File::findOrFail($fileId);

    if (!$file->fileExists()) {
        $file->forceDelete();
        abort(404, 'File tidak ditemukan.');
    }

    $ext = $file->extension();
    $isOffice = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
    $publicUrl = $file->url();

    if ($isOffice) {
        $viewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($publicUrl);
        return redirect()->away($viewerUrl);
    }

    if ($ext === 'pdf') {
        // Tampilkan via custom viewer, bukan redirect langsung ke file
        return view('share.pdf-viewer', [
            'token'  => $token,
            'fileId' => $fileId,
            'file'   => $file,
        ]);
    }

    return redirect()->route('file.stream', $file->id);
}

public function streamFile(string $token, int $fileId)
{
    $share = SharedLink::where('token', $token)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail();

    $file = File::findOrFail($fileId);
    abort_unless($file->extension() === 'pdf', 415);

    if (!$file->fileExists()) {
        $file->forceDelete();
        abort(404, 'File tidak ditemukan.');
    }

    $path = Storage::disk($file->disk ?? 'public')->path($file->storagePath());

    return response()->file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="protected.pdf"',
        'Cache-Control'       => 'no-store, no-cache, must-revalidate, private',
        'Pragma'              => 'no-cache',
        'X-Frame-Options'     => 'SAMEORIGIN',
        'Access-Control-Allow-Origin' => '*', // izinkan PDF.js fetch
    ]);
}

    public function download(string $token, int $fileId)
    {
        $share = SharedLink::where('token', $token)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->firstOrFail();

        if ($share->permission !== 'download') {
            abort(403, 'Anda tidak diizinkan mengunduh file ini.');
        }

        $file = File::findOrFail($fileId);

        if (!$file->fileExists()) {
            $file->forceDelete();
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(
            Storage::disk($file->disk ?? 'public')->path($file->storagePath()),
            $file->original_name
        );
    }
}
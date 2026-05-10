<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\SharedLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShareController extends Controller
{
   public function show(Request $request, string $token)
{
    $share = SharedLink::where('token', $token)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail();

    $shareable = $share->shareable; // Folder atau File

    if ($shareable instanceof \App\Models\Folder) {
        // Cek apakah ada parameter folder (sub-folder yang ingin dilihat)
        $subFolderId = $request->query('folder');
        $currentFolder = $shareable;

        if ($subFolderId) {
            $subFolder = Folder::find($subFolderId);
            // Validasi: subFolder harus ada dan merupakan turunan dari folder yang di-share
            if ($subFolder && $subFolder->isDescendantOf($shareable)) {
                $currentFolder = $subFolder;
            } else {
                abort(404, 'Sub-folder tidak ditemukan atau tidak memiliki akses.');
            }
        }

        return view('share.folder', [
            'folder'        => $currentFolder,
            'rootFolder'    => $shareable,
            'token'         => $token,
            'permission'    => $share->permission,
            'expiresAt'     => $share->expires_at,
        ]);
    } else {
        // Jika shareable adalah file
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

        // Pastikan file berada di dalam folder yang dishare atau turunannya
        $shareable = $share->shareable;
        if ($shareable instanceof Folder) {
            if (!$file->folder->isDescendantOf($shareable) && $file->folder_id !== $shareable->id) {
                abort(403);
            }
        } elseif ($shareable instanceof File && $shareable->id !== $file->id) {
            abort(403);
        }

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
        } elseif ($ext === 'pdf') {
            // Gunakan viewer PDF custom yang tidak bisa save
            return redirect()->route('share.pdf.viewer', ['token' => $token, 'fileId' => $fileId]);
        } else {
            return redirect()->route('share.stream', ['token' => $token, 'fileId' => $fileId]);
        }
    }

    public function streamFile(string $token, int $fileId)
    {
        $share = SharedLink::where('token', $token)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->firstOrFail();

        $file = File::findOrFail($fileId);

        // Validasi akses sama seperti di viewFile
        $shareable = $share->shareable;
        if ($shareable instanceof Folder) {
            if (!$file->folder->isDescendantOf($shareable) && $file->folder_id !== $shareable->id) {
                abort(403);
            }
        } elseif ($shareable instanceof File && $shareable->id !== $file->id) {
            abort(403);
        }

        if (!$file->fileExists()) {
            $file->forceDelete();
            abort(404);
        }

        $path = Storage::disk($file->disk ?? 'public')->path($file->storagePath());
        return response()->file($path, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
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

        // Validasi akses
        $shareable = $share->shareable;
        if ($shareable instanceof Folder) {
            if (!$file->folder->isDescendantOf($shareable) && $file->folder_id !== $shareable->id) {
                abort(403);
            }
        } elseif ($shareable instanceof File && $shareable->id !== $file->id) {
            abort(403);
        }

        if (!$file->fileExists()) {
            $file->forceDelete();
            abort(404);
        }

        return response()->download(
            Storage::disk($file->disk ?? 'public')->path($file->storagePath()),
            $file->original_name
        );
    }

    // PDF viewer custom (tanpa save)
    /**
 * Menampilkan viewer HTML untuk PDF (tanpa akses simpan)
 */
public function viewPdfViewer(string $token, int $fileId)
{
    $share = SharedLink::where('token', $token)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail();

    $file = File::findOrFail($fileId);
    
    if ($file->extension() !== 'pdf' || !$file->fileExists()) {
        $file->forceDelete();
        abort(404, 'File tidak ditemukan.');
    }

    return view('share.pdf_viewer', [
        'token' => $token,
        'fileId' => $fileId,
        'fileName' => $file->original_name,
    ]);
}

/**
 * Endpoint khusus untuk mengambil data PDF yang digunakan oleh PDF.js viewer
 * Dilindungi token dan hanya bisa diakses via AJAX dari halaman viewer
 */
public function streamPdfData(string $token, int $fileId)
{
    $share = SharedLink::where('token', $token)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail();

    $file = File::findOrFail($fileId);
    
    if ($file->extension() !== 'pdf' || !$file->fileExists()) {
        $file->forceDelete();
        abort(404);
    }

    $path = Storage::disk($file->disk ?? 'public')->path($file->storagePath());
    
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline',
    ]);
}
}
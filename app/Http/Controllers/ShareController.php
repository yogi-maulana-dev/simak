<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\SharedLink;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ShareController extends Controller
{
    public function show(Request $request, string $token)
    {
        $link = SharedLink::where('token', $token)
            ->with('shareable', 'creator')
            ->firstOrFail();

        if ($link->isExpired()) {
            abort(410, 'Link ini sudah kedaluwarsa.');
        }

        $link->incrementAccess();

        $shareable = $link->shareable;

        if ($shareable instanceof File) {
            return view('share.file', compact('link', 'shareable'));
        }

        if ($shareable instanceof Folder) {
            $rootFolder = $shareable;

            // Navigasi ke subfolder via ?sub={id}
            $subId         = $request->query('sub');
            $currentFolder = $rootFolder;

            if ($subId) {
                $candidate = Folder::find($subId);
                // Pastikan folder ini berada dalam pohon root yang dibagikan
                abort_unless(
                    $candidate && $this->isDescendantOf($candidate, $rootFolder),
                    403,
                    'Folder tidak ada dalam lingkup berbagi ini.'
                );
                $currentFolder = $candidate;
            }

            $subFolders  = $currentFolder->children()->get();
            $files       = $currentFolder->files()->get();
            $breadcrumbs = $this->buildBreadcrumbs($currentFolder, $rootFolder, $token);

            return view('share.folder', compact(
                'link', 'shareable', 'rootFolder',
                'currentFolder', 'subFolders', 'files', 'breadcrumbs'
            ));
        }

        abort(404);
    }

    /** Halaman viewer read-only (publik, via share link) */
    public function viewFile(string $token, int $fileId)
    {
        [$link, $file] = $this->resolveSharedFile($token, $fileId);

        $ext      = $file->extension();
        $isOffice = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);

        abort_unless(
            in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']),
            415,
            'Tipe file tidak didukung untuk pratinjau.'
        );

        $publicUrl = Storage::disk($file->disk)->url($file->storagePath());

        $viewerUrl = $isOffice
            ? 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($publicUrl)
            : route('share.stream', [$token, $fileId]);

        ActivityLogger::log(
            action: 'file_view',
            description: "Membuka pratinjau file \"{$file->original_name}\" via link berbagi",
            subject: $file,
            metadata: ['via_share_token' => $token],
        );

        return view('file.viewer', compact('file', 'viewerUrl', 'isOffice'));
    }

    /** Stream PDF inline (publik, via share link) — tanpa toolbar download */
    public function streamFile(string $token, int $fileId)
    {
        [$link, $file] = $this->resolveSharedFile($token, $fileId);

        abort_unless($file->extension() === 'pdf', 415);

        $path = Storage::disk($file->disk)->path($file->storagePath());

        return response()->file($path, [
            'Content-Type'           => 'application/pdf',
            'Content-Disposition'    => 'inline; filename="' . $file->original_name . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function download(string $token, int $fileId)
    {
        [$link, $file] = $this->resolveSharedFile($token, $fileId);

        abort_unless($link->permission === 'download', 403, 'Link ini tidak mengizinkan unduhan.');

        ActivityLogger::log(
            action: 'file_download',
            description: "Mengunduh file \"{$file->original_name}\" via link berbagi",
            subject: $file,
            metadata: ['via_share_token' => $token, 'shared_by' => $link->created_by],
        );

        return Storage::disk($file->disk)
            ->download($file->storagePath(), $file->original_name);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Validasi token & pastikan fileId boleh diakses via link tersebut.
     * Return [$link, $file].
     */
    private function resolveSharedFile(string $token, int $fileId): array
    {
        $link = SharedLink::where('token', $token)->firstOrFail();

        if ($link->isExpired()) {
            abort(410, 'Link ini sudah kedaluwarsa.');
        }

        $file = null;

        if ($link->shareable_type === File::class) {
            abort_unless($link->shareable_id === $fileId, 403);
            $file = File::findOrFail($fileId);
        } elseif ($link->shareable_type === Folder::class) {
            $rootFolder = Folder::findOrFail($link->shareable_id);
            $candidate  = File::findOrFail($fileId);

            $parentFolder = Folder::find($candidate->folder_id);
            abort_unless(
                $parentFolder && $this->isDescendantOf($parentFolder, $rootFolder, includeSelf: true),
                403,
                'File tidak ada dalam lingkup berbagi ini.'
            );

            $file = $candidate;
        }

        abort_unless($file, 404);

        return [$link, $file];
    }

    private function isDescendantOf(Folder $folder, Folder $root, bool $includeSelf = false): bool
    {
        if ($includeSelf && $folder->id === $root->id) {
            return true;
        }

        $current = $folder->parent_id ? Folder::find($folder->parent_id) : null;

        while ($current) {
            if ($current->id === $root->id) {
                return true;
            }
            $current = $current->parent_id ? Folder::find($current->parent_id) : null;
        }

        return false;
    }

    private function buildBreadcrumbs(Folder $current, Folder $root, string $token): array
    {
        $crumbs = [];
        $folder = $current;

        while ($folder) {
            if ($folder->id === $root->id) {
                array_unshift($crumbs, ['id' => $folder->id, 'name' => $folder->name, 'isRoot' => true]);
                break;
            }
            array_unshift($crumbs, ['id' => $folder->id, 'name' => $folder->name, 'isRoot' => false]);
            $folder = $folder->parent_id ? Folder::find($folder->parent_id) : null;
        }

        return $crumbs;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FolderViewController extends Controller
{
    public function show(Request $request, string $uuid)
    {
        $rootFolder = Folder::where('uuid', $uuid)->whereNull('deleted_at')->firstOrFail();

        $subId         = $request->query('sub');
        $currentFolder = $rootFolder;

        if ($subId) {
            $candidate = Folder::whereNull('deleted_at')->find($subId);
            abort_unless(
                $candidate && $this->isDescendantOf($candidate, $rootFolder),
                403,
                'Folder tidak ada dalam lingkup ini.'
            );
            $currentFolder = $candidate;
        }

        $subFolders  = $currentFolder->children()->get();
        $files       = $currentFolder->files()->get();
        $breadcrumbs = $this->buildBreadcrumbs($currentFolder, $rootFolder);

        return view('folder.view', compact(
            'rootFolder', 'currentFolder', 'subFolders', 'files', 'breadcrumbs'
        ));
    }

    public function preview(string $uuid, int $fileId)
    {
        $rootFolder = Folder::where('uuid', $uuid)->whereNull('deleted_at')->firstOrFail();
        $file       = File::whereNull('deleted_at')->findOrFail($fileId);

        // Pastikan file ada di dalam pohon folder ini
        $parentFolder = Folder::find($file->folder_id);
        abort_unless(
            $parentFolder && $this->isDescendantOf($parentFolder, $rootFolder, includeSelf: true),
            403
        );

        $ext = $file->extension();

        // DOCX/DOC → redirect ke Microsoft Office Online Viewer
        if (in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'])) {
            $publicUrl = Storage::disk($file->disk)->url($file->storagePath());
            $viewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($publicUrl);
            return redirect($viewerUrl);
        }

        // PDF → serve inline agar browser render langsung
        $path = Storage::disk($file->disk)->path($file->storagePath());
        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

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

    private function buildBreadcrumbs(Folder $current, Folder $root): array
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

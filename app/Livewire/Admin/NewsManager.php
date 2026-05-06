<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class NewsManager extends Component
{
    use WithFileUploads, WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'cat', history: true)]
    public string $filterCategory = '';

    // Form
    public bool $showFormModal = false;
    public ?int $editId        = null;

    public string $title       = '';
    public ?string $excerpt    = null;
    public string $content     = '';
    public ?string $category   = null;
    public bool $isPublished   = true;
    public ?string $publishedAt = null;

    /** @var \Illuminate\Http\UploadedFile|null */
    public $thumbnail = null;
    public ?string $existingThumbnailPath = null;

    // Delete confirm
    public bool $showDeleteModal = false;
    public ?int $deleteId        = null;
    public string $deleteName    = '';

    // Categories yang bisa dipilih
    public array $categories = [
        'pengumuman' => 'Pengumuman',
        'akademik'   => 'Akademik',
        'kegiatan'   => 'Kegiatan',
        'prestasi'   => 'Prestasi',
        'umum'       => 'Umum',
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCategory(): void { $this->resetPage(); }

    #[Computed]
    public function newsList()
    {
        return News::with('author')
            ->when($this->search, fn ($q) => $q->where(function ($w) {
                $w->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            }))
            ->when($this->filterCategory, fn ($q) => $q->where('category', $this->filterCategory))
            ->latest('id')
            ->paginate(10);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->publishedAt = now()->format('Y-m-d\TH:i');
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $n = News::findOrFail($id);
        $this->editId               = $n->id;
        $this->title                = $n->title;
        $this->excerpt              = $n->excerpt;
        $this->content              = $n->content;
        $this->category             = $n->category;
        $this->isPublished          = $n->is_published;
        $this->publishedAt          = $n->published_at?->format('Y-m-d\TH:i');
        $this->existingThumbnailPath = $n->thumbnail_path;
        $this->thumbnail            = null;
        $this->showFormModal        = true;
    }

    public function save(): void
    {
        $this->validate([
            'title'      => 'required|string|max:250',
            'excerpt'    => 'nullable|string|max:500',
            'content'    => 'required|string|min:20',
            'category'   => 'nullable|string|max:60',
            'publishedAt'=> 'nullable|date',
            'thumbnail'  => 'nullable|image|max:5120|mimes:jpg,jpeg,png,webp',
        ], [], [
            'title' => 'judul', 'content' => 'isi berita', 'thumbnail' => 'gambar',
        ]);

        // Handle thumbnail
        $thumbPath = $this->existingThumbnailPath;
        if ($this->thumbnail) {
            if ($this->existingThumbnailPath && Storage::disk('public')->exists($this->existingThumbnailPath)) {
                Storage::disk('public')->delete($this->existingThumbnailPath);
            }
            $thumbPath = $this->thumbnail->store('news', 'public');
        }

        $data = [
            'title'          => $this->title,
            'excerpt'        => $this->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($this->content), 200),
            'content'        => $this->content,
            'category'       => $this->category ?: null,
            'is_published'   => $this->isPublished,
            'published_at'   => $this->publishedAt ?: ($this->isPublished ? now() : null),
            'thumbnail_path' => $thumbPath,
        ];

        if ($this->editId) {
            $n = News::findOrFail($this->editId);
            // Regenerate slug kalau judul berubah
            if ($n->title !== $this->title) {
                $data['slug'] = News::makeUniqueSlug($this->title, $n->id);
            }
            $n->update($data);
            session()->flash('flash', 'Berita berhasil diperbarui.');
        } else {
            $data['author_id'] = auth()->id();
            $data['slug']      = News::makeUniqueSlug($this->title);
            News::create($data);
            session()->flash('flash', 'Berita berhasil ditambahkan.');
        }

        $this->showFormModal = false;
        $this->resetForm();
        unset($this->newsList);
    }

    public function togglePublish(int $id): void
    {
        $n = News::findOrFail($id);
        $n->update([
            'is_published' => ! $n->is_published,
            'published_at' => $n->is_published ? $n->published_at : ($n->published_at ?? now()),
        ]);
        unset($this->newsList);
    }

    public function confirmDelete(int $id): void
    {
        $n = News::findOrFail($id);
        $this->deleteId   = $id;
        $this->deleteName = $n->title;
        $this->showDeleteModal = true;
    }

    public function deleteNews(): void
    {
        $n = News::findOrFail($this->deleteId);
        if ($n->thumbnail_path && Storage::disk('public')->exists($n->thumbnail_path)) {
            Storage::disk('public')->delete($n->thumbnail_path);
        }
        $n->delete();

        $this->showDeleteModal = false;
        session()->flash('flash', 'Berita berhasil dihapus.');
        unset($this->newsList);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editId', 'title', 'excerpt', 'content', 'category',
            'isPublished', 'publishedAt', 'thumbnail', 'existingThumbnailPath',
        ]);
        $this->isPublished = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.news-manager')
            ->layout('layouts.app');
    }
}

<?php

namespace App\Livewire;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class NewsManager extends Component
{
    use WithFileUploads, WithPagination;

    // ── Modal ─────────────────────────────────────────────────
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public ?int $editingId   = null;
    public ?int $deletingId  = null;

    // ── Form fields ───────────────────────────────────────────
    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('nullable|string|max:100')]
    public string $category = '';

    #[Rule('nullable|image|max:3072')]
    public $thumbnail;

    #[Rule('nullable|string|max:500')]
    public string $excerpt = '';

    #[Rule('required|string')]
    public string $body = '';

    #[Rule('boolean')]
    public bool $is_published = false;

    #[Rule('boolean')]
    public bool $is_featured = false;

    public string $existingThumbnail = '';

    // ── Filter ────────────────────────────────────────────────
    public string $search         = '';
    public string $filterCategory = '';
    public string $filterStatus   = '';

    public array $categories = ['akademik', 'kegiatan', 'pengumuman', 'prestasi', 'umum'];

    public function updatedSearch(): void        { $this->resetPage(); }
    public function updatedFilterCategory(): void { $this->resetPage(); }
    public function updatedFilterStatus(): void   { $this->resetPage(); }

    // ── Open modal ────────────────────────────────────────────
    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $news = News::findOrFail($id);
        $this->editingId          = $id;
        $this->title              = $news->title;
        $this->category           = $news->category ?? '';
        $this->excerpt            = $news->excerpt ?? '';
        $this->body               = $news->body;
        $this->is_published       = $news->is_published;
        $this->is_featured        = $news->is_featured;
        $this->existingThumbnail  = $news->thumbnail_path ?? '';
        $this->showModal          = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId  = $id;
        $this->showConfirm = true;
    }

    // ── Save ──────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $thumbnailPath = $this->existingThumbnail;

        if ($this->thumbnail) {
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $thumbnailPath = $this->thumbnail->store('news/thumbnails', 'public');
        }

        $data = [
            'title'          => $this->title,
            'category'       => $this->category ?: null,
            'excerpt'        => $this->excerpt ?: null,
            'body'           => $this->body,
            'is_published'   => $this->is_published,
            'is_featured'    => $this->is_featured,
            'thumbnail_path' => $thumbnailPath ?: null,
            'published_at'   => $this->is_published ? now() : null,
        ];

        if ($this->editingId) {
            $news = News::findOrFail($this->editingId);
            // Jaga published_at jika sudah ada
            if ($news->published_at && $this->is_published) {
                unset($data['published_at']);
            }
            $news->update($data);
            session()->flash('toast', ['type' => 'success', 'msg' => 'Berita berhasil diperbarui.']);
        } else {
            News::create($data);
            session()->flash('toast', ['type' => 'success', 'msg' => 'Berita berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    // ── Delete ────────────────────────────────────────────────
    public function delete(): void
    {
        if (! $this->deletingId) return;

        $news = News::findOrFail($this->deletingId);

        if ($news->thumbnail_path && Storage::disk('public')->exists($news->thumbnail_path)) {
            Storage::disk('public')->delete($news->thumbnail_path);
        }

        $news->delete();

        $this->showConfirm = false;
        $this->deletingId  = null;

        session()->flash('toast', ['type' => 'success', 'msg' => 'Berita berhasil dihapus.']);
    }

    // ── Toggle ────────────────────────────────────────────────
    public function togglePublished(int $id): void
    {
        $news = News::findOrFail($id);
        $news->update([
            'is_published' => ! $news->is_published,
            'published_at' => ! $news->is_published ? now() : $news->published_at,
        ]);
    }

    public function toggleFeatured(int $id): void
    {
        $news = News::findOrFail($id);
        $news->update(['is_featured' => ! $news->is_featured]);
    }

    // ── Helpers ───────────────────────────────────────────────
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId         = null;
        $this->title             = '';
        $this->category          = '';
        $this->excerpt           = '';
        $this->body              = '';
        $this->is_published      = false;
        $this->is_featured       = false;
        $this->thumbnail         = null;
        $this->existingThumbnail = '';
        $this->resetValidation();
    }

    public function render()
    {
        $news = News::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->when($this->filterStatus === 'published', fn($q) => $q->where('is_published', true))
            ->when($this->filterStatus === 'draft', fn($q) => $q->where('is_published', false))
            ->latest()
            ->paginate(10);

        return view('livewire.news-manager', compact('news'))
            ->layout('layouts.app');
    }
}
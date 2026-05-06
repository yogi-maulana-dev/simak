<?php

namespace App\Livewire;

use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SliderManager extends Component
{
    use WithFileUploads, WithPagination;

    // ── Modal state ──────────────────────────────────────────
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public ?int $editingId   = null;
    public ?int $deletingId  = null;

    // ── Form fields ──────────────────────────────────────────
    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('nullable|string|max:255')]
    public string $subtitle = '';

    #[Rule('nullable|image|max:2048')]
    public $image;

    #[Rule('nullable|url|max:255')]
    public string $link_url = '';

    #[Rule('nullable|string|max:100')]
    public string $link_label = '';

    #[Rule('integer|min:0|max:999')]
    public int $order = 0;

    #[Rule('boolean')]
    public bool $is_active = true;

    public string $existingImage = '';

    // ── Filter / search ──────────────────────────────────────
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ── Open modal ───────────────────────────────────────────
    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $slider = Slider::findOrFail($id);
        $this->editingId     = $id;
        $this->title         = $slider->title;
        $this->subtitle      = $slider->subtitle ?? '';
        $this->link_url      = $slider->link_url ?? '';
        $this->link_label    = $slider->link_label ?? '';
        $this->order         = $slider->order;
        $this->is_active     = $slider->is_active;
        $this->existingImage = $slider->image_path ?? '';
        $this->showModal     = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId  = $id;
        $this->showConfirm = true;
    }

    // ── Save ─────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $imagePath = $this->existingImage;

        if ($this->image) {
            // Hapus gambar lama
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->image->store('sliders', 'public');
        }

        $data = [
            'title'      => $this->title,
            'subtitle'   => $this->subtitle ?: null,
            'link_url'   => $this->link_url ?: null,
            'link_label' => $this->link_label ?: null,
            'order'      => $this->order,
            'is_active'  => $this->is_active,
            'image_path' => $imagePath ?: null,
        ];

        if ($this->editingId) {
            Slider::findOrFail($this->editingId)->update($data);
            session()->flash('toast', ['type' => 'success', 'msg' => 'Slider berhasil diperbarui.']);
        } else {
            Slider::create($data);
            session()->flash('toast', ['type' => 'success', 'msg' => 'Slider berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    // ── Delete ────────────────────────────────────────────────
    public function delete(): void
    {
        if (! $this->deletingId) return;

        $slider = Slider::findOrFail($this->deletingId);

        if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
            Storage::disk('public')->delete($slider->image_path);
        }

        $slider->delete();

        $this->showConfirm = false;
        $this->deletingId  = null;

        session()->flash('toast', ['type' => 'success', 'msg' => 'Slider berhasil dihapus.']);
    }

    // ── Toggle active ─────────────────────────────────────────
    public function toggleActive(int $id): void
    {
        $slider = Slider::findOrFail($id);
        $slider->update(['is_active' => ! $slider->is_active]);
    }

    // ── Helpers ───────────────────────────────────────────────
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId     = null;
        $this->title         = '';
        $this->subtitle      = '';
        $this->link_url      = '';
        $this->link_label    = '';
        $this->order         = 0;
        $this->is_active     = true;
        $this->image         = null;
        $this->existingImage = '';
        $this->resetValidation();
    }

    public function render()
    {
        $sliders = Slider::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.slider-manager', compact('sliders'))
            ->layout('layouts.app');
    }
}
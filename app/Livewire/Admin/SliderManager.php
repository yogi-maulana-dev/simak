<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class SliderManager extends Component
{
    use WithFileUploads;

    // Modal
    public bool $showFormModal  = false;
    public ?int $editId         = null;

    #[Validate('required|string|max:200')]
    public string $title        = '';

    #[Validate('nullable|string|max:255')]
    public ?string $subtitle    = null;

    #[Validate('nullable|url|max:500')]
    public ?string $linkUrl     = null;

    #[Validate('nullable|string|max:100')]
    public ?string $linkLabel   = null;

    #[Validate('nullable|integer|min:0|max:9999')]
    public int $sortOrder       = 0;

    public bool $isActive       = true;

    /** @var \Illuminate\Http\UploadedFile|null */
    public $image               = null;

    public ?string $existingImagePath = null; // saat edit

    // Confirm delete
    public bool $showDeleteModal = false;
    public ?int $deleteId        = null;
    public string $deleteName    = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    #[Computed]
    public function sliders()
    {
        return Slider::orderBy('sort_order')->orderBy('id')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $s = Slider::findOrFail($id);
        $this->editId            = $s->id;
        $this->title             = $s->title;
        $this->subtitle          = $s->subtitle;
        $this->linkUrl           = $s->link_url;
        $this->linkLabel         = $s->link_label;
        $this->sortOrder         = $s->sort_order;
        $this->isActive          = $s->is_active;
        $this->existingImagePath = $s->image_path;
        $this->image             = null;
        $this->showFormModal     = true;
    }

    public function save(): void
    {
        $rules = [
            'title'    => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:255',
            'linkUrl'  => 'nullable|url|max:500',
            'linkLabel'=> 'nullable|string|max:100',
            'sortOrder'=> 'nullable|integer|min:0|max:9999',
        ];

        // Image wajib hanya kalau create
        if (! $this->editId) {
            $rules['image'] = 'required|image|max:5120|mimes:jpg,jpeg,png,webp';
        } else {
            $rules['image'] = 'nullable|image|max:5120|mimes:jpg,jpeg,png,webp';
        }

        $this->validate($rules, [], [
            'image'    => 'gambar',
            'title'    => 'judul',
            'sortOrder'=> 'urutan',
            'linkUrl'  => 'URL link',
        ]);

        // Upload image baru jika ada
        $imagePath = $this->existingImagePath;
        if ($this->image) {
            // Hapus image lama saat edit
            if ($this->existingImagePath && Storage::disk('public')->exists($this->existingImagePath)) {
                Storage::disk('public')->delete($this->existingImagePath);
            }
            $imagePath = $this->image->store('sliders', 'public');
        }

        $data = [
            'title'      => $this->title,
            'subtitle'   => $this->subtitle ?: null,
            'link_url'   => $this->linkUrl ?: null,
            'link_label' => $this->linkLabel ?: null,
            'sort_order' => $this->sortOrder,
            'is_active'  => $this->isActive,
            'image_path' => $imagePath,
        ];

        if ($this->editId) {
            Slider::findOrFail($this->editId)->update($data);
            session()->flash('flash', 'Slider berhasil diperbarui.');
        } else {
            $data['created_by'] = auth()->id();
            Slider::create($data);
            session()->flash('flash', 'Slider berhasil ditambahkan.');
        }

        $this->showFormModal = false;
        $this->resetForm();
        unset($this->sliders);
    }

    public function toggleActive(int $id): void
    {
        $s = Slider::findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
        unset($this->sliders);
    }

    public function moveUp(int $id): void
    {
        $s = Slider::findOrFail($id);
        $prev = Slider::where('sort_order', '<', $s->sort_order)
            ->orderByDesc('sort_order')->first();
        if ($prev) {
            [$s->sort_order, $prev->sort_order] = [$prev->sort_order, $s->sort_order];
            $s->save();
            $prev->save();
        }
        unset($this->sliders);
    }

    public function moveDown(int $id): void
    {
        $s = Slider::findOrFail($id);
        $next = Slider::where('sort_order', '>', $s->sort_order)
            ->orderBy('sort_order')->first();
        if ($next) {
            [$s->sort_order, $next->sort_order] = [$next->sort_order, $s->sort_order];
            $s->save();
            $next->save();
        }
        unset($this->sliders);
    }

    public function confirmDelete(int $id): void
    {
        $s = Slider::findOrFail($id);
        $this->deleteId   = $id;
        $this->deleteName = $s->title;
        $this->showDeleteModal = true;
    }

    public function deleteSlider(): void
    {
        $s = Slider::findOrFail($this->deleteId);
        if ($s->image_path && Storage::disk('public')->exists($s->image_path)) {
            Storage::disk('public')->delete($s->image_path);
        }
        $s->delete();

        $this->showDeleteModal = false;
        $this->deleteId   = null;
        session()->flash('flash', 'Slider berhasil dihapus.');
        unset($this->sliders);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editId', 'title', 'subtitle', 'linkUrl', 'linkLabel',
            'sortOrder', 'isActive', 'image', 'existingImagePath',
        ]);
        $this->isActive = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.slider-manager')
            ->layout('layouts.app');
    }
}

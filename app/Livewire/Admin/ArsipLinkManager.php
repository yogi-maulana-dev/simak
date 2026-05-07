<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Folder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ArsipLinkManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    public bool $showOnlyRoot = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingShowOnlyRoot(): void { $this->resetPage(); }

    #[Computed]
    public function folders()
    {
        return Folder::withCount(['children', 'files'])
            ->when($this->showOnlyRoot, fn ($q) => $q->whereNull('parent_id'))
            ->when($this->search, fn ($q) => $q->where(function ($w) {
                $w->where('name', 'like', "%{$this->search}%")
                  ->orWhere('kode_lamp', 'like', "%{$this->search}%");
            }))
            ->with('parent')
            ->orderByRaw('parent_id IS NULL DESC')
            ->orderBy('name')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.arsip-link-manager')
            ->layout('layouts.app');
    }
}

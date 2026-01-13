<?php

namespace App\Livewire\Arsip;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Arsip;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public string $search = '';
    public int $perPage = 10;

    public function render()
    {
        return view('livewire.arsip.index', [
            'arsips' => Arsip::visibleFor(auth()->user())
                ->where('judul', 'like', "%{$this->search}%")
                ->latest()
                ->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}


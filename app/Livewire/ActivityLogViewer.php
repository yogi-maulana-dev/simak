<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogViewer extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'action', history: true)]
    public string $filterAction = '';

    #[Url(as: 'user', history: true)]
    public string $filterUser = '';

    #[Url(as: 'src', history: true)]
    public string $filterSource = '';

    #[Url(as: 'date', history: true)]
    public string $filterDate = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function updating($name): void
    {
        if (in_array($name, ['search', 'filterAction', 'filterUser', 'filterSource', 'filterDate'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterAction', 'filterUser', 'filterSource', 'filterDate']);
        $this->resetPage();
    }

    #[Computed]
    public function logs()
    {
        return ActivityLog::query()
            ->with('user')
            ->when($this->search !== '', fn ($q) => $q->where(function ($w) {
                $w->where('description', 'like', "%{$this->search}%")
                  ->orWhere('user_name', 'like', "%{$this->search}%")
                  ->orWhere('user_email', 'like', "%{$this->search}%")
                  ->orWhere('action', 'like', "%{$this->search}%");
            }))
            ->when($this->filterAction !== '', function ($q) {
                $q->where('action', 'like', $this->filterAction.'%');
            })
            ->when($this->filterUser !== '', fn ($q) => $q->where('user_id', $this->filterUser))
            ->when($this->filterSource !== '', fn ($q) => $q->where('source', $this->filterSource))
            ->when($this->filterDate !== '', fn ($q) => $q->whereDate('created_at', $this->filterDate))
            ->latest('created_at')
            ->paginate(25);
    }

    #[Computed]
    public function userOptions()
    {
        return User::orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function actionGroups(): array
    {
        return [
            ''        => 'Semua aksi',
            'login'   => 'Login / Logout',
            'file'    => 'Aktivitas File',
            'folder'  => 'Aktivitas Folder',
            'share'   => 'Aktivitas Berbagi',
            'user'    => 'Manajemen User',
        ];
    }

    #[Computed]
    public function todayCount(): int
    {
        return ActivityLog::whereDate('created_at', today())->count();
    }

    #[Computed]
    public function loginFailedCount(): int
    {
        return ActivityLog::where('action', 'login_failed')
            ->where('created_at', '>=', now()->subDay())
            ->count();
    }

    public function render()
    {
        return view('livewire.activity-log-viewer')
            ->layout('layouts.app');
    }
}

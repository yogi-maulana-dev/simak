{{-- resources/views/components/action-buttons.blade.php --}}
@props(['arsip'])

<div class="flex space-x-2">
   
                                                    <a href="{{ route('arsip.edit', $arsip->id) }}" 
                                                       class="text-green-600 hover:text-green-900" 
                                                       title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    {{-- Delete Button --}}
@can('delete', $arsip)
    <button type="button"
            wire:click="confirmDelete('{{ $arsip->id }}')"
            onclick="console.log('Delete clicked for UUID:', '{{ $arsip->id }}', 'Type:', typeof '{{ $arsip->id }}')"
            class="text-red-600 hover:text-red-900" 
            title="Hapus">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>
@endcan

    
{{-- Delete Button --}}
<button type="button"
        wire:click="testMethod({{ json_encode($arsip->id) }}, '{{ addslashes($arsip->judul) }}')"
        onclick="console.log('Test clicked for:', '{{ $arsip->judul }}')"
        class="text-red-600 hover:text-red-900">
    TEST CLICK {{$arsip->id}}
</button>

    <div class="mt-4 p-4 bg-yellow-100">
    <p>Debug Info:</p>
    <p>Current Arsip ID: {{ $arsip->id }}</p>
    <p>User Can Delete: @can('delete', $arsip) YES @else NO @endcan</p>
</div>

</div>
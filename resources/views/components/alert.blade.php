{{-- resources/views/components/alert.blade.php --}}
@props([
    'type' => 'info', // 'success', 'error', 'warning', 'info'
    'dismissible' => false,
])

@php
    $classes = [
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'error' => 'bg-red-50 border-red-400 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-400 text-blue-800',
    ][$type] ?? 'bg-blue-50 border-blue-400 text-blue-800';

    $icons = [
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z',
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ][$type] ?? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
@endphp

<div {{ $attributes->merge(['class' => 'rounded-md border-l-4 p-4 ' . $classes]) }}
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-90"
     role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons }}" />
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm font-medium">
                {{ $slot }}
            </p>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false"
                            type="button"
                            class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2
                                   @if($type === 'success') text-green-500 hover:bg-green-100 focus:ring-green-600
                                   @elseif($type === 'error') text-red-500 hover:bg-red-100 focus:ring-red-600
                                   @elseif($type === 'warning') text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600
                                   @else text-blue-500 hover:bg-blue-100 focus:ring-blue-600 @endif">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
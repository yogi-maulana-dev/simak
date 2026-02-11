<a {{ $attributes->merge([
    'class' => 'inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold uppercase tracking-widest
                bg-green-600 text-white hover:bg-green-700
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition'
]) }}>
    {{ $slot }}
</a>

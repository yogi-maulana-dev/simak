<x-guest-layout>
    {{-- Heading --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#0d4a2a] to-[#1a6b3e]
                        flex items-center justify-center shadow-md shadow-[#0d4a2a]/20">
                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/>
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                Atur Password Baru
            </h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Buatlah password yang kuat dan mudah Anda ingat. Minimal 8 karakter.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Email
            </label>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" name="email" type="email" required autofocus autocomplete="username"
                       value="{{ old('email', $request->email) }}"
                       class="w-full pl-9 pr-3 py-2.5 text-sm
                              bg-gray-50 dark:bg-gray-800/50 cursor-not-allowed
                              border border-gray-300 dark:border-gray-700 rounded-xl
                              text-gray-700 dark:text-gray-300
                              focus:outline-none"
                       readonly>
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password baru --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Password Baru
            </label>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" name="password" required autocomplete="new-password"
                       :type="show ? 'text' : 'password'"
                       placeholder="Minimal 8 karakter"
                       class="w-full pl-9 pr-10 py-2.5 text-sm
                              bg-white dark:bg-gray-800
                              border border-gray-300 dark:border-gray-700 rounded-xl
                              text-gray-900 dark:text-gray-100 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                              dark:focus:ring-emerald-500/30 dark:focus:border-emerald-500
                              transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Konfirmasi Password
            </label>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       placeholder="Ulangi password baru"
                       class="w-full pl-9 pr-3 py-2.5 text-sm
                              bg-white dark:bg-gray-800
                              border border-gray-300 dark:border-gray-700 rounded-xl
                              text-gray-900 dark:text-gray-100 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                              dark:focus:ring-emerald-500/30 dark:focus:border-emerald-500
                              transition">
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl
                       text-sm font-semibold text-white
                       bg-gradient-to-r from-[#0a3d22] via-[#0d4a2a] to-[#1a6b3e]
                       hover:from-[#082e1a] hover:via-[#0a3d22] hover:to-[#155831]
                       active:scale-[0.99]
                       shadow-md shadow-[#0d4a2a]/20
                       transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Atur Ulang Password
        </button>
    </form>
</x-guest-layout>

<x-guest-layout>
    {{-- Heading --}}
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
            Selamat Datang Kembali
        </h2>
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">
            Silakan masuk untuk mengakses sistem akademik.
        </p>
    </div>

    {{-- Status banner --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-300 flex items-start gap-2">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Email
            </label>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" name="email" type="email" required autofocus autocomplete="username"
                       value="{{ old('email') }}"
                       placeholder="nama@uml.ac.id"
                       class="w-full pl-9 pr-3 py-2.5 text-sm
                              bg-white dark:bg-gray-800
                              border border-gray-300 dark:border-gray-700 rounded-xl
                              text-gray-900 dark:text-gray-100 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                              dark:focus:ring-emerald-500/30 dark:focus:border-emerald-500
                              transition">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-[#0d4a2a] dark:text-emerald-400 hover:underline font-medium">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" name="password" required autocomplete="current-password"
                       :type="show ? 'text' : 'password'"
                       placeholder="Masukkan password"
                       class="w-full pl-9 pr-10 py-2.5 text-sm
                              bg-white dark:bg-gray-800
                              border border-gray-300 dark:border-gray-700 rounded-xl
                              text-gray-900 dark:text-gray-100 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                              dark:focus:ring-emerald-500/30 dark:focus:border-emerald-500
                              transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                        :title="show ? 'Sembunyikan' : 'Tampilkan'">
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
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Captcha matematika --}}
        <div>
            <label for="captcha_answer" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Verifikasi Keamanan
            </label>
            <div class="flex items-stretch gap-2">
                {{-- Soal --}}
                <div class="flex-shrink-0 flex items-center gap-2 px-4 rounded-xl
                            bg-gradient-to-br from-[#0d4a2a] to-[#1a6b3e]
                            border border-[#0a3d22] shadow-sm select-none"
                     style="font-family: 'Courier New', monospace;">
                    <span class="text-base font-bold text-yellow-300 tracking-widest">{{ $captcha['question'] }}</span>
                    <span class="text-base font-bold text-green-200/60">=</span>
                    <span class="text-base font-bold text-yellow-300">?</span>
                </div>

                {{-- Input jawaban --}}
                <input id="captcha_answer" name="captcha_answer" type="number"
                       inputmode="numeric" autocomplete="off" required
                       placeholder="Jawaban"
                       class="flex-1 min-w-0 px-3 py-2.5 text-sm
                              bg-white dark:bg-gray-800
                              border border-gray-300 dark:border-gray-700 rounded-xl
                              text-gray-900 dark:text-gray-100 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                              dark:focus:ring-emerald-500/30 dark:focus:border-emerald-500
                              transition">

                {{-- Refresh --}}
                <a href="{{ route('login') }}" title="Ganti soal"
                   class="flex-shrink-0 flex items-center justify-center w-11 rounded-xl
                          border border-gray-300 dark:border-gray-700
                          text-gray-500 dark:text-gray-400
                          hover:bg-[#0d4a2a]/5 hover:text-[#0d4a2a]
                          dark:hover:bg-emerald-500/10 dark:hover:text-emerald-400
                          transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </a>
            </div>
            <input type="hidden" name="captcha_token" value="{{ $captcha['token'] }}">
            <p class="mt-1.5 text-[11px] text-gray-400 dark:text-gray-500">
                Tanda <code class="px-1 rounded bg-gray-100 dark:bg-gray-800">x</code> berarti perkalian
            </p>
            @error('captcha_answer')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800
                              text-[#0d4a2a] dark:text-emerald-500
                              focus:ring-[#0d4a2a]/30 dark:focus:ring-emerald-500/30">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
            </label>
        </div>

        {{-- Submit button --}}
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl
                       text-sm font-semibold text-white
                       bg-gradient-to-r from-[#0a3d22] via-[#0d4a2a] to-[#1a6b3e]
                       hover:from-[#082e1a] hover:via-[#0a3d22] hover:to-[#155831]
                       active:scale-[0.99]
                       shadow-md shadow-[#0d4a2a]/20
                       transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk
        </button>

    </form>

    {{-- Footer info --}}
    <div class="mt-8 text-center">
        <p class="text-xs text-gray-400 dark:text-gray-500">
            Akun bermasalah? Hubungi
            <span class="font-medium text-[#0d4a2a] dark:text-emerald-400">administrator</span>.
        </p>
    </div>
</x-guest-layout>

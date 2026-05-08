<x-guest-layout>
    {{-- Heading --}}
    <div class="mb-6">
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-[#0d4a2a] dark:hover:text-emerald-400 mb-4 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke halaman masuk
        </a>

        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#0d4a2a] to-[#1a6b3e]
                        flex items-center justify-center shadow-md shadow-[#0d4a2a]/20">
                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                Lupa Password?
            </h2>
        </div>

        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Masukkan alamat email Anda. Kami akan mengirimkan tautan untuk
            mengatur ulang password ke email tersebut.
        </p>
    </div>

    {{-- Status (link sukses dikirim) --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 flex items-start gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-emerald-700 dark:text-emerald-300">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Email Terdaftar
            </label>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" name="email" type="email" required autofocus
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Kirim Tautan Reset
        </button>
    </form>

    {{-- Info bantuan --}}
    <div class="mt-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
        <div class="flex items-start gap-2">
            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
                Tidak menerima email? Cek folder spam atau hubungi
                <strong>administrator</strong> untuk bantuan reset password manual.
            </p>
        </div>
    </div>
</x-guest-layout>

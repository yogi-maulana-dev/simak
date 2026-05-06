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
                Verifikasi Dua Langkah
            </h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Akun Anda dilindungi 2FA. Buka aplikasi <strong>Google Authenticator</strong>
            (atau yang serupa) di HP Anda lalu masukkan kode 6 angka.
        </p>
        @if(! empty($maskedEmail))
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                Akun: <span class="font-mono font-semibold text-gray-600 dark:text-gray-300">{{ $maskedEmail }}</span>
            </p>
        @endif
    </div>

    <form method="POST" action="{{ route('two-factor.challenge.verify') }}"
          x-data="{ useRecovery: false }"
          class="space-y-5">
        @csrf

        {{-- Mode TOTP --}}
        <div x-show="!useRecovery" class="space-y-4">
            <div>
                <label for="code" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                    Kode 6-Digit
                </label>
                <input id="code" name="code" type="text" inputmode="numeric"
                       pattern="[0-9]*" maxlength="6" required autofocus autocomplete="one-time-code"
                       placeholder="000000"
                       x-bind:disabled="useRecovery"
                       class="w-full px-4 py-4 text-center text-2xl font-bold tracking-[0.5em]
                              bg-white dark:bg-gray-800
                              border-2 border-gray-200 dark:border-gray-700 rounded-xl
                              text-[#0d4a2a] dark:text-emerald-400
                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                              dark:focus:ring-emerald-500/30 dark:focus:border-emerald-500
                              transition">
                @error('code')
                    <p class="mt-2 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <p class="text-xs text-center text-gray-400 dark:text-gray-500">
                Kode berubah setiap 30 detik
            </p>
        </div>

        {{-- Mode Recovery --}}
        <div x-show="useRecovery" x-cloak class="space-y-4">
            <div>
                <label for="code_recovery" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                    Recovery Code
                </label>
                <input id="code_recovery" name="code" type="text" maxlength="9"
                       autocomplete="off"
                       placeholder="XXXX-XXXX"
                       x-bind:disabled="!useRecovery"
                       class="w-full px-4 py-3 text-center text-lg font-mono font-bold tracking-wider uppercase
                              bg-white dark:bg-gray-800
                              border-2 border-amber-300 dark:border-amber-700 rounded-xl
                              text-amber-700 dark:text-amber-400
                              focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500
                              transition">
                <p class="mt-1.5 text-xs text-gray-400">
                    Salah satu dari 8 kode pemulihan yang Anda simpan saat aktivasi 2FA. Hanya bisa dipakai sekali.
                </p>
            </div>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Verifikasi
        </button>

        {{-- Toggle mode --}}
        <div class="text-center">
            <button type="button" @click="useRecovery = !useRecovery"
                    class="text-xs font-medium text-[#0d4a2a] dark:text-emerald-400 hover:underline">
                <span x-show="!useRecovery">Tidak bisa akses HP? Gunakan recovery code</span>
                <span x-show="useRecovery" x-cloak>← Kembali ke kode authenticator</span>
            </button>
        </div>

        {{-- Cancel link --}}
        <div class="text-center pt-2">
            <a href="{{ route('two-factor.challenge.cancel') }}"
               class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                Batal &amp; kembali ke login
            </a>
        </div>
    </form>

    {{-- Info bantuan --}}
    <div class="mt-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
        <div class="flex items-start gap-2">
            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                Kehilangan akses ke HP <em>dan</em> recovery codes? Hubungi <strong>administrator</strong>
                untuk reset 2FA secara manual.
            </p>
        </div>
    </div>
</x-guest-layout>

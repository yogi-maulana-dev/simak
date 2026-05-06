<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white">
            Keamanan Akun — Two-Factor Authentication
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Flash status --}}
            @if (session('status'))
                <div class="px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-emerald-700 dark:text-emerald-300">{{ session('status') }}</p>
                </div>
            @endif

            {{-- ── Status Card ── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $enabled ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-gray-100 dark:bg-gray-700' }}">
                        <svg class="w-6 h-6 {{ $enabled ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Google Authenticator</h3>
                            @if ($enabled)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                             bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                             bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                    Belum Aktif
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Tambahkan lapisan keamanan ekstra dengan kode 6-digit dari aplikasi authenticator
                            (Google Authenticator, Authy, Microsoft Authenticator, dll).
                        </p>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 BELUM AKTIF — Setup wizard
            ══════════════════════════════════════════════════════════ --}}
            @if (! $enabled && $pendingSecret)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

                    {{-- Step 1: Install app --}}
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-[#0d4a2a] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Pasang aplikasi Authenticator</h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Unduh salah satu aplikasi gratis berikut di smartphone Anda:
                                </p>
                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach ([
                                        'Google Authenticator',
                                        'Microsoft Authenticator',
                                        'Authy',
                                    ] as $app)
                                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                            <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[11px] font-medium text-gray-700 dark:text-gray-300 truncate">{{ $app }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Scan QR --}}
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-7 h-7 rounded-full bg-[#0d4a2a] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Pindai kode QR</h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Buka aplikasi authenticator → tekan tombol <strong>+</strong> → pilih
                                    <strong>"Scan QR Code"</strong> → arahkan kamera ke kode di bawah.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-5 items-center sm:items-stretch">
                            {{-- QR --}}
                            <div class="flex-shrink-0 p-4 bg-white border-2 border-[#0d4a2a]/20 rounded-2xl shadow-sm">
                                <img src="{{ $qrUrl }}" alt="QR Code 2FA"
                                     class="w-[220px] h-[220px] block"
                                     loading="lazy">
                            </div>

                            {{-- Manual entry --}}
                            <div class="flex-1 min-w-0 space-y-3">
                                <div class="px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                    <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                        Tidak bisa scan? Input manual:
                                    </p>
                                    <div x-data="{ copied: false }" class="flex items-center gap-2">
                                        <code class="flex-1 text-sm font-mono font-bold text-[#0d4a2a] dark:text-emerald-400 select-all break-all tracking-wider">
                                            {{ chunk_split($pendingSecret, 4, ' ') }}
                                        </code>
                                        <button type="button"
                                                x-on:click="navigator.clipboard.writeText('{{ $pendingSecret }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-[#0d4a2a] hover:bg-[#0d4a2a]/5 transition-colors"
                                                :title="copied ? 'Tersalin!' : 'Salin'">
                                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                            <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="px-4 py-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                                    <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                        <strong>Penting:</strong> Pastikan jam HP Anda akurat (otomatis dari jaringan).
                                        Kode TOTP berbasis waktu — selisih lebih dari 30 detik akan menyebabkan kode salah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Verify code --}}
                    <div class="p-6">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-7 h-7 rounded-full bg-[#0d4a2a] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Konfirmasi kode</h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Masukkan kode 6-digit yang muncul di aplikasi authenticator untuk mengaktifkan 2FA.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('two-factor.confirm') }}" class="ml-10 space-y-3">
                            @csrf
                            <div class="flex gap-3">
                                <input id="code" name="code" type="text" inputmode="numeric"
                                       pattern="[0-9]*" maxlength="6" required autofocus
                                       autocomplete="one-time-code"
                                       placeholder="000000"
                                       class="flex-1 sm:flex-none sm:w-44 px-4 py-3 text-center text-xl font-bold tracking-[0.4em]
                                              bg-white dark:bg-gray-900
                                              border-2 border-gray-200 dark:border-gray-700 rounded-xl
                                              text-[#0d4a2a] dark:text-emerald-400
                                              focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]
                                              transition">
                                <button type="submit"
                                        class="px-5 py-3 rounded-xl text-sm font-semibold text-white
                                               bg-gradient-to-r from-[#0a3d22] to-[#1a6b3e]
                                               hover:from-[#082e1a] hover:to-[#155831]
                                               shadow-md shadow-[#0d4a2a]/20 transition-all">
                                    Aktifkan 2FA
                                </button>
                            </div>
                            @error('code')
                                <p class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </form>
                    </div>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 RECOVERY CODES (tampil sekali setelah enable / regenerate)
            ══════════════════════════════════════════════════════════ --}}
            @if (! empty($recoveryCodes))
                <div class="bg-amber-50 dark:bg-amber-900/20 border-2 border-amber-300 dark:border-amber-800 rounded-2xl p-5">
                    <div class="flex items-start gap-3 mb-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900 dark:text-amber-200">Recovery Codes — Simpan Sekarang!</h4>
                            <p class="mt-1 text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                Kode pemulihan ini bisa dipakai untuk login jika HP Anda hilang/rusak.
                                <strong>Setiap kode hanya bisa dipakai sekali</strong> dan kode ini <strong>TIDAK akan ditampilkan lagi</strong>.
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3 font-mono">
                        @foreach ($recoveryCodes as $code)
                            <code class="px-3 py-2 bg-white dark:bg-gray-800 rounded-lg text-xs font-bold text-center text-gray-800 dark:text-gray-200 border border-amber-200 dark:border-amber-800/50 select-all">
                                {{ $code }}
                            </code>
                        @endforeach
                    </div>
                    <button type="button" onclick="window.print()"
                            class="text-xs font-semibold text-amber-700 dark:text-amber-300 hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak / Simpan
                    </button>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 SUDAH AKTIF — Disable / Regenerate
            ══════════════════════════════════════════════════════════ --}}
            @if ($enabled)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Kelola 2FA</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">
                        Untuk semua aksi keamanan ini, Anda perlu memasukkan password akun saat ini.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        {{-- Regenerate codes --}}
                        <form method="POST" action="{{ route('two-factor.regenerate-codes') }}"
                              class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            @csrf
                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white">Buat Ulang Recovery Codes</h5>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                Membatalkan kode lama, membuat 8 kode baru.
                            </p>
                            <input name="password" type="password" required
                                   placeholder="Password saat ini"
                                   class="w-full mb-2 px-3 py-2 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                            <button type="submit"
                                    class="w-full px-3 py-2 rounded-lg text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                                Generate Ulang
                            </button>
                            @if ($errors->has('password') && old('action') === 'regen')
                                <p class="mt-2 text-xs text-red-500">{{ $errors->first('password') }}</p>
                            @endif
                        </form>

                        {{-- Disable 2FA --}}
                        <form method="POST" action="{{ route('two-factor.disable') }}"
                              onsubmit="return confirm('Yakin nonaktifkan 2FA? Akun Anda akan kembali hanya bergantung pada password.')"
                              class="rounded-xl border border-red-200 dark:border-red-900/50 p-4 bg-red-50/50 dark:bg-red-900/10">
                            @csrf
                            <h5 class="text-sm font-semibold text-red-700 dark:text-red-400">Nonaktifkan 2FA</h5>
                            <p class="mt-1 text-xs text-red-600/80 dark:text-red-400/70 mb-3">
                                Menghilangkan lapisan keamanan ekstra. Tidak direkomendasikan.
                            </p>
                            <input name="password" type="password" required
                                   placeholder="Password saat ini"
                                   class="w-full mb-2 px-3 py-2 text-sm bg-white dark:bg-gray-900 border border-red-300 dark:border-red-800 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500">
                            <button type="submit"
                                    class="w-full px-3 py-2 rounded-lg text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                                Nonaktifkan
                            </button>
                            @if ($errors->has('password'))
                                <p class="mt-2 text-xs text-red-500">{{ $errors->first('password') }}</p>
                            @endif
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

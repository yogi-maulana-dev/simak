<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Captcha Matematika -->
        <div class="mt-4">
            <x-input-label for="captcha_answer" value="Verifikasi keamanan" />
            <div class="mt-1.5 flex items-stretch gap-2">
                {{-- Soal --}}
                <div class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-lg
                            bg-gradient-to-br from-indigo-50 to-purple-50
                            dark:from-indigo-900/30 dark:to-purple-900/30
                            border border-indigo-200 dark:border-indigo-800
                            select-none"
                     style="font-family: 'Courier New', monospace; letter-spacing: 0.05em;">
                    <span class="text-base font-bold text-indigo-700 dark:text-indigo-300 tracking-wider">
                        {{ $captcha['question'] }}
                    </span>
                    <span class="text-base font-bold text-gray-400">=</span>
                    <span class="text-base font-bold text-purple-600 dark:text-purple-400">?</span>
                </div>

                {{-- Input jawaban --}}
                <input id="captcha_answer" name="captcha_answer" type="number"
                       inputmode="numeric"
                       autocomplete="off"
                       required
                       placeholder="Jawaban"
                       class="flex-1 min-w-0 px-3 py-2 text-sm
                              bg-white dark:bg-gray-900
                              border border-gray-300 dark:border-gray-700 rounded-lg
                              text-gray-900 dark:text-gray-100 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                              transition">

                {{-- Refresh captcha --}}
                <a href="{{ route('login') }}"
                   title="Ganti soal"
                   class="flex-shrink-0 flex items-center justify-center w-10
                          rounded-lg border border-gray-300 dark:border-gray-700
                          text-gray-500 dark:text-gray-400
                          hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-indigo-600 dark:hover:text-indigo-400
                          transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </a>
            </div>
            <input type="hidden" name="captcha_token" value="{{ $captcha['token'] }}">
            <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">
                Catatan: <code class="px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-800">x</code> berarti perkalian
            </p>
            <x-input-error :messages="$errors->get('captcha_answer')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

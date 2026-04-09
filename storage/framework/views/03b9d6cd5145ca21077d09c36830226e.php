<!-- resources/views/auth/login.blade.php -->
<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\GuestLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Session Status -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="mb-6 animate-fadeIn">
            <div class="p-4 bg-gradient-to-r from-muhammadiyah-50 to-emerald-50 border-l-4 border-muhammadiyah-500 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-muhammadiyah-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-muhammadiyah-800"><?php echo e(session('status')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="animate-slideUp">
        <!-- Header dengan gradient -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
            <p class="text-muhammadiyah-600 font-medium">Masuk ke Sistem Arsip Akademik</p>
        </div>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Email Input dengan floating label -->
            <div class="relative">
                <div class="relative">
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="<?php echo e(old('email')); ?>" 
                           required 
                           autofocus 
                           autocomplete="username"
                           class="peer w-full px-4 py-3 pt-6 border-2 border-muhammadiyah-200 rounded-xl focus:border-muhammadiyah-400 focus:ring-2 focus:ring-muhammadiyah-100 focus:outline-none transition-all duration-300 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder=" ">
                    <label for="email" 
                           class="absolute left-4 top-2 text-xs font-medium text-muhammadiyah-600 peer-focus:text-muhammadiyah-500 transition-all duration-300 peer-focus:-translate-y-1 peer-focus:text-xs peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            Alamat Email
                        </div>
                    </label>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="mt-2 flex items-center text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm"><?php echo e($message); ?></span>
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Password Input dengan floating label -->
            <div class="relative">
                <div class="relative">
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           class="peer w-full px-4 py-3 pt-6 border-2 border-muhammadiyah-200 rounded-xl focus:border-muhammadiyah-400 focus:ring-2 focus:ring-muhammadiyah-100 focus:outline-none transition-all duration-300 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder=" ">
                    <label for="password" 
                           class="absolute left-4 top-2 text-xs font-medium text-muhammadiyah-600 peer-focus:text-muhammadiyah-500 transition-all duration-300 peer-focus:-translate-y-1 peer-focus:text-xs peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Kata Sandi
                        </div>
                    </label>
                    
                    <!-- Toggle Password Button -->
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-muhammadiyah-400 hover:text-muhammadiyah-600 transition-colors">
                        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="mt-2 flex items-center text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm"><?php echo e($message); ?></span>
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Options Row -->
            <div class="flex items-center justify-between">
                <!-- Remember Me dengan custom checkbox -->
                <div class="flex items-center">
                    <div class="relative">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="sr-only peer"
                               <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <div class="w-5 h-5 bg-white border-2 border-muhammadiyah-300 rounded flex items-center justify-center peer-checked:bg-muhammadiyah-500 peer-checked:border-muhammadiyah-500 transition-all duration-200">
                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <label for="remember_me" class="ml-2 text-sm text-gray-700 cursor-pointer select-none hover:text-gray-900 transition-colors">
                        Ingat saya
                    </label>
                </div>

                <!-- Forgot Password Link -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>" 
                       class="text-sm font-medium text-muhammadiyah-600 hover:text-muhammadiyah-500 hover:underline transition-all duration-200 group">
                        <span class="flex items-center">
                            Lupa password?
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Submit Button dengan efek glow -->
            <button type="submit" 
                    class="group relative w-full py-4 px-6 bg-gradient-to-r from-muhammadiyah-500 to-muhammadiyah-600 rounded-xl shadow-lg hover:shadow-xl hover:from-muhammadiyah-600 hover:to-muhammadiyah-700 focus:outline-none focus:ring-4 focus:ring-muhammadiyah-300 focus:ring-opacity-50 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                
                <!-- Glow effect -->
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-muhammadiyah-400 to-muhammadiyah-500 opacity-0 group-hover:opacity-100 blur transition-opacity duration-300 -z-10"></div>
                
                <div class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 text-white transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-sm font-semibold text-white tracking-wide">Masuk ke Akun</span>
                </div>
            </button>

            <!-- Register Link -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                <div class="pt-6 border-t border-muhammadiyah-100">
                    <p class="text-center text-sm text-gray-600">
                        Belum memiliki akun?
                        <a href="<?php echo e(route('register')); ?>" 
                           class="font-semibold text-muhammadiyah-600 hover:text-muhammadiyah-500 hover:underline transition-colors group">
                            <span class="flex items-center justify-center">
                                Buat akun baru
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </span>
                        </a>
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>

        <!-- Alternative Login -->
        <div class="mt-8">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-muhammadiyah-100"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-gray-500">Atau masuk dengan</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" 
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-muhammadiyah-100 transition-all duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    <span class="text-sm font-medium">GitHub</span>
                </button>
                
                <button type="button" 
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-muhammadiyah-100 transition-all duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="text-sm font-medium">Google</span>
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        // Add floating label functionality
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            
            inputs.forEach(input => {
                // Check if input has value on load
                if (input.value) {
                    input.previousElementSibling.classList.add('-translate-y-1', 'text-xs');
                }
                
                // Add focus/blur effects
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-muhammadiyah-100');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-muhammadiyah-100');
                });
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
        
        .animate-slideUp {
            animation: slideUp 0.6s ease-out;
        }
        
        /* Custom input focus styles */
        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 157, 46, 0.1);
        }
        
        /* Smooth transitions */
        * {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f0f9f0;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #b9e5b9;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #8cd18c;
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH /home/umlacid/simak.uml.ac.id/resources/views/auth/login.blade.php ENDPATH**/ ?>
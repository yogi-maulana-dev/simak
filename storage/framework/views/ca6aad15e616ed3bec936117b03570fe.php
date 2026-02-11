<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false, dropdownOpen: false }" class="bg-white/90 backdrop-blur-lg shadow-lg border-b border-muhammadiyah-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-muhammadiyah-600 to-muhammadiyah-800 bg-clip-text text-transparent logo-glow">
                            <?php echo e(config('app.name', 'SIMAK')); ?>

                        </h1>
                        <p class="text-xs text-muhammadiyah-500 font-medium">Universitas Muhammadiyah</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <!-- Menu untuk semua user yang login -->
                    <a href="<?php echo e(route('dashboard')); ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('dashboard') ? 'bg-muhammadiyah-100 text-muhammadiyah-700' : 'text-gray-600 hover:text-muhammadiyah-600 hover:bg-muhammadiyah-50'); ?>">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </span>
                    </a>
                    
                    <a href="<?php echo e(route('arsip.index')); ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('arsip.*') ? 'bg-muhammadiyah-100 text-muhammadiyah-700' : 'text-gray-600 hover:text-muhammadiyah-600 hover:bg-muhammadiyah-50'); ?>">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            Arsip
                        </span>
                    </a>

                    <!-- Menu khusus Superadmin -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isSuperadmin()): ?>
                        <a href="<?php echo e(route('admin.arsip.index')); ?>" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('admin.arsip.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50'); ?>">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Admin Arsip
                            </span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.users.index')); ?>" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'); ?>">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13-5.75V14a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h5.5M12 8a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Manajemen User
                            </span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- User Dropdown -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <div class="relative ml-4" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-muhammadiyah-50 transition-all duration-200">
                            <div class="w-8 h-8 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                            </div>
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-xs text-muhammadiyah-500"><?php echo e(Auth::user()->email); ?></p>
                            </div>
                            <svg class="w-4 h-4 text-gray-500" :class="{ 'transform rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" 
                             @click.away="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-muhammadiyah-100 py-2 z-50">
                            
                            <!-- User Info dengan Badge Role -->
                            <div class="px-4 py-3 border-b border-muhammadiyah-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?php echo e(Auth::user()->name); ?></p>
                                        <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php if(auth()->user()->isSuperadmin()): ?> bg-purple-100 text-purple-800
                                        <?php elseif(auth()->user()->role->name === 'admin_univ'): ?> bg-blue-100 text-blue-800
                                        <?php elseif(auth()->user()->role->name === 'admin_fakultas'): ?> bg-green-100 text-green-800
                                        <?php elseif(auth()->user()->role->name === 'admin_prodi'): ?> bg-indigo-100 text-indigo-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                        <?php echo e(Auth::user()->role->name ?? 'user'); ?>

                                    </span>
                                </div>
                            </div>
                            
                            <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-muhammadiyah-50 hover:text-muhammadiyah-700 transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Saya
                            </a>
                            
                            <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-muhammadiyah-50 hover:text-muhammadiyah-700 transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Pengaturan
                            </a>

                            <div class="border-t border-muhammadiyah-100 my-2"></div>

                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-muhammadiyah-500">
                    <svg class="h-6 w-6" :class="{ 'hidden': open, 'block': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="h-6 w-6" :class="{ 'block': open, 'hidden': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-show="open" x-transition>
            <div class="pt-2 pb-3 space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" 
                       class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium <?php echo e(request()->routeIs('dashboard') ? 'bg-muhammadiyah-50 text-muhammadiyah-700 border-l-4 border-muhammadiyah-500' : 'text-gray-600 hover:bg-muhammadiyah-50 hover:text-muhammadiyah-700 hover:border-l-4 hover:border-muhammadiyah-300'); ?>">
                        Dashboard
                    </a>
                    
                    <a href="<?php echo e(route('arsip.index')); ?>" 
                       class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium <?php echo e(request()->routeIs('arsip.*') ? 'bg-muhammadiyah-50 text-muhammadiyah-700 border-l-4 border-muhammadiyah-500' : 'text-gray-600 hover:bg-muhammadiyah-50 hover:text-muhammadiyah-700 hover:border-l-4 hover:border-muhammadiyah-300'); ?>">
                        Arsip
                    </a>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isSuperadmin()): ?>
                        <a href="<?php echo e(route('admin.arsip.index')); ?>" 
                           class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium <?php echo e(request()->routeIs('admin.arsip.*') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-500' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700 hover:border-l-4 hover:border-purple-300'); ?>">
                            Admin Arsip
                        </a>
                        
                        <a href="<?php echo e(route('admin.users.index')); ?>" 
                           class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium <?php echo e(request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-500' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700 hover:border-l-4 hover:border-blue-300'); ?>">
                            Manajemen User
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800"><?php echo e(Auth::user()->name); ?></div>
                            <div class="text-sm font-medium text-gray-500"><?php echo e(Auth::user()->email); ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                Role: <span class="font-semibold"><?php echo e(Auth::user()->role->name ?? 'user'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            Profil Saya
                        </a>
                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            Pengaturan
                        </a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</nav><?php /**PATH /home/bismillah/Dokumen/Project/Folder Baru/simak/resources/views/layouts/navigation.blade.php ENDPATH**/ ?>
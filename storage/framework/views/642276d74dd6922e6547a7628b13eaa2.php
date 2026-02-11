<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'SIMAK')); ?> - <?php echo e($title ?? 'Dashboard'); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <!-- Livewire Styles -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


        <style>
            .logo-glow {
                text-shadow: 0 0 20px rgba(46, 157, 46, 0.3);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-muhammadiyah-50">
        <!-- Loading Screen -->
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 1000)"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-white z-50 flex items-center justify-center">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-6 relative">
                    <!-- Animated Logo -->
                    <div class="absolute inset-0 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-white rounded-full"></div>
                    <div class="absolute inset-4 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">M</span>
                    </div>
                </div>
                <p class="text-muhammadiyah-600 font-medium animate-pulse">Memuat Sistem Informasi Akademik...</p>
            </div>
        </div>

        <div class="min-h-screen flex flex-col">
            <!-- Include Navigation -->
            <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Page Heading -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($header)): ?>
                <header class="bg-gradient-to-r from-white to-muhammadiyah-50 shadow-sm border-b border-muhammadiyah-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 animate-slideUp">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Page Content -->
            <main class="flex-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($slot)): ?>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($slot); ?>

                    </div>
                <?php else: ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-muhammadiyah-100 mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">M</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Sistem Informasi Akademik</p>
                                    <p class="text-xs text-muhammadiyah-500">Universitas Muhammadiyah</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mb-4 md:mb-0">
                            <p class="text-lg font-bold text-muhammadiyah-800">"Fastabiqul Khairat"</p>
                            <p class="text-sm text-muhammadiyah-600">Berlomba-lomba dalam kebaikan</p>
                        </div>
                        
                        <div class="text-sm text-gray-500">
                            <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'SIMAK')); ?>. All rights reserved.</p>
                            <p class="text-xs mt-1">v<?php echo e(config('app.version', '1.0.0')); ?></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Back to Top Button -->
        <div x-data="backToTop()">
            <button x-show="scrolled" 
                    @click="scrollToTop"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-10"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-10"
                    class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-br from-muhammadiyah-500 to-muhammadiyah-600 text-white rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center z-30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </button>
        </div>

        <!-- Notification Bell -->
        <div x-data="{ open: false }" class="fixed bottom-8 left-8 z-30">
            <button @click="open = !open"
                    class="relative w-12 h-12 bg-white text-muhammadiyah-600 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                    3
                </span>
            </button>
            
            <!-- Notification Panel -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute left-0 bottom-16 bg-white rounded-lg shadow-xl p-4 w-80">
                <h3 class="font-semibold text-gray-800 mb-3">Notifikasi</h3>
                <div class="space-y-2">
                    <div class="p-3 bg-muhammadiyah-50 rounded-lg hover:bg-muhammadiyah-100 cursor-pointer transition">
                        <p class="text-sm font-medium text-gray-800">Arsip baru ditambahkan</p>
                        <p class="text-xs text-gray-600 mt-1">2 menit yang lalu</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                        <p class="text-sm font-medium text-gray-800">Pembaruan sistem</p>
                        <p class="text-xs text-gray-600 mt-1">1 jam yang lalu</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                        <p class="text-sm font-medium text-gray-800">Pesan dari admin</p>
                        <p class="text-xs text-gray-600 mt-1">3 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livewire Scripts -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

        
        <!-- Alpine.js v3 - PENTING: Harus setelah Livewire -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            // Alpine.js Components
            document.addEventListener('alpine:init', () => {
                // Back to top functionality
                Alpine.data('backToTop', () => ({
                    scrolled: false,
                    init() {
                        window.addEventListener('scroll', () => {
                            this.scrolled = window.pageYOffset > 300;
                        });
                    },
                    scrollToTop() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                }));
            });

            // Add active class to current nav link
            document.addEventListener('DOMContentLoaded', function() {
                const currentPath = window.location.pathname;
                const navLinks = document.querySelectorAll('nav a[href]');
                
                navLinks.forEach(link => {
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');
                    }
                });

                // Add hover effects to cards
                const cards = document.querySelectorAll('.transform');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        card.style.transform = 'translateY(-4px)';
                    });
                    
                    card.addEventListener('mouseleave', () => {
                        card.style.transform = 'translateY(0)';
                    });
                });
            });
        </script>

        <!-- Custom Styles -->
        <style>
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 10px;
            }
            
            ::-webkit-scrollbar-track {
                background: #f0f9f0;
                border-radius: 5px;
            }
            
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(to bottom, #5db85d, #2e9d2e);
                border-radius: 5px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(to bottom, #4a9c4a, #228422);
            }
            
            /* Selection color */
            ::selection {
                background-color: rgba(46, 157, 46, 0.2);
                color: #135213;
            }
            
            /* Smooth transitions */
            * {
                transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 300ms;
            }
            
            /* Animations */
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-slideUp {
                animation: slideUp 0.6s ease-out forwards;
            }
            
            /* Gradient text */
            .gradient-text {
                background: linear-gradient(135deg, #2e9d2e 0%, #1a6b1a 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Fix Livewire loading delay */
            [wire\:loading] {
                display: inline-block;
            }

            [wire\:loading].hidden {
                display: none;
            }
        </style>
    </body>
</html><?php /**PATH /home/bismillah/Dokumen/Project/Folder Baru/simak/resources/views/layouts/app.blade.php ENDPATH**/ ?>
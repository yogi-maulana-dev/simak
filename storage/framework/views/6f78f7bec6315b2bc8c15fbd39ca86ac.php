<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?>xxx</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            .logo-muhammadiyah {
                background: linear-gradient(135deg, #2e9d2e 0%, #1a6b1a 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="font-sans text-gray-800 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" 
             style="background: linear-gradient(135deg, #f0f9f0 0%, #dcf2dc 100%);">
            
            <!-- Logo Container -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-muhammadiyah-400 to-muhammadiyah-600 rounded-full shadow-lg mb-4">
                    <span class="text-3xl font-bold text-white">M</span>
                </div>
                <h1 class="text-3xl font-bold logo-muhammadiyah">
                    <?php echo e(config('app.name', 'SIMAK')); ?>

                </h1>
                <p class="text-muhammadiyah-600 mt-2 font-medium">Sistem Informasi Akademik</p>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-8 bg-white/90 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-2xl border border-muhammadiyah-100">
                <?php echo e($slot); ?>

            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-muhammadiyah-600">
                    &copy; <?php echo e(date('Y')); ?> <span class="font-semibold">Muhammadiyah</span>. All rights reserved.
                </p>
                <p class="text-xs text-muhammadiyah-400 mt-1">"Fastabiqul Khairat"</p>
            </div>
        </div>
    </body>
</html><?php /**PATH /home/bismillah/Dokumen/Project/Folder Baru/simak/resources/views/layouts/guest.blade.php ENDPATH**/ ?>
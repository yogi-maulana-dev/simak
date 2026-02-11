<?php $__env->startSection('title', 'Akses Ditolak'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 flex flex-col justify-center items-center px-4 py-12">
    <div class="max-w-3xl w-full mx-auto text-center">
        <!-- Icon or Illustration -->
        <div class="mb-8">
            <div class="relative w-48 h-48 mx-auto">
                <div class="absolute inset-0 bg-red-100 rounded-full opacity-20 animate-pulse"></div>
                <div class="relative flex items-center justify-center w-full h-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error Code -->
        <div class="mb-6">
            <span class="inline-block px-4 py-2 text-6xl font-bold text-red-600 bg-red-50 rounded-lg border border-red-100 shadow-sm">
                403
            </span>
        </div>

        <!-- Message -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
            Akses Ditolak
        </h1>
        
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini mungkin terbatas untuk peran atau hak akses tertentu.
        </p>

        <!-- Additional Info -->
        
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bismillah/Dokumen/Project/Folder Baru/simak/resources/views/errors/403.blade.php ENDPATH**/ ?>
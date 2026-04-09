<div class="p-6">

    <h1 class="text-xl font-bold mb-4">Data Arsip</h1>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($arsips->isEmpty()): ?>
        <div class="text-red-600 font-semibold">
            ❌ Data tidak ditemukan
        </div>
    <?php else: ?>
        <table class="w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Judul</th>
                    <th class="border px-3 py-2">Fakultas</th>
                    <th class="border px-3 py-2">Prodi</th>
                    <th class="border px-3 py-2">Tanggal</th>
                    <th class="border px-3 py-2">File</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $arsips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arsip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="border px-3 py-2"><?php echo e($arsip->judul); ?></td>
                        <td class="border px-3 py-2"><?php echo e($arsip->nama_fakultas ?? '-'); ?></td>
                        <td class="border px-3 py-2"><?php echo e($arsip->nama_prodi ?? '-'); ?></td>
                        <td class="border px-3 py-2">
                            <?php echo e($arsip->created_at ? \Carbon\Carbon::parse($arsip->created_at)->format('d-m-Y') : '-'); ?>

                        </td>
                        <td class="border px-3 py-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($arsip->file && \Storage::disk('public')->exists($arsip->file)): ?>
                                <div class="flex gap-2">
                                    <!-- Lihat File -->
                                    <a href="<?php echo e(\Storage::url($arsip->file)); ?>" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 p-1.5 rounded-md hover:bg-blue-50 transition"
                                       title="Lihat File">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Download File -->
                                    <a href="<?php echo e(\Storage::url($arsip->file)); ?>" 
                                       download
                                       class="text-green-600 hover:text-green-900 p-1.5 rounded-md hover:bg-green-50 transition"
                                       title="Download File">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400">File tidak tersedia</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH /home/umlacid/simak.uml.ac.id/resources/views/livewire/data-arsip/index.blade.php ENDPATH**/ ?>
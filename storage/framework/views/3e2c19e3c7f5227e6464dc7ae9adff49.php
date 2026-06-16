

<?php $__env->startSection('title', 'Hasil Ujian - ' . $ujian->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-6 animate-fadeIn">
    
    <!-- Header -->
    <div class="bg-gradient-to-r <?php echo e(($hasil->nilai ?? 0) >= 75 ? 'from-green-500 to-emerald-600' : 'from-orange-500 to-red-600'); ?> rounded-3xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <?php echo e(($hasil->nilai ?? 0) >= 75 ? '🎉 Selamat! Kamu Lulus!' : '💪 Terus Berlatih!'); ?>

                </h1>
                <p class="text-white text-opacity-90"><?php echo e($ujian->judul); ?></p>
                <p class="text-white text-opacity-90 text-sm mt-1">
                    <?php echo e($ujian->mapel->nama ?? '-'); ?> • <?php echo e($ujian->mode === 'ujian' ? 'UJIAN' : 'LATIHAN'); ?>

                </p>
            </div>
            <div class="text-center bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl px-6 py-4">
                <div class="text-5xl font-bold"><?php echo e(number_format($hasil->nilai ?? 0, 0)); ?></div>
                <p class="text-sm text-white text-opacity-90 mt-1">Nilai Akhir</p>
            </div>
        </div>
    </div>

    <!-- Info Waktu Pengerjaan -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($waktuPengerjaan): ?>
        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-4">
            <p class="text-blue-800 font-semibold">
                <i class="fas fa-clock mr-2"></i>
                Waktu Pengerjaan: <strong><?php echo e($waktuPengerjaan); ?></strong>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasil->submitted_at): ?>
                    • Disubmit: <?php echo e($hasil->submitted_at->format('d M Y, H:i')); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Stats Cards - Benar/Salah/Kosong -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($hasil->benar + $hasil->salah + $hasil->kosong ?? 0); ?></p>
                    <p class="text-sm text-gray-600">Total Soal</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-green-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600"><?php echo e($hasil->benar ?? 0); ?></p>
                    <p class="text-sm text-gray-600">Benar</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-red-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600"><?php echo e($hasil->salah ?? 0); ?></p>
                    <p class="text-sm text-gray-600">Salah</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-minus-circle text-2xl text-gray-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-600"><?php echo e($hasil->kosong ?? 0); ?></p>
                    <p class="text-sm text-gray-600">Kosong</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ INTEGRITY REPORT - Anti Tab/Copy/Paste -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ujian->mode === 'ujian'): ?>
        <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-shield-alt text-red-600"></i>
                Laporan Integritas
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <!-- Tab Switch -->
                <div class="text-center p-4 rounded-xl <?php echo e(($hasil->tab_switch_count ?? 0) > 3 ? 'bg-red-50 border-2 border-red-200' : 'bg-gray-50 border-2 border-gray-200'); ?>">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center <?php echo e(($hasil->tab_switch_count ?? 0) > 3 ? 'bg-red-100' : 'bg-gray-100'); ?>">
                        <i class="fas fa-window-restore text-xl <?php echo e(($hasil->tab_switch_count ?? 0) > 3 ? 'text-red-600' : 'text-gray-600'); ?>"></i>
                    </div>
                    <p class="text-2xl font-bold mt-2 <?php echo e(($hasil->tab_switch_count ?? 0) > 3 ? 'text-red-600' : 'text-gray-900'); ?>">
                        <?php echo e($hasil->tab_switch_count ?? 0); ?>

                    </p>
                    <p class="text-xs text-gray-600 mt-1">Tab Switch</p>
                </div>

                <!-- Copy -->
                <div class="text-center p-4 rounded-xl <?php echo e(($hasil->copy_count ?? 0) > 0 ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-gray-50 border-2 border-gray-200'); ?>">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center <?php echo e(($hasil->copy_count ?? 0) > 0 ? 'bg-yellow-100' : 'bg-gray-100'); ?>">
                        <i class="fas fa-copy text-xl <?php echo e(($hasil->copy_count ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-600'); ?>"></i>
                    </div>
                    <p class="text-2xl font-bold mt-2 <?php echo e(($hasil->copy_count ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-900'); ?>">
                        <?php echo e($hasil->copy_count ?? 0); ?>

                    </p>
                    <p class="text-xs text-gray-600 mt-1">Copy</p>
                </div>

                <!-- Paste -->
                <div class="text-center p-4 rounded-xl <?php echo e(($hasil->paste_count ?? 0) > 0 ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-gray-50 border-2 border-gray-200'); ?>">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center <?php echo e(($hasil->paste_count ?? 0) > 0 ? 'bg-yellow-100' : 'bg-gray-100'); ?>">
                        <i class="fas fa-paste text-xl <?php echo e(($hasil->paste_count ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-600'); ?>"></i>
                    </div>
                    <p class="text-2xl font-bold mt-2 <?php echo e(($hasil->paste_count ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-900'); ?>">
                        <?php echo e($hasil->paste_count ?? 0); ?>

                    </p>
                    <p class="text-xs text-gray-600 mt-1">Paste</p>
                </div>

                <!-- Right Click -->
                <div class="text-center p-4 rounded-xl <?php echo e(($hasil->right_click_count ?? 0) > 0 ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-gray-50 border-2 border-gray-200'); ?>">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center <?php echo e(($hasil->right_click_count ?? 0) > 0 ? 'bg-yellow-100' : 'bg-gray-100'); ?>">
                        <i class="fas fa-mouse text-xl <?php echo e(($hasil->right_click_count ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-600'); ?>"></i>
                    </div>
                    <p class="text-2xl font-bold mt-2 <?php echo e(($hasil->right_click_count ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-900'); ?>">
                        <?php echo e($hasil->right_click_count ?? 0); ?>

                    </p>
                    <p class="text-xs text-gray-600 mt-1">Right Click</p>
                </div>

                <!-- Blur -->
                <div class="text-center p-4 rounded-xl <?php echo e(($hasil->blur_count ?? 0) > 3 ? 'bg-red-50 border-2 border-red-200' : 'bg-gray-50 border-2 border-gray-200'); ?>">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center <?php echo e(($hasil->blur_count ?? 0) > 3 ? 'bg-red-100' : 'bg-gray-100'); ?>">
                        <i class="fas fa-eye-slash text-xl <?php echo e(($hasil->blur_count ?? 0) > 3 ? 'text-red-600' : 'text-gray-600'); ?>"></i>
                    </div>
                    <p class="text-2xl font-bold mt-2 <?php echo e(($hasil->blur_count ?? 0) > 3 ? 'text-red-600' : 'text-gray-900'); ?>">
                        <?php echo e($hasil->blur_count ?? 0); ?>

                    </p>
                    <p class="text-xs text-gray-600 mt-1">Blur</p>
                </div>
            </div>

            <!-- Status Integritas -->
            <?php
                $totalViolation = ($hasil->tab_switch_count ?? 0) + ($hasil->copy_count ?? 0) + ($hasil->paste_count ?? 0) + ($hasil->right_click_count ?? 0) + ($hasil->blur_count ?? 0);
            ?>
            <div class="mt-4 p-4 rounded-xl <?php echo e($totalViolation === 0 ? 'bg-green-50 border-2 border-green-200' : ($totalViolation <= 5 ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-red-50 border-2 border-red-200')); ?>">
                <p class="font-bold flex items-center gap-2 <?php echo e($totalViolation === 0 ? 'text-green-800' : ($totalViolation <= 5 ? 'text-yellow-800' : 'text-red-800')); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalViolation === 0): ?>
                        <i class="fas fa-check-circle"></i> Integritas Sempurna - Tidak ada pelanggaran
                    <?php elseif($totalViolation <= 5): ?>
                        <i class="fas fa-exclamation-triangle"></i> Peringatan - Ada <?php echo e($totalViolation); ?> aktivitas mencurigakan
                    <?php else: ?>
                        <i class="fas fa-times-circle"></i> Banyak Pelanggaran - <?php echo e($totalViolation); ?> aktivitas mencurigakan
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Review Soal (jika diizinkan) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($soalList): ?>
        <div class="bg-white rounded-3xl p-6 shadow-lg border-2 border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-list-check text-blue-600"></i>
                Review Jawaban
            </h3>
            
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $soalList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $soalUjian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $soal = $soalUjian->soal;
                        $jawabanSiswa = is_array($hasil->jawaban) ? ($hasil->jawaban[$soal->id] ?? null) : null;
                        $isCorrect = $jawabanSiswa && $soal->tipe === 'pg' ? ($jawabanSiswa === $soal->jawaban) : null;
                    ?>
                    <div class="border-2 rounded-2xl p-4 <?php echo e($isCorrect === true ? 'border-green-300 bg-green-50' : ($isCorrect === false ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-gray-50')); ?>">
                        <div class="flex items-start gap-3 mb-3">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0 <?php echo e($isCorrect === true ? 'bg-green-600' : ($isCorrect === false ? 'bg-red-600' : 'bg-gray-500')); ?>">
                                <?php echo e($index + 1); ?>

                            </span>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 mb-2"><?php echo e($soal->pertanyaan); ?></p>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($soal->tipe === 'pg'): ?>
                                    <div class="space-y-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $opsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($opsi): ?>
                                                <div class="flex items-center gap-2 p-2 rounded-lg <?php echo e($label === $soal->jawaban ? 'bg-green-100 border-2 border-green-400' : ($label === $jawabanSiswa ? 'bg-red-100 border-2 border-red-400' : 'bg-white border border-gray-200')); ?>">
                                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold <?php echo e($label === $soal->jawaban ? 'bg-green-600 text-white' : ($label === $jawabanSiswa ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700')); ?>">
                                                        <?php echo e($label); ?>

                                                    </span>
                                                    <span class="flex-1 text-sm"><?php echo e($opsi); ?></span>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label === $soal->jawaban): ?>
                                                        <i class="fas fa-check text-green-600"></i>
                                                    <?php elseif($label === $jawabanSiswa): ?>
                                                        <i class="fas fa-times text-red-600"></i>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Jawaban Anda:</span>
                                        <span class="<?php echo e($isCorrect ? 'text-green-700' : 'text-red-700'); ?> font-bold">
                                            <?php echo e($jawabanSiswa ?? 'Tidak dijawab'); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isCorrect && $soal->jawaban): ?>
                                            <span class="text-green-700 font-bold ml-2">
                                                (Jawaban benar: <?php echo e($soal->jawaban); ?>)
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-2">
                                        <p class="text-sm font-semibold text-gray-700">Jawaban Anda:</p>
                                        <p class="text-sm text-gray-900 mt-1 p-3 bg-white rounded-lg border border-gray-200">
                                            <?php echo e($jawabanSiswa ?? 'Tidak dijawab'); ?>

                                        </p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-6 text-center">
            <i class="fas fa-lock text-4xl text-yellow-600 mb-3"></i>
            <p class="text-yellow-800 font-semibold">Review jawaban tidak tersedia</p>
            <p class="text-yellow-700 text-sm mt-1">Guru tidak mengizinkan review untuk ujian ini</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Action Buttons -->
    <div class="flex gap-3 flex-wrap">
        <a href="<?php echo e(route('siswa.ujian.daftar')); ?>" 
           class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 font-bold text-center transition-all shadow-lg shadow-blue-500/30">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Ujian
        </a>
        <a href="<?php echo e(route('siswa.dashboard')); ?>" 
           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300 font-bold transition-all">
            <i class="fas fa-home mr-2"></i> Dashboard
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\FILE_VSC\EsasyExam\resources\views/siswa/ujian/hasil.blade.php ENDPATH**/ ?>
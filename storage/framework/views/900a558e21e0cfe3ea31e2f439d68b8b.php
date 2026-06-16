<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo e(config('app.name', 'EsasyExam')); ?> - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <style>
        @keyframes gradient-siswa {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes bounce-soft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        
        .animated-gradient-siswa {
            background: linear-gradient(-45deg, #fbbf24, #f59e0b, #ec4899, #8b5cf6);
            background-size: 400% 400%;
            animation: gradient-siswa 10s ease infinite;
        }
        
        .animated-gradient-guru {
            background: linear-gradient(-45deg, #1e40af, #3b82f6, #0ea5e9, #06b6d4);
            background-size: 400% 400%;
            animation: gradient-siswa 12s ease infinite;
        }
        
        .animated-gradient-admin {
            background: linear-gradient(-45deg, #1f2937, #374151, #dc2626, #991b1b);
            background-size: 400% 400%;
            animation: gradient-siswa 15s ease infinite;
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        .bounce-soft {
            animation: bounce-soft 2s ease-in-out infinite;
        }
        
        .wiggle {
            animation: wiggle 1s ease-in-out infinite;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .role-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .role-card:hover {
            transform: translateY(-8px) scale(1.02);
        }
        
        .role-card.active-siswa {
            transform: scale(1.08) rotate(2deg);
            box-shadow: 0 25px 50px rgba(251, 191, 36, 0.4);
            border-color: #fbbf24;
        }
        
        .role-card.active-guru {
            transform: scale(1.05);
            box-shadow: 0 25px 50px rgba(59, 130, 246, 0.4);
            border-color: #3b82f6;
        }
        
        .role-card.active-admin {
            transform: scale(1.05);
            box-shadow: 0 25px 50px rgba(220, 38, 38, 0.4);
            border-color: #dc2626;
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
        }
        
        .btn-siswa {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            transition: all 0.3s ease;
        }
        
        .btn-siswa:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.5);
        }
        
        .btn-guru {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            transition: all 0.3s ease;
        }
        
        .btn-guru:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.5);
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.5);
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            transition: all 1s ease;
        }
        
        .transition-bg {
            transition: background 1s ease;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden transition-bg"
      x-data="loginForm()"
      :class="{
          'animated-gradient-siswa': role === 'siswa',
          'animated-gradient-guru': role === 'guru',
          'animated-gradient-admin': role === 'admin'
      }">
    
    <!-- Animated Background Shapes -->
    <div class="shape" :class="role === 'siswa' ? 'bg-yellow-300' : (role === 'guru' ? 'bg-blue-400' : 'bg-red-500')" 
         style="width: 400px; height: 400px; top: -100px; left: -100px; animation: float 8s ease-in-out infinite;"></div>
    <div class="shape" :class="role === 'siswa' ? 'bg-pink-300' : (role === 'guru' ? 'bg-cyan-400' : 'bg-gray-600')" 
         style="width: 300px; height: 300px; bottom: -100px; right: -100px; animation: float 10s ease-in-out infinite reverse;"></div>
    <div class="shape" :class="role === 'siswa' ? 'bg-purple-300' : (role === 'guru' ? 'bg-indigo-400' : 'bg-red-700')" 
         style="width: 200px; height: 200px; top: 50%; right: 20%; animation: float 7s ease-in-out infinite;"></div>
    
    <!-- Floating Icons Background - SISWA -->
    <template x-if="role === 'siswa'">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <i class="fas fa-star absolute top-20 left-20 text-yellow-300 opacity-20 text-6xl bounce-soft"></i>
            <i class="fas fa-heart absolute top-40 right-32 text-pink-300 opacity-20 text-5xl bounce-soft" style="animation-delay: 0.5s;"></i>
            <i class="fas fa-smile absolute bottom-32 left-40 text-yellow-200 opacity-20 text-7xl bounce-soft" style="animation-delay: 1s;"></i>
            <i class="fas fa-rocket absolute bottom-40 right-40 text-purple-300 opacity-20 text-5xl bounce-soft" style="animation-delay: 1.5s;"></i>
            <i class="fas fa-gamepad absolute top-1/2 left-10 text-pink-200 opacity-20 text-6xl bounce-soft" style="animation-delay: 2s;"></i>
            <i class="fas fa-trophy absolute top-32 left-1/2 text-yellow-200 opacity-20 text-5xl bounce-soft" style="animation-delay: 2.5s;"></i>
        </div>
    </template>
    
    <!-- Floating Icons Background - GURU -->
    <template x-if="role === 'guru'">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <i class="fas fa-graduation-cap absolute top-20 left-20 text-blue-200 opacity-15 text-6xl float-animation"></i>
            <i class="fas fa-book absolute top-40 right-32 text-cyan-200 opacity-15 text-5xl float-animation" style="animation-delay: 1s;"></i>
            <i class="fas fa-chalkboard absolute bottom-32 left-40 text-blue-300 opacity-15 text-7xl float-animation" style="animation-delay: 2s;"></i>
            <i class="fas fa-award absolute bottom-40 right-40 text-indigo-200 opacity-15 text-5xl float-animation" style="animation-delay: 3s;"></i>
            <i class="fas fa-laptop-code absolute top-1/2 left-10 text-cyan-300 opacity-15 text-6xl float-animation" style="animation-delay: 4s;"></i>
        </div>
    </template>
    
    <!-- Floating Icons Background - ADMIN -->
    <template x-if="role === 'admin'">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <i class="fas fa-shield-alt absolute top-20 left-20 text-red-300 opacity-15 text-6xl float-animation"></i>
            <i class="fas fa-lock absolute top-40 right-32 text-gray-300 opacity-15 text-5xl float-animation" style="animation-delay: 1s;"></i>
            <i class="fas fa-user-shield absolute bottom-32 left-40 text-red-200 opacity-15 text-7xl float-animation" style="animation-delay: 2s;"></i>
            <i class="fas fa-key absolute bottom-40 right-40 text-gray-200 opacity-15 text-5xl float-animation" style="animation-delay: 3s;"></i>
            <i class="fas fa-server absolute top-1/2 left-10 text-red-300 opacity-15 text-6xl float-animation" style="animation-delay: 4s;"></i>
        </div>
    </template>

    <!-- Main Container -->
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
        
        <!-- Left Side - Branding -->
        <div class="hidden lg:block text-white slide-in">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-2xl">
                        <i class="fas fa-graduation-cap text-4xl" 
                           :class="role === 'siswa' ? 'text-yellow-500' : (role === 'guru' ? 'text-blue-600' : 'text-red-600')"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">EsasyExam</h1>
                        <p class="text-sm" :class="role === 'siswa' ? 'text-yellow-100' : (role === 'guru' ? 'text-blue-100' : 'text-gray-300')">
                            Smart Learning Platform
                        </p>
                    </div>
                </div>
                
                <!-- Dynamic Tagline -->
                <template x-if="role === 'siswa'">
                    <div class="slide-in">
                        <h2 class="text-5xl font-bold mb-6 leading-tight">
                            Belajar Jadi<br>
                            <span class="text-yellow-300 wiggle">Lebih Seru!</span> 🎉
                        </h2>
                        <p class="text-xl text-yellow-100 mb-8 leading-relaxed">
                            Yuk kerjakan ujian dengan mudah dan raih nilai terbaik! Kamu pasti bisa! 
                        </p>
                    </div>
                </template>
                
                <template x-if="role === 'guru'">
                    <div class="slide-in">
                        <h2 class="text-5xl font-bold mb-6 leading-tight">
                            Mengajar Lebih<br>
                            <span class="text-cyan-300">Efektif</span> 📚
                        </h2>
                        <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                            Buat ujian berkualitas dengan mudah dan pantau perkembangan siswa secara real-time.
                        </p>
                    </div>
                </template>
                
                <template x-if="role === 'admin'">
                    <div class="slide-in">
                        <h2 class="text-5xl font-bold mb-6 leading-tight">
                            Kelola Sistem<br>
                            <span class="text-red-300">Dengan Aman</span> 🔒
                        </h2>
                        <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                            Kontrol penuh atas platform. Monitor aktivitas dan pastikan keamanan sistem.
                        </p>
                    </div>
                </template>
                
                <!-- Dynamic Features -->
                <div class="space-y-4 mb-8">
                    <template x-if="role === 'siswa'">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-star text-xl text-yellow-300"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Soal Menarik</p>
                                    <p class="text-yellow-100 text-sm">Dengan gambar dan video</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-pink-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-trophy text-xl text-pink-300"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Raih Prestasi</p>
                                    <p class="text-yellow-100 text-sm">Kumpulkan badge & poin</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-chart-line text-xl text-purple-300"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Lihat Progress</p>
                                    <p class="text-yellow-100 text-sm">Pantau perkembanganmu</p>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="role === 'guru'">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-file-alt text-xl text-blue-200"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Bank Soal Lengkap</p>
                                    <p class="text-blue-100 text-sm">Ribuan soal berkualitas</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-cyan-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-robot text-xl text-cyan-200"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">AI Generate</p>
                                    <p class="text-blue-100 text-sm">Buat soal otomatis dengan AI</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-chart-bar text-xl text-indigo-200"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Analisis Detail</p>
                                    <p class="text-blue-100 text-sm">Laporan performa siswa</p>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="role === 'admin'">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-users text-xl text-red-200"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Manajemen User</p>
                                    <p class="text-gray-300 text-sm">Kelola guru & siswa</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-400 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-shield-alt text-xl text-gray-200"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Keamanan Sistem</p>
                                    <p class="text-gray-300 text-sm">Proteksi data terjamin</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-500 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-cog text-xl text-red-200"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Konfigurasi Penuh</p>
                                    <p class="text-gray-300 text-sm">Atur semua sistem</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-white border-opacity-20">
                    <div>
                        <p class="text-3xl font-bold">1000+</p>
                        <p class="text-sm" :class="role === 'siswa' ? 'text-yellow-100' : (role === 'guru' ? 'text-blue-100' : 'text-gray-300')">Guru Aktif</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">10000+</p>
                        <p class="text-sm" :class="role === 'siswa' ? 'text-yellow-100' : (role === 'guru' ? 'text-blue-100' : 'text-gray-300')">Siswa</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">5000+</p>
                        <p class="text-sm" :class="role === 'siswa' ? 'text-yellow-100' : (role === 'guru' ? 'text-blue-100' : 'text-gray-300')">Ujian</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="glass-effect rounded-3xl p-8 md:p-10 shadow-2xl slide-in">
            
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl"
                     :class="role === 'siswa' ? 'bg-gradient-to-br from-yellow-400 to-yellow-500' : (role === 'guru' ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-red-500 to-red-600')">
                    <i class="fas fa-graduation-cap text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">EsasyExam</h1>
                <p class="text-sm text-gray-500">Smart Learning Platform</p>
            </div>

            <!-- Welcome Text -->
            <div class="text-center mb-8">
                <!-- SESUDAH -->
                <div class="text-center mb-8">
                    <img src="<?php echo e(asset('images/logo/esasyexam-logo.png')); ?>" 
                        alt="EsasyExam Logo" 
                        class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-4 drop-shadow-xl">
                    <h1 class="text-4xl font-bold text-gray-900">EsasyExam</h1>
                    <p class="text-gray-600 mt-2">Smart Learning Platform</p>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    <template x-if="role === 'siswa'">Halo, Sobat Belajar! 👋</template>
                    <template x-if="role === 'guru'">Selamat Datang, Bapak/Ibu Guru 👨‍🏫</template>
                    <template x-if="role === 'admin'">Akses Administrator 🔐</template>
                </h2>
                <p class="text-gray-600">
                    <template x-if="role === 'siswa'">Siap mengerjakan ujian hari ini?</template>
                    <template x-if="role === 'guru'">Masuk ke dashboard mengajar Anda</template>
                    <template x-if="role === 'admin'">Masuk ke panel administrasi</template>
                </p>
            </div>

            <!-- Session Status -->
            <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

            <!-- Error Message -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 text-red-700 rounded-2xl text-sm slide-in">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="font-bold mb-1">Terjadi Kesalahan</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.store')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Role Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Masuk Sebagai</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer role-card" :class="role === 'siswa' ? 'active-siswa' : ''">
                            <input type="radio" name="role" value="siswa" x-model="role" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all text-center">
                                <div class="w-12 h-12 mx-auto mb-2 rounded-xl flex items-center justify-center transition-all"
                                     :class="role === 'siswa' ? 'bg-gradient-to-br from-yellow-400 to-yellow-500 shadow-lg' : 'bg-gray-100'">
                                    <i class="fas fa-user-graduate text-2xl" 
                                       :class="role === 'siswa' ? 'text-white' : 'text-gray-600'"></i>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">Siswa</p>
                                <p class="text-xs text-gray-500 mt-1">NISN + Password</p>
                            </div>
                        </label>
                        
                        <label class="cursor-pointer role-card" :class="role === 'guru' ? 'active-guru' : ''">
                            <input type="radio" name="role" value="guru" x-model="role" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                                <div class="w-12 h-12 mx-auto mb-2 rounded-xl flex items-center justify-center transition-all"
                                     :class="role === 'guru' ? 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg' : 'bg-gray-100'">
                                    <i class="fas fa-chalkboard-teacher text-2xl" 
                                       :class="role === 'guru' ? 'text-white' : 'text-gray-600'"></i>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">Guru</p>
                                <p class="text-xs text-gray-500 mt-1">Email + Password</p>
                            </div>
                        </label>
                        
                        <label class="cursor-pointer role-card" :class="role === 'admin' ? 'active-admin' : ''">
                            <input type="radio" name="role" value="admin" x-model="role" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-red-500 peer-checked:bg-red-50 transition-all text-center">
                                <div class="w-12 h-12 mx-auto mb-2 rounded-xl flex items-center justify-center transition-all"
                                     :class="role === 'admin' ? 'bg-gradient-to-br from-red-500 to-red-600 shadow-lg' : 'bg-gray-100'">
                                    <i class="fas fa-shield-alt text-2xl" 
                                       :class="role === 'admin' ? 'text-white' : 'text-gray-600'"></i>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">Admin</p>
                                <p class="text-xs text-gray-500 mt-1">Email + Password</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Email (for Guru & Admin) -->
                <div x-show="role === 'guru' || role === 'admin'" x-cloak class="slide-in">
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2" :class="role === 'guru' ? 'text-blue-500' : 'text-red-500'"></i>Email
                    </label>
                    <div class="relative">
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="<?php echo e(old('email')); ?>" 
                            :required="role === 'guru' || role === 'admin'"
                            :disabled="role === 'siswa'"
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:border-transparent text-gray-900 font-medium"
                            :class="role === 'guru' ? 'focus:ring-blue-500' : 'focus:ring-red-500'"
                        >
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- NISN (for Siswa) -->
                <div x-show="role === 'siswa'" x-cloak class="slide-in">
                    <label for="nisn" class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-2 text-yellow-500"></i>NISN
                    </label>
                    <div class="relative">
                        <input 
                            id="nisn" 
                            type="text" 
                            name="nisn" 
                            value="<?php echo e(old('nisn')); ?>" 
                            :required="role === 'siswa'"
                            :disabled="role !== 'siswa'"
                            autocomplete="nisn"
                            placeholder="Contoh: 0012345678"
                            class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900 font-medium"
                            maxlength="10"
                        >
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                        <i class="fas fa-info-circle text-yellow-500"></i>
                        Masukkan NISN Anda (10 digit)
                    </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2" :class="role === 'siswa' ? 'text-yellow-500' : (role === 'guru' ? 'text-blue-500' : 'text-red-500')"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:border-transparent text-gray-900 font-medium pr-12"
                            :class="role === 'siswa' ? 'focus:ring-yellow-500' : (role === 'guru' ? 'focus:ring-blue-500' : 'focus:ring-red-500')"
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Remember Me & Version -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" 
                            class="rounded border-gray-300 shadow-sm focus:ring w-5 h-5 cursor-pointer"
                            :class="role === 'siswa' ? 'text-yellow-500 focus:ring-yellow-500' : (role === 'guru' ? 'text-blue-600 focus:ring-blue-500' : 'text-red-600 focus:ring-red-500')"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
                    </label>
                    <span class="text-sm text-gray-400 font-semibold">v1.0</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full px-6 py-4 text-white rounded-2xl font-bold text-lg shadow-xl flex items-center justify-center gap-2 group"
                        :class="role === 'siswa' ? 'btn-siswa' : (role === 'guru' ? 'btn-guru' : 'btn-admin')">
                    <i class="fas fa-sign-in-alt group-hover:rotate-12 transition-transform"></i>
                    <span>Masuk</span>
                </button>

                <!-- Help Text -->
                <div class="text-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1" :class="role === 'siswa' ? 'text-yellow-500' : (role === 'guru' ? 'text-blue-500' : 'text-red-500')"></i>
                    Hubungi administrator untuk mendapatkan akun
                </div>
            </form>

            <!-- Demo Credentials -->
            <div class="mt-8 p-6 rounded-2xl border-2 transition-all"
                 :class="role === 'siswa' ? 'bg-gradient-to-br from-yellow-50 to-orange-50 border-yellow-200' : (role === 'guru' ? 'bg-gradient-to-br from-blue-50 to-cyan-50 border-blue-200' : 'bg-gradient-to-br from-red-50 to-gray-50 border-red-200')">
                <p class="text-sm font-bold mb-4 flex items-center gap-2"
                   :class="role === 'siswa' ? 'text-yellow-900' : (role === 'guru' ? 'text-blue-900' : 'text-red-900')">
                    <i class="fas fa-key" :class="role === 'siswa' ? 'text-yellow-600' : (role === 'guru' ? 'text-blue-600' : 'text-red-600')"></i>
                    Demo Credentials:
                </p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-3 bg-white rounded-xl border transition-all hover:shadow-md"
                         :class="role === 'siswa' ? 'border-yellow-200' : (role === 'guru' ? 'border-blue-200' : 'border-red-200')">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shield-alt text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Admin</p>
                                <p class="text-xs text-gray-500">admin@esasyexam.com</p>
                            </div>
                        </div>
                        <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold text-gray-700">password</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-white rounded-xl border transition-all hover:shadow-md"
                         :class="role === 'siswa' ? 'border-yellow-200' : (role === 'guru' ? 'border-blue-200' : 'border-red-200')">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Guru</p>
                                <p class="text-xs text-gray-500">guru@esasyexam.com</p>
                            </div>
                        </div>
                        <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold text-gray-700">password</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-white rounded-xl border transition-all hover:shadow-md"
                         :class="role === 'siswa' ? 'border-yellow-200' : (role === 'guru' ? 'border-blue-200' : 'border-red-200')">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-graduate text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Siswa</p>
                                <p class="text-xs text-gray-500">NISN: 0012345678</p>
                            </div>
                        </div>
                        <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold text-gray-700">password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function loginForm() {
        return {
            role: 'siswa',
            showPassword: false,
        }
    }
    </script>
</body>
</html><?php /**PATH E:\laragon\www\FILE_VSC\EsasyExam\resources\views/auth/login.blade.php ENDPATH**/ ?>
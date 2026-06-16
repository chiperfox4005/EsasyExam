<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EsasyExam - Platform Ujian Online Modern</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary: #0ea5e9;
            --secondary: #6366f1;
            --accent: #10b981;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        }
        
        .gradient-card {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ mobileMenu: false, faqOpen: null }">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 gradient-hero rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">EsasyExam</h1>
                        <p class="text-xs text-gray-500">Smart Learning Platform</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="#fitur" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Fitur</a>
                    <a href="#cara-kerja" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Cara Kerja</a>
                    <a href="#testimoni" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Testimoni</a>
                    <a href="#faq" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">FAQ</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : (auth()->user()->role === 'guru' ? '/guru/dashboard' : '/siswa/dashboard') }}" 
                               class="px-6 py-2.5 gradient-hero text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-6 py-2.5 gradient-hero text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                                Masuk
                            </a>
                        @endauth
                    @endif
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-gray-600">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" class="md:hidden bg-white border-t" x-cloak>
            <div class="px-4 py-3 space-y-3">
                <a href="#fitur" class="block text-gray-600 hover:text-blue-600 font-medium">Fitur</a>
                <a href="#cara-kerja" class="block text-gray-600 hover:text-blue-600 font-medium">Cara Kerja</a>
                <a href="#testimoni" class="block text-gray-600 hover:text-blue-600 font-medium">Testimoni</a>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : (auth()->user()->role === 'guru' ? '/guru/dashboard' : '/siswa/dashboard') }}" 
                       class="block px-4 py-2 gradient-hero text-white rounded-xl font-semibold text-center">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="block px-4 py-2 gradient-hero text-white rounded-xl font-semibold text-center">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-hero relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-white animate-fadeIn">
                    <div class="inline-block px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-semibold mb-6 backdrop-blur-sm">
                        🎓 Platform Ujian Online Terpercaya
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Belajar Lebih Mudah & Menyenangkan
                    </h1>
                    <p class="text-lg md:text-xl text-blue-50 mb-8 leading-relaxed">
                        EsasyExam membantu guru membuat ujian dengan mudah dan siswa belajar dengan lebih interaktif.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : (auth()->user()->role === 'guru' ? '/guru/dashboard' : '/siswa/dashboard') }}" 
                               class="px-8 py-4 bg-white text-blue-600 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all text-center">
                                <i class="fas fa-rocket mr-2"></i> Mulai Sekarang
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-8 py-4 bg-white text-blue-600 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                            </a>
                        @endauth
                        <a href="#fitur" class="px-8 py-4 bg-blue-600 bg-opacity-50 text-white rounded-2xl font-bold text-lg hover:bg-opacity-70 transition-all text-center backdrop-blur-sm">
                            <i class="fas fa-play-circle mr-2"></i> Pelajari Lebih
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-12 border-t border-white border-opacity-20">
                        <div>
                            <p class="text-3xl font-bold">1000+</p>
                            <p class="text-blue-100 text-sm">Guru Aktif</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold">10000+</p>
                            <p class="text-blue-100 text-sm">Siswa</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold">5000+</p>
                            <p class="text-blue-100 text-sm">Ujian Dibuat</p>
                        </div>
                    </div>
                </div>
                
                <div class="hidden lg:block float-animation">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white bg-opacity-20 rounded-3xl transform rotate-6"></div>
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Students Learning" 
                             class="relative rounded-3xl shadow-2xl w-full object-cover h-96">
                        
                        <!-- Floating Cards -->
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-4 shadow-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Ujian Selesai</p>
                                    <p class="text-sm text-gray-500">Nilai: 95/100</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute -top-6 -right-6 bg-white rounded-2xl p-4 shadow-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                    <i class="fas fa-clock text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Waktu Tersisa</p>
                                    <p class="text-sm text-gray-500">15 menit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk pembelajaran dan evaluasi yang lebih efektif</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="gradient-card rounded-3xl p-8 hover-lift border border-blue-100">
                    <div class="w-16 h-16 gradient-hero rounded-2xl flex items-center justify-center text-white text-3xl mb-6 shadow-lg">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Bank Soal Lengkap</h3>
                    <p class="text-gray-600 leading-relaxed">Ribuan soal dengan berbagai tipe: pilihan ganda, essay, dengan dukungan gambar dan multimedia.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="gradient-card rounded-3xl p-8 hover-lift border border-indigo-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 shadow-lg">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">AI Generate Soal</h3>
                    <p class="text-gray-600 leading-relaxed">Buat soal otomatis dengan AI. Cukup masukkan topik, biarkan AI membuat soal berkualitas.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="gradient-card rounded-3xl p-8 hover-lift border border-green-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 shadow-lg">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Anti-Cheat System</h3>
                    <p class="text-gray-600 leading-relaxed">Sistem pengawasan otomatis yang mendeteksi kecurangan saat ujian berlangsung.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="gradient-card rounded-3xl p-8 hover-lift border border-purple-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 shadow-lg">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analisis Mendalam</h3>
                    <p class="text-gray-600 leading-relaxed">Laporan detail performa siswa dengan grafik dan statistik yang mudah dipahami.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="gradient-card rounded-3xl p-8 hover-lift border border-orange-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 shadow-lg">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Akses Dimana Saja</h3>
                    <p class="text-gray-600 leading-relaxed">Platform responsive yang bisa diakses dari desktop, tablet, maupun smartphone.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="gradient-card rounded-3xl p-8 hover-lift border border-pink-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 shadow-lg">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Multi-Role System</h3>
                    <p class="text-gray-600 leading-relaxed">Support untuk Admin, Guru, dan Siswa dengan fitur yang disesuaikan per role.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="cara-kerja" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cara Kerja EsasyExam</h2>
                <p class="text-lg text-gray-600">Mudah dan cepat untuk memulai</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 gradient-hero rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-xl">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Daftar Akun</h3>
                    <p class="text-gray-600 leading-relaxed">Buat akun sesuai role Anda (Guru/Siswa). Proses pendaftaran cepat dan mudah.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 gradient-hero rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-xl">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Buat/Kerjakan Ujian</h3>
                    <p class="text-gray-600 leading-relaxed">Guru membuat ujian, siswa mengerjakan dengan sistem yang user-friendly.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 gradient-hero rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-xl">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Lihat Hasil</h3>
                    <p class="text-gray-600 leading-relaxed">Nilai otomatis keluar dengan analisis detail untuk evaluasi pembelajaran.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimoni" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Apa Kata Mereka?</h2>
                <p class="text-lg text-gray-600">Testimoni dari pengguna EsasyExam</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 hover-lift">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-400"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"EsasyExam sangat membantu saya dalam membuat ujian. Fitur AI generate soal sangat praktis dan menghemat waktu."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            BS
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Budi Santoso</p>
                            <p class="text-sm text-gray-500">Guru Matematika</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 hover-lift">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-400"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"Sebagai siswa, saya suka dengan interface yang mudah digunakan. Sistem anti-cheat juga membuat ujian lebih adil."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                            AR
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Ahmad Rizki</p>
                            <p class="text-sm text-gray-500">Siswa SMA</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 hover-lift">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-400"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"Platform yang sangat profesional. Fitur analisis hasil ujian membantu saya memantau perkembangan siswa dengan baik."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            DS
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Dewi Sari</p>
                            <p class="text-sm text-gray-500">Guru Bahasa Inggris</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-lg text-gray-600">Temukan jawaban atas pertanyaan Anda</p>
            </div>
            
            <div class="space-y-4">
                @php
                    $faqs = [
                        ['q' => 'Apa itu EsasyExam?', 'a' => 'EsasyExam adalah platform ujian online yang membantu guru membuat dan mengelola ujian, serta memudahkan siswa dalam mengerjakan ujian secara digital.'],
                        ['q' => 'Bagaimana cara mendaftar?', 'a' => 'Klik tombol "Masuk" di pojok kanan atas, lalu hubungi administrator sekolah untuk mendapatkan akun.'],
                        ['q' => 'Apakah bisa diakses dari HP?', 'a' => 'Ya, EsasyExam responsive dan bisa diakses dari smartphone, tablet, maupun desktop.'],
                        ['q' => 'Apakah ada biaya?', 'a' => 'EsasyExam tersedia dalam berbagai paket. Hubungi kami untuk informasi harga dan paket yang tersedia.'],
                        ['q' => 'Bagaimana sistem anti-cheat bekerja?', 'a' => 'Sistem kami mendeteksi perpindahan tab, copy-paste, dan aktivitas mencurigakan lainnya selama ujian berlangsung.']
                    ];
                @endphp
                
                @foreach($faqs as $index => $faq)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <button @click="faqOpen = faqOpen === {{ $index }} ? null : {{ $index }}" 
                                class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                            <span class="font-bold text-gray-900">{{ $faq['q'] }}</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" :class="faqOpen === {{ $index }} ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="faqOpen === {{ $index }}" x-collapse class="px-6 pb-4 text-gray-600 leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-hero">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Memulai Pembelajaran Digital?</h2>
            <p class="text-xl text-blue-100 mb-8">Bergabunglah dengan ribuan guru dan siswa yang sudah menggunakan EsasyExam</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : (auth()->user()->role === 'guru' ? '/guru/dashboard' : '/siswa/dashboard') }}" 
                       class="px-8 py-4 bg-white text-blue-600 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all">
                        <i class="fas fa-rocket mr-2"></i> Mulai Sekarang
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-8 py-4 bg-white text-blue-600 rounded-2xl font-bold text-lg hover:shadow-2xl transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 gradient-hero rounded-xl flex items-center justify-center text-white font-bold text-xl">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">EsasyExam</h3>
                            <p class="text-xs text-gray-400">Smart Learning Platform</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Platform ujian online modern untuk pembelajaran yang lebih efektif dan menyenangkan.</p>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="#testimoni" class="hover:text-white transition-colors">Testimoni</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><i class="fas fa-envelope mr-2"></i> info@esasyexam.com</li>
                        <li><i class="fas fa-phone mr-2"></i> 021-12345678</li>
                        <li class="flex gap-3 mt-4">
                            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors"><i class="fab fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} EsasyExam. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
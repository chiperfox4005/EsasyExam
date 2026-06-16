<header class="bg-white border-b border-gray-100 sticky top-0 z-30">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Mobile Menu Button -->
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors"
        >
            <i class="fas fa-bars text-gray-600 text-xl"></i>
        </button>
        
        <!-- Search -->
        <div class="flex-1 max-w-xl mx-4 hidden md:block">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    placeholder="Cari fitur, menu, atau konten..." 
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <button class="relative p-3 rounded-2xl hover:bg-gray-50 transition-colors">
                <i class="fas fa-bell text-gray-600 text-lg"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full pulse-ring"></span>
            </button>
            
            <!-- Settings -->
            <button class="p-3 rounded-2xl hover:bg-gray-50 transition-colors">
                <i class="fas fa-cog text-gray-600 text-lg"></i>
            </button>
            
            <!-- 🔥 PROFILE DROPDOWN DENGAN LOGOUT YANG PASTI BERFUNGSI -->
            <div class="relative">
                <button 
                    onclick="toggleProfileDropdown()"
                    type="button"
                    class="flex items-center gap-3 p-2 rounded-2xl hover:bg-gray-50 transition-colors"
                >
                    <!-- Avatar -->
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/30">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-gray-500 capitalize"><?php echo e(ucfirst(auth()->user()->role)); ?></p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
                
                <!-- Dropdown Menu (Native JavaScript, BUKAN Alpine.js) -->
                <div 
                    id="profileDropdown"
                    class="hidden absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border-2 border-gray-200 py-2 z-50"
                >
                    <!-- User Info Header -->
                    <div class="px-4 py-3 border-b-2 border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <p class="text-sm font-bold text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-gray-600 truncate">
                            <?php echo e(auth()->user()->email ?? auth()->user()->nisn ?? '-'); ?>

                        </p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-700 capitalize">
                            <?php echo e(auth()->user()->role); ?>

                        </span>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                            <i class="fas fa-user w-5 text-blue-500"></i>
                            <span class="font-medium">Profil Saya</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                            <i class="fas fa-cog w-5 text-gray-500"></i>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                            <i class="fas fa-question-circle w-5 text-gray-500"></i>
                            <span class="font-medium">Bantuan</span>
                        </a>
                    </div>
                    
                    <hr class="border-gray-200">
                    
                    <!-- 🔥 LOGOUT BUTTON - Native JavaScript -->
                    <div class="py-2">
                        <button 
                            type="button"
                            onclick="handleLogout()"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors font-semibold"
                        >
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Keluar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- 🔥 FORM LOGOUT TERPISAH (DI LUAR DROPDOWN) -->
<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>

<!-- 🔥 JAVASCRIPT NATIVE (BUKAN Alpine.js) -->
<script>
// Toggle dropdown
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when click outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const button = event.target.closest('button[onclick="toggleProfileDropdown()"]');
    
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

// Handle logout
function handleLogout() {
    if (confirm('Yakin ingin keluar dari akun?')) {
        document.getElementById('logout-form').submit();
    }
}
</script><?php /**PATH E:\laragon\www\FILE_VSC\EsasyExam\resources\views/components/topbar.blade.php ENDPATH**/ ?>
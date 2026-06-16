<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo e(config('app.name', 'EsasyExam')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    <style>
        :root {
            --primary: #2563EB;
            --secondary: #FACC15;
            --dark: #0F172A;
            --background: #F8FAFC;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #2563EB 0%, #3B82F6 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #FACC15 0%, #FDE047 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }
        
        .gradient-danger {
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }
        
        .pulse-ring {
            animation: pulse-ring 2s infinite;
        }
        
        [x-cloak] { display: none !important; }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <?php echo $__env->make('components.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Page Content -->
            <main class="flex-1 p-6 lg:p-8">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Chart.js Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('userChart');
            if (chartCanvas) {
                const ctx = chartCanvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Guru',
                            data: [12, 19, 15, 25, 22, 30],
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }, {
                            label: 'Siswa',
                            data: [50, 80, 70, 120, 100, 150],
                            borderColor: '#FACC15',
                            backgroundColor: 'rgba(250, 204, 21, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                padding: 12,
                                cornerRadius: 12
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        },
                        animation: {
                            duration: 800,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }
        });
    </script>
</body>
</html><?php /**PATH E:\laragon\www\FILE_VSC\EsasyExam\resources\views/layouts/app.blade.php ENDPATH**/ ?>
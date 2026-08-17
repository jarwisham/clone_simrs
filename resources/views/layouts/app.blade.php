<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Kepegawaian & Presensi - RSUP Makassar</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#e6f7f5',
                            100: '#ccefe9',
                            200: '#99dfd3',
                            300: '#66cfbe',
                            400: '#33bfa8',
                            500: '#00a896',
                            600: '#008778',
                            700: '#00655a',
                            800: '#00433c',
                            900: '#00221e',
                        },
                        tealAccent: '#02c39a',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            -webkit-tap-highlight-color: transparent;
        }

        /* Custom Pattern Background for Attendance Card */
        .bg-pattern-circles {
            background-color: #00a896;
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 40%),
                radial-gradient(circle at 20% 80%, rgba(2, 195, 154, 0.3) 0%, rgba(2, 195, 154, 0) 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0) 60%);
        }

        .bg-pattern-overlay {
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 2px, transparent 2px);
            background-size: 24px 24px;
        }

        /* Glassmorphism Effect */
        .glass-btn {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Hide scrollbars */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased min-h-screen text-slate-800 flex justify-center items-start">

    <!-- Container Wrap: Works mobile screen centered or desktop full width -->
    <div class="w-full max-w-md min-h-screen bg-slate-50 shadow-2xl relative flex flex-col pb-24 border-x border-slate-200">
        
        @yield('content')

    </div>

    <!-- Notification Toast container -->
    <div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs space-y-2 pointer-events-none"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-emerald-600' : 'bg-rose-600';
            
            toast.className = `${bgColor} text-white px-4 py-3 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 transform translate-y-[-20px] opacity-0 text-sm font-semibold pointer-events-auto`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-y-[-20px]', 'opacity-0');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'scale-95');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }
    </script>
</body>
</html>

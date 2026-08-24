<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Social Commerce UMKM Desa Bojongsawah')</title>
    <meta name="description" content="Platform Social Commerce UMKM Desa Bojongsawah. Temukan produk lokal berkualitas dan pesan langsung via WhatsApp.">
    
    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-full font-sans antialiased text-slate-800 bg-slate-50">

    <!-- Navigation Bar -->
    @include('components.navbar')

    <!-- Flash Notification Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                    <span class="font-medium text-sm md:text-base">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if (session('warning'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
                    <span class="font-medium text-sm md:text-base">{{ session('warning') }}</span>
                </div>
                <button @click="show = false" class="text-amber-500 hover:text-amber-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-xmark text-rose-600 text-xl"></i>
                    <span class="font-medium text-sm md:text-base">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if (session('info'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-info text-sky-600 text-xl"></i>
                    <span class="font-medium text-sm md:text-base">{{ session('info') }}</span>
                </div>
                <button @click="show = false" class="text-sky-500 hover:text-sky-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>

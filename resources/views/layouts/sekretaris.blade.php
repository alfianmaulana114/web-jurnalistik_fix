<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sekretaris</title>
    <!-- Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    @stack('styles')

    
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white text-gray-800 w-64 border-r border-gray-200 shadow-sm py-6 px-4 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-30">
            <div class="flex items-center justify-center space-x-2 px-4 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-blue-600">PARAGRAF MUDA</h2>
                    <p class="text-xs text-gray-500">Portal Sekretaris</p>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="space-y-2">
                <a href="{{ route('sekretaris.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('sekretaris.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>Dashboard
                </a>
                
                <!-- Notulensi -->
                <a href="{{ route('sekretaris.notulensi.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('sekretaris.notulensi.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-alt mr-3 w-5 text-center"></i>Notulensi Rapat
                </a>
                
                <!-- Proker -->
                <a href="{{ route('sekretaris.proker.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('sekretaris.proker.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-calendar-check mr-3 w-5 text-center"></i>Program Kerja
                </a>
                
                <!-- Absen -->
                <a href="{{ route('sekretaris.absen.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('sekretaris.absen.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-user-check mr-3 w-5 text-center"></i>Absen Anggota
                </a>
                
                <hr class="my-4 border-gray-200">

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" data-logout-form>
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-red-50 text-gray-600 hover:text-red-600" data-logout-button>
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top header -->
            <header class="bg-white/80 backdrop-blur border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden focus:outline-none" aria-controls="sidebar" aria-expanded="false">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>

                    <div class="flex items-center space-x-4">
                        <h1 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-user-circle mr-2"></i>{{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto space-y-6">
                    @include('partials.flash')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        // Global double-click protection HANYA untuk navigasi
        // TIDAK proteksi untuk form submit (create/edit/update/delete)
        (function() {
            const clickDelay = 500; // 500ms delay antara klik
            const clickTimes = new WeakMap();

            // Proteksi hanya untuk link navigasi
            document.addEventListener('click', function(e) {
                const target = e.target;
                const clickable = target.closest('a[href], button[type="button"]:not([onclick])');
                
                if (clickable) {
                    // Skip untuk elemen tertentu yang perlu multiple click
                    // Skip untuk semua button di dalam modal
                    if (clickable.id === 'mobile-menu-button' || 
                        clickable.closest('#user-menu') ||
                        clickable.hasAttribute('data-logout-button') ||
                        clickable.closest('[data-logout-form]') ||
                        clickable.closest('form[action*="logout"]') ||
                        clickable.closest('[id$="Modal"]') ||
                        clickable.closest('.modal') ||
                        clickable.hasAttribute('onclick')) {
                        return;
                    }

                    // Hanya proteksi untuk link navigasi (bukan form submit)
                    if (clickable.tagName === 'A' && clickable.href) {
                        const currentTime = Date.now();
                        const lastClickTime = clickTimes.get(clickable) || 0;
                        
                        if (currentTime - lastClickTime < clickDelay) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                        
                        clickTimes.set(clickable, currentTime);
                        
                        // Untuk link, tambahkan class untuk mencegah multiple click
                        if (clickable.classList.contains('processing')) {
                            e.preventDefault();
                            return false;
                        }
                        
                        clickable.classList.add('processing');
                        setTimeout(function() {
                            clickable.classList.remove('processing');
                        }, clickDelay);
                    }
                }
            }, true);
        })();
    </script>

    @stack('scripts')
</body>
</html>


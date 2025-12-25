<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Koordinator Redaksi</title>
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
    <div class="flex min-h-screen overflow-x-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white text-gray-800 w-64 border-r border-gray-200 shadow-sm py-6 px-4 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-30">
            <div class="flex items-center justify-center space-x-2 px-4 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-[#1b334e]">PARAGRAF MUDA</h2>
                    <p class="text-xs text-gray-500">Portal Koordinator Redaksi</p>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="space-y-2">
                <a href="{{ route('koordinator-redaksi.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.dashboard') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>Dashboard
                </a>
                
                <!-- Manajemen Berita -->
                <a href="{{ route('koordinator-redaksi.news.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.news.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-newspaper mr-3 w-5 text-center"></i>Berita
                </a>
                
                <!-- Penjadwalan -->
                <a href="{{ route('koordinator-redaksi.penjadwalan.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.penjadwalan.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>Penjadwalan
                </a>
                
                <!-- Funfact -->
                <a href="{{ route('koordinator-redaksi.funfacts.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.funfacts.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-lightbulb mr-3 w-5 text-center"></i>Funfact
                </a>
                
                <!-- Content Management (Read-Only) -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Konten</p>
                </div>
                
                <a href="{{ route('koordinator-redaksi.view.contents.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.contents.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-quote-right mr-3 w-5 text-center"></i>Caption
                </a>
                
                <a href="{{ route('koordinator-redaksi.view.designs.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.designs.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-image mr-3 w-5 text-center"></i>Design
                </a>

                <!-- Briefs -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Brief</p>
                </div>
                
                <a href="{{ route('koordinator-redaksi.view.briefs.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.briefs.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-alt mr-3 w-5 text-center"></i>Brief Litbang
                </a>
                
                <a href="{{ route('koordinator-redaksi.view.brief-humas.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.brief-humas.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-bullhorn mr-3 w-5 text-center"></i>Brief Humas
                </a>

                <!-- Manajemen -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manajemen</p>
                </div>
                
                <a href="{{ route('koordinator-redaksi.view.prokers.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.prokers.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>Program Kerja
                </a>
                
                <a href="{{ route('koordinator-redaksi.view.users.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.users.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>User
                </a>

                <!-- Keuangan -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Keuangan</p>
                </div>
                
                <a href="{{ route('koordinator-redaksi.view.laporan.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.laporan.index') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-invoice-dollar mr-3 w-5 text-center"></i>Laporan Keuangan
                </a>

                <!-- Sekretaris (Read-Only) -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sekretaris</p>
                </div>
                
                <a href="{{ route('koordinator-redaksi.view.sekretaris.notulensi.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.sekretaris.notulensi.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-alt mr-3 w-5 text-center"></i>Notulensi Rapat
                </a>
                
                <a href="{{ route('koordinator-redaksi.view.sekretaris.absen.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-redaksi.view.sekretaris.absen.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
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
        <div id="backdrop" class="fixed inset-0 bg-black/30 hidden md:hidden z-20"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top header -->
            <header class="bg-white/80 backdrop-blur border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden focus:outline-none" aria-controls="sidebar" aria-expanded="false">
                        <i class="fas fa-bars text-gray-600 text-lg"></i>
                    </button>

                    <!-- Header title -->
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header')</h2>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                            <span class="text-gray-700">{{ auth()->user()->name ?? 'Koordinator Redaksi' }}</span>
                            <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>Profil
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i>Pengaturan
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}" class="block" data-logout-form>
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-logout-button>
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6">
                <div class="max-w-7xl mx-auto space-y-6">
                    @include('partials.flash')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('backdrop');
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            if (backdrop) backdrop.classList.remove('hidden');
            if (mobileMenuButton) mobileMenuButton.setAttribute('aria-expanded', 'true');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
            if (mobileMenuButton) mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
        if (mobileMenuButton) mobileMenuButton.addEventListener('click', function() {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) closeSidebar(); else openSidebar();
        });
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
        document.getElementById('user-menu-button')?.addEventListener('click', function() {
            document.getElementById('user-menu')?.classList.toggle('hidden');
        });
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userMenuButton = document.getElementById('user-menu-button');
            if (!userMenuButton || !userMenu) return;
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Global double-click protection HANYA untuk navigasi dan form create
        // TIDAK proteksi untuk form edit/update/delete
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
                        clickable.id === 'user-menu-button' ||
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


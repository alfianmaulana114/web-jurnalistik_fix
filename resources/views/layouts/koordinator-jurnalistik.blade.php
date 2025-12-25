<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Koordinator Jurnalistik</title>
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
                    <p class="text-xs text-gray-500">Portal Koordinator</p>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="space-y-2">
                <a href="{{ route('koordinator-jurnalistik.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.dashboard') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>Dashboard
                </a>
                
                <!-- Manajemen Berita -->
                <a href="{{ route('koordinator-jurnalistik.news.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.news.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-newspaper mr-3 w-5 text-center"></i>Berita
                </a>
                
                <!-- Manajemen Funfact -->
                <a href="{{ route('koordinator-jurnalistik.funfacts.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.funfacts.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-lightbulb mr-3 w-5 text-center"></i>Funfact
                </a>

                <!-- Konten -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Konten</p>
                </div>
                
                <a href="{{ route('koordinator-jurnalistik.contents.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.contents.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-closed-captioning mr-3 w-5 text-center"></i>Caption
                </a>
                
                <a href="{{ route('koordinator-jurnalistik.designs.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.designs.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-palette mr-3 w-5 text-center"></i>Desain Media
                </a>

                <!-- Brief -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Brief</p>
                </div>
                
                <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.briefs.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-alt mr-3 w-5 text-center"></i>Brief Litbang
                </a>
                
                <a href="{{ route('koordinator-jurnalistik.brief-humas.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.brief-humas.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-bullhorn mr-3 w-5 text-center"></i>Brief Humas
                </a>

                <!-- Manajemen -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manajemen</p>
                </div>
                
                <a href="{{ route('koordinator-jurnalistik.prokers.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.prokers.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>Program Kerja
                </a>
                
                <a href="{{ route('koordinator-jurnalistik.users.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.users.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>Manajemen User
                </a>
                
                <!-- Keuangan -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Keuangan</p>
                </div>
                <!-- Riwayat Kas Anggota -->
                <a href="{{ route('koordinator-jurnalistik.kas-anggota.riwayat') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.kas-anggota.riwayat') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-wallet mr-3 w-5 text-center"></i>Riwayat Kas Anggota
                </a>
                <!-- Laporan Keuangan -->
                <a href="{{ route('koordinator-jurnalistik.laporan.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.laporan.index') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-invoice-dollar mr-3 w-5 text-center"></i>Laporan Keuangan
                </a>

                <!-- Sekretaris -->
                <div class="px-4 py-2 mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sekretaris</p>
                </div>
                
                <a href="{{ route('koordinator-jurnalistik.sekretaris.notulensi.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.sekretaris.notulensi.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-alt mr-3 w-5 text-center"></i>Notulensi Rapat
                </a>
                
                <a href="{{ route('koordinator-jurnalistik.sekretaris.absen.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-[#1b334e]/10 hover:text-[#1b334e] {{ request()->routeIs('koordinator-jurnalistik.sekretaris.absen.*') ? 'bg-[#1b334e]/10 text-[#1b334e] font-medium' : 'text-gray-600' }}">
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
                            <span class="text-gray-700">{{ auth()->user()->name ?? 'Koordinator' }}</span>
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
        (function() {
            const clickDelay = 500;
            const clickTimes = new WeakMap();
            document.addEventListener('click', function(e) {
                const target = e.target;
                const clickable = target.closest('a[href], button[type="button"]:not([onclick])');
                if (clickable) {
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
                    if (clickable.tagName === 'A' && clickable.href) {
                        const currentTime = Date.now();
                        const lastClickTime = clickTimes.get(clickable) || 0;
                        if (currentTime - lastClickTime < clickDelay) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                        clickTimes.set(clickable, currentTime);
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Koordinator Jurnalistik</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    @stack('styles')

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white text-gray-800 w-64 shadow-lg py-6 px-4 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-10">
            <div class="flex items-center justify-center space-x-2 px-4 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-blue-600">PARAGRAF MUDA</h2>
                    <p class="text-xs text-gray-500">Portal Koordinator</p>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="space-y-2">
                <a href="{{ route('koordinator-jurnalistik.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>Dashboard
                </a>
                
                <!-- Manajemen Program Kerja -->
                <a href="{{ route('koordinator-jurnalistik.prokers.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.prokers.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>Program Kerja
                </a>
                
                <!-- Manajemen Brief -->
                <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.briefs.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-file-alt mr-3 w-5 text-center"></i>Brief
                </a>
                
                <!-- Manajemen Berita -->
                <a href="{{ route('koordinator-jurnalistik.news.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.news.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-newspaper mr-3 w-5 text-center"></i>Berita
                </a>
                
                <!-- Manajemen Caption -->
                <a href="{{ route('koordinator-jurnalistik.contents.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.contents.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-closed-captioning mr-3 w-5 text-center"></i>Caption
                </a>
                
                <!-- Manajemen Desain -->
                <a href="{{ route('koordinator-jurnalistik.designs.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.designs.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-palette mr-3 w-5 text-center"></i>Desain Media
                </a>
                
                <!-- Manajemen User -->
                <a href="{{ route('koordinator-jurnalistik.users.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('koordinator-jurnalistik.users.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>Manajemen User
                </a>
                
                <hr class="my-4 border-gray-200">
                
                <!-- Link ke Website -->
                <a href="{{ route('home') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 text-gray-600">
                    <i class="fas fa-globe mr-3 w-5 text-center"></i>Lihat Website
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-red-50 text-gray-600 hover:text-red-600">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden focus:outline-none">
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
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });

        // User menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userMenuButton = document.getElementById('user-menu-button');
            
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
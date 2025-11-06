<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
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
                    <p class="text-xs text-gray-500">Portal Admin</p>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>Dashboard
                </a>
                <a href="{{ route('admin.news.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.news.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-newspaper mr-3 w-5 text-center"></i>Manajemen Berita
                </a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.comments.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600' }}">
                    <i class="fas fa-comments mr-3 w-5 text-center"></i>Manajemen Komentar
                </a>
                <a href="{{ route('home') }}" class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 hover:bg-blue-50 hover:text-blue-600 text-gray-600">
                    <i class="fas fa-globe mr-3 w-5 text-center"></i>Lihat Website
                </a>
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

                    <!-- Admin dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                            <span class="text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
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
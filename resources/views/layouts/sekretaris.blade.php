<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sekretaris</title>
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
                        primary: '#3b82f6',
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
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>

    @stack('scripts')
</body>
</html>


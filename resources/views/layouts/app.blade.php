<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Jurnalistik</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Header -->

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->


    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
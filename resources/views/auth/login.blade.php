<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UKM Jurnalistik</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        journalism: '#dc2626',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-red-100">
                <i class="fas fa-newspaper text-red-600 text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                UKM Jurnalistik
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Masuk ke sistem manajemen
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <!-- Debug Information on Login Page -->
            @if(session('login_failed'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Debug:</strong> {{ session('login_failed') }}
                </div>
            @endif
            
            @if(session('login_attempt'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    <strong>Last Attempt:</strong> {{ session('login_attempt')['email'] }} at {{ session('login_attempt')['timestamp'] }}
                </div>
            @endif
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm @error('email') border-red-300 @enderror" 
                           placeholder="Email address" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm @error('password') border-red-300 @enderror" 
                           placeholder="Password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock text-red-500 group-hover:text-red-400"></i>
                    </span>
                    Masuk
                </button>
            </div>
            
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-red-600 hover:text-red-500 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Website
                </a>
            </div>
        </form>
        
        <!-- Demo Credentials -->
        <div class="mt-6 p-4 bg-blue-50 rounded-md">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Demo Credentials:</h3>
            <div class="text-xs text-blue-700 space-y-1">
                <p><strong>Koordinator:</strong> koordinator@jurnalistik.com / password</p>
                <p><strong>Bendahara:</strong> bendahara@jurnalistik.com / password</p>
                <p><strong>Sekretaris:</strong> sekretaris@jurnalistik.com / password</p>
                <p><strong>Redaksi:</strong> koor.redaksi@jurnalistik.com / password</p>
                <p><strong>Anggota:</strong> redaksi1@jurnalistik.com / password</p>
            </div>
        </div>
    </div>
</body>
</html>
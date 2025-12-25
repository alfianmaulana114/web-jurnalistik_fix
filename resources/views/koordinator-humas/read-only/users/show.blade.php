@extends($layout ?? 'layouts.koordinator-humas')

@section('title', 'Detail User')
@section('header', 'Detail User (Read-Only)')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Read-Only Banner --}}
    <div class="mb-4 rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow mb-6 border border-[#D8C4B6]/40">
        <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-r from-[#1b334e] to-[#1b334e] rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#1b334e]">{{ $user->name }}</h1>
                        <div class="flex items-center space-x-4 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if(str_contains($user->role, 'koordinator'))
                                    bg-purple-100 text-purple-800
                                @elseif(str_contains($user->role, 'anggota'))
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-green-100 text-green-800
                                @endif
                            ">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $user->email }}</span>
                            <span class="text-sm text-gray-500">NIM: {{ $user->nim }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route(($routePrefix ?? 'koordinator-humas.view').'.users.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Informasi Dasar</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIM</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->nim }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $user->email }}" class="text-[#1b334e] hover:text-[#f9b61a]">{{ $user->email }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-[#1b334e] hover:text-[#f9b61a]">{{ $user->phone }}</a>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Angkatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->angkatan ?? 'Tidak diketahui' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bergabung</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                    </dl>
                    
                    @if($user->bio)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Bio/Deskripsi</dt>
                        <dd class="mt-2 text-sm text-gray-900 bg-gray-50 rounded-lg p-4">{{ $user->bio }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Informasi Cepat</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aktif
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Role</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Bergabung</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Terakhir Update</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.koordinator-jurnalistik')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola anggota UKM Jurnalistik</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('koordinator-jurnalistik.users.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total User</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->total() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Koordinator -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Koordinator</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $koordinatorCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Anggota -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-user-friends text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Anggota</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $anggotaCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pengurus -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pengurus</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pengurusCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('koordinator-jurnalistik.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Nama, email, atau NIM">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Semua Role</option>
                    <option value="koordinator_jurnalistik" {{ request('role') === 'koordinator_jurnalistik' ? 'selected' : '' }}>Koordinator Jurnalistik</option>
                    <option value="koordinator_redaksi" {{ request('role') === 'koordinator_redaksi' ? 'selected' : '' }}>Koordinator Redaksi</option>
                    <option value="koordinator_litbang" {{ request('role') === 'koordinator_litbang' ? 'selected' : '' }}>Koordinator Litbang</option>
                    <option value="koordinator_humas" {{ request('role') === 'koordinator_humas' ? 'selected' : '' }}>Koordinator Humas</option>
                    <option value="koordinator_media_kreatif" {{ request('role') === 'koordinator_media_kreatif' ? 'selected' : '' }}>Koordinator Media Kreatif</option>
                    <option value="anggota_redaksi" {{ request('role') === 'anggota_redaksi' ? 'selected' : '' }}>Anggota Redaksi</option>
                    <option value="anggota_litbang" {{ request('role') === 'anggota_litbang' ? 'selected' : '' }}>Anggota Litbang</option>
                    <option value="anggota_humas" {{ request('role') === 'anggota_humas' ? 'selected' : '' }}>Anggota Humas</option>
                    <option value="anggota_media_kreatif" {{ request('role') === 'anggota_media_kreatif' ? 'selected' : '' }}>Anggota Media Kreatif</option>
                    <option value="sekretaris" {{ request('role') === 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                    <option value="bendahara" {{ request('role') === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                </select>
            </div>

            <div>
                <label for="divisi" class="block text-sm font-medium text-gray-700">Divisi</label>
                <select name="divisi" id="divisi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Semua Divisi</option>
                    <option value="redaksi" {{ request('divisi') === 'redaksi' ? 'selected' : '' }}>Redaksi</option>
                    <option value="litbang" {{ request('divisi') === 'litbang' ? 'selected' : '' }}>Litbang</option>
                    <option value="humas" {{ request('divisi') === 'humas' ? 'selected' : '' }}>Humas</option>
                    <option value="media_kreatif" {{ request('divisi') === 'media_kreatif' ? 'selected' : '' }}>Media Kreatif</option>
                    <option value="pengurus" {{ request('divisi') === 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('koordinator-jurnalistik.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIM
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role & Divisi
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aktivitas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bergabung
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-red-600">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->nim }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @php
                                            $roleLabels = [
                                                'koordinator_jurnalistik' => 'Koordinator Jurnalistik',
                                                'koordinator_redaksi' => 'Koordinator Redaksi',
                                                'koordinator_litbang' => 'Koordinator Litbang',
                                                'koordinator_humas' => 'Koordinator Humas',
                                                'koordinator_media_kreatif' => 'Koordinator Media Kreatif',
                                                'anggota_redaksi' => 'Anggota Redaksi',
                                                'anggota_litbang' => 'Anggota Litbang',
                                                'anggota_humas' => 'Anggota Humas',
                                                'anggota_media_kreatif' => 'Anggota Media Kreatif',
                                                'sekretaris' => 'Sekretaris',
                                                'bendahara' => 'Bendahara',
                                            ];
                                            
                                            $roleColors = [
                                                'koordinator_jurnalistik' => 'bg-red-100 text-red-800',
                                                'koordinator_redaksi' => 'bg-blue-100 text-blue-800',
                                                'koordinator_litbang' => 'bg-green-100 text-green-800',
                                                'koordinator_humas' => 'bg-yellow-100 text-yellow-800',
                                                'koordinator_media_kreatif' => 'bg-purple-100 text-purple-800',
                                                'anggota_redaksi' => 'bg-blue-50 text-blue-700',
                                                'anggota_litbang' => 'bg-green-50 text-green-700',
                                                'anggota_humas' => 'bg-yellow-50 text-yellow-700',
                                                'anggota_media_kreatif' => 'bg-purple-50 text-purple-700',
                                                'sekretaris' => 'bg-indigo-100 text-indigo-800',
                                                'bendahara' => 'bg-pink-100 text-pink-800',
                                            ];
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="space-y-1">
                                        @if($user->contents_count ?? 0 > 0)
                                            <div class="text-xs">{{ $user->contents_count }} Konten</div>
                                        @endif
                                        @if($user->briefs_count ?? 0 > 0)
                                            <div class="text-xs">{{ $user->briefs_count }} Brief</div>
                                        @endif
                                        @if($user->designs_count ?? 0 > 0)
                                            <div class="text-xs">{{ $user->designs_count }} Desain</div>
                                        @endif
                                        @if(($user->contents_count ?? 0) == 0 && ($user->briefs_count ?? 0) == 0 && ($user->designs_count ?? 0) == 0)
                                            <div class="text-xs text-gray-400">Belum ada aktivitas</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('koordinator-jurnalistik.users.show', $user) }}" class="text-red-600 hover:text-red-900" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('koordinator-jurnalistik.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('koordinator-jurnalistik.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada user ditemukan</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'role', 'divisi']))
                        Coba ubah filter pencarian Anda.
                    @else
                        Mulai dengan menambahkan user baru.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'role', 'divisi']))
                    <a href="{{ route('koordinator-jurnalistik.users.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah User Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterForm = document.querySelector('form');
    const filterInputs = filterForm.querySelectorAll('select, input[name="search"]');
    
    filterInputs.forEach(input => {
        if (input.type === 'text') {
            // For search input, use debounce
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    filterForm.submit();
                }, 500);
            });
        } else {
            // For select inputs, submit immediately
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });
});
</script>
@endpush
@endsection
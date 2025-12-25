@extends($layout ?? 'layouts.sekretaris')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User (Read-Only)')

@section('content')
<div class="space-y-6">
    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Manajemen User</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat anggota UKM Jurnalistik (Read-Only)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total User</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $users->total() ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Koordinator</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $users->whereIn('role', ['koordinator_jurnalistik', 'koordinator_redaksi', 'koordinator_litbang', 'koordinator_humas', 'koordinator_media_kreatif'])->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-user-friends text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Anggota</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $users->whereIn('role', ['anggota_redaksi', 'anggota_litbang', 'anggota_humas', 'anggota_media_kreatif'])->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pengurus</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $users->whereIn('role', ['sekretaris', 'bendahara'])->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route(($routePrefix ?? 'sekretaris.view').'.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm" placeholder="Nama, email, atau NIM">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
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
                <select name="divisi" id="divisi" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                    <option value="">Semua Divisi</option>
                    <option value="redaksi" {{ request('divisi') === 'redaksi' ? 'selected' : '' }}>Redaksi</option>
                    <option value="litbang" {{ request('divisi') === 'litbang' ? 'selected' : '' }}>Litbang</option>
                    <option value="humas" {{ request('divisi') === 'humas' ? 'selected' : '' }}>Humas</option>
                    <option value="media_kreatif" {{ request('divisi') === 'media_kreatif' ? 'selected' : '' }}>Media Kreatif</option>
                    <option value="pengurus" {{ request('divisi') === 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
            <h3 class="text-lg font-medium text-[#1b334e]">Daftar User</h3>
        </div>
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                    <thead class="bg-[#f9b61a]/5">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role & Divisi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="usersTableBody">
                        @foreach($users as $user)
                            <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-[#f9b61a]/10 flex items-center justify-center">
                                                <span class="text-sm font-medium text-[#f9b61a]">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-[#1b334e]">{{ $user->name }}</div>
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
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.users.show', $user) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-4 py-3 border-t border-[#D8C4B6]/40 sm:px-6">
                {{ $users->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-[#1b334e] mb-2">Tidak ada user ditemukan</h3>
                <p class="text-gray-500">Belum ada user yang tersedia</p>
            </div>
        @endif
    </div>
</div>
@endsection

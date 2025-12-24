{{-- Shadcn-Inspired News Index - Koordinator Jurnalistik --}}
@extends('layouts.koordinator-jurnalistik')

@section('title', 'Manajemen Berita')
@section('header', 'Manajemen Berita')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Manajemen Berita</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola semua berita dan publikasi UKM Jurnalistik</p>
        </div>
        <a href="{{ route('koordinator-jurnalistik.news.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Berita
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Berita</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $news->total() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Disetujui</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $news->filter(fn($n) => $n->approval()->exists())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Views</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($news->sum('views')) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route('koordinator-jurnalistik.news.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Judul atau isi berita..." class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category" id="category" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\NewsCategory::all() as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="approval" class="block text-sm font-medium text-gray-700">Status Persetujuan</label>
                <select name="approval" id="approval" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="approved" {{ request('approval') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="pending" {{ request('approval') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('koordinator-jurnalistik.news.index') }}" class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md">
        <div class="border-b border-[#D8C4B6]/40 p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Daftar Berita</h3>
                <span class="text-sm text-gray-600">{{ $news->total() }} berita</span>
            </div>
        </div>
        
        {{-- Table --}}
        @if($news->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Berita</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8C4B6]/40 bg-white" id="newsTableBody">
                    @foreach($news as $item)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-12 w-12 rounded-lg object-cover">
                                @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $item->title }}</p>
                                    <p class="truncate text-xs text-gray-500">{{ Str::limit(strip_tags($item->content), 50) }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $item->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center rounded-full bg-[#1b334e] px-2 py-0.5 text-xs font-medium text-white">
                                    {{ $item->category?->name ?? 'Tidak ada' }}
                                </span>
                                @if($item->type)
                                <span class="inline-flex items-center rounded-full bg-[#f9b61a]/10 px-2 py-0.5 text-xs font-medium text-[#1b334e]">
                                    {{ $item->type->name }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#f9b61a]/10 text-xs font-semibold text-[#1b334e]">
                                    {{ strtoupper(substr($item->user?->name ?? '?', 0, 2)) }}
                                </div>
                                <span class="text-sm text-gray-900">{{ $item->user?->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="text-sm text-gray-900">{{ number_format($item->views) }}</span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @php
                                $approval = $item->approval()->with('user')->first();
                                $isAllowed = auth()->check() && in_array(auth()->user()->role, [\App\Models\User::ROLE_KOORDINATOR_JURNALISTIK, \App\Models\User::ROLE_KOORDINATOR_REDAKSI, \App\Models\User::ROLE_ANGGOTA_REDAKSI]);
                            @endphp
                            @if($approval)
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Disetujui
                                    </span>
                                    <span class="text-xs text-gray-500">oleh {{ $approval->user->name ?? 'Unknown' }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pending
                                    </span>
                                    @if($isAllowed)
                                        <form action="{{ route('news.approve', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-[#f9b61a]/10 px-2 py-1 text-xs font-medium text-[#1b334e] hover:bg-[#f9b61a] hover:text-white transition-all">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('news.show', $item->slug) }}" class="rounded-lg p-1 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" target="_blank" title="Lihat">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('koordinator-jurnalistik.news.edit', $item->id) }}" class="rounded-lg p-1 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('koordinator-jurnalistik.news.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg p-1 text-red-600 hover:bg-red-50" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($news->hasPages())
        <div class="border-t border-[#D8C4B6]/40 px-6 py-4">
            {{ $news->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <h3 class="mt-4 text-sm font-semibold text-gray-900">Belum Ada Berita</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat berita pertama Anda</p>
            <div class="mt-6">
                <a href="{{ route('koordinator-jurnalistik.news.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Berita
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

// Filter sekarang menggunakan form GET, tidak perlu JavaScript untuk client-side filtering

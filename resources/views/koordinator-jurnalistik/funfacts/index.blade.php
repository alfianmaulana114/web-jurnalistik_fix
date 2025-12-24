{{-- Shadcn-Inspired Funfacts Index --}}
@extends('layouts.koordinator-jurnalistik')

@section('title', 'Manajemen Funfact')
@section('header', 'Manajemen Funfact')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Funfact</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola funfact untuk konten menarik dan edukatif</p>
        </div>
        <a href="{{ route('koordinator-jurnalistik.funfacts.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Funfact
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Funfact</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalFunfacts }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50">
                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $funfacts->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route('koordinator-jurnalistik.funfacts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Judul atau isi funfact..." class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('koordinator-jurnalistik.funfacts.index') }}" class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
            <div class="flex items-end">
                <span class="text-sm text-gray-600">{{ $funfacts->total() }} funfact</span>
            </div>
        </form>
    </div>

    {{-- Funfacts Grid --}}
    @if($funfacts->count() > 0)
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" id="funfactsGrid">
        @foreach($funfacts as $funfact)
        <div class="funfact-card group rounded-lg border border-[#D8C4B6]/40 bg-white p-6 shadow-sm transition-all hover:shadow-md">
            <div class="mb-4 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <span class="text-xs text-gray-500">{{ $funfact->created_at->format('d M Y') }}</span>
            </div>
            
            <h3 class="mb-2 line-clamp-2 text-sm font-semibold text-gray-900">{{ $funfact->judul }}</h3>
            <p class="mb-4 line-clamp-3 text-sm text-gray-600">{{ $funfact->isi }}</p>
            
            @if($funfact->link_referensi)
                @php($links = $funfact->getLinksArray())
                @if(count($links) > 0)
                <div class="mb-4 flex items-center gap-1 text-xs text-gray-500">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    {{ count($links) }} referensi
                </div>
                @endif
            @endif
            
            <div class="flex items-center justify-between border-t border-[#D8C4B6]/40 pt-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-[#f9b61a]/10 text-xs text-[#1b334e] font-semibold">
                        {{ strtoupper(substr($funfact->creator->name ?? '?', 0, 1)) }}
                    </div>
                    <span class="text-xs text-gray-600">{{ $funfact->creator->name ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('koordinator-jurnalistik.funfacts.show', $funfact) }}" class="rounded-lg p-1.5 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" title="Lihat">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <a href="{{ route('koordinator-jurnalistik.funfacts.edit', $funfact) }}" class="rounded-lg p-1.5 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" title="Edit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('koordinator-jurnalistik.funfacts.destroy', $funfact) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg p-1.5 text-red-600 hover:bg-red-50" onclick="return confirm('Hapus funfact ini?')" title="Hapus">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($funfacts->hasPages())
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 shadow-sm">
        {{ $funfacts->links() }}
    </div>
    @endif
    @else
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-12 shadow-sm text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
        </div>
        <h3 class="mt-4 text-sm font-semibold text-gray-900">Belum Ada Funfact</h3>
        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat funfact pertama Anda</p>
        <div class="mt-6">
                <a href="{{ route('koordinator-jurnalistik.funfacts.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Funfact
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

// Filter sekarang menggunakan form GET, tidak perlu JavaScript untuk client-side filtering

@extends($layout ?? 'layouts.sekretaris')

@section('title', 'Funfact')
@section('header', 'Funfact (Read-Only)')

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

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b334e]">Funfact</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat funfact untuk konten menarik dan edukatif (Read-Only)</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Funfact</p>
                    <p class="mt-2 text-2xl font-bold text-[#1b334e]">{{ $totalFunfacts }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50">
                    <i class="fas fa-lightbulb text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold text-[#1b334e]">{{ $funfacts->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route(($routePrefix ?? 'sekretaris.view').'.funfacts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Judul atau isi funfact..." class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.funfacts.index') }}" class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
            <div class="flex items-end">
                <span class="text-sm text-gray-600">{{ $funfacts->total() }} funfact</span>
            </div>
        </form>
    </div>

    @if($funfacts->count() > 0)
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" id="funfactsGrid">
        @foreach($funfacts as $funfact)
        <div class="group rounded-lg border border-[#D8C4B6]/40 bg-white p-6 shadow-sm transition-all hover:shadow-md">
            <div class="mb-4 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <i class="fas fa-lightbulb text-xl"></i>
                </div>
                <span class="text-xs text-gray-500">{{ $funfact->created_at->format('d M Y') }}</span>
            </div>
            <h3 class="mb-2 line-clamp-2 text-sm font-semibold text-[#1b334e]">{{ $funfact->judul }}</h3>
            <p class="mb-4 line-clamp-3 text-sm text-gray-600">{{ $funfact->isi }}</p>
            @if($funfact->link_referensi)
                @php($links = $funfact->getLinksArray())
                @if(count($links) > 0)
                <div class="mb-4 flex items-center gap-1 text-xs text-gray-500">
                    <i class="fas fa-link"></i>
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
                    <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.funfacts.show', $funfact) }}" class="rounded-lg p-1.5 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" title="Lihat">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if($funfacts->hasPages())
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 shadow-sm">
        {{ $funfacts->withQueryString()->links() }}
    </div>
    @endif
    @else
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-12 shadow-sm text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
            <i class="fas fa-lightbulb text-gray-400 text-xl"></i>
        </div>
        <h3 class="mt-4 text-sm font-semibold text-[#1b334e]">Belum Ada Funfact</h3>
        <p class="mt-1 text-sm text-gray-500">Belum ada funfact yang tersedia</p>
    </div>
    @endif
</div>
@endsection


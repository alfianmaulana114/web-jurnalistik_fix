@extends($layout ?? 'layouts.sekretaris')

@section('title', 'Lihat Berita')
@section('header', 'Lihat Berita (Read-Only)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b334e]">Lihat Berita</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat semua berita dan publikasi UKM Jurnalistik (Read-Only)</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700">
            <i class="fas fa-lock"></i>
            Mode Read-Only
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $news->total() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Disetujui</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $news->filter(fn($n) => $n->approval()->exists())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ number_format($news->sum('views')) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="fas fa-eye text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route(($routePrefix ?? 'sekretaris.view').'.news.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.news.index') }}" class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Daftar Berita</h3>
                <span class="text-sm text-gray-600">{{ $news->total() }} berita</span>
            </div>
        </div>
        
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
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-[#1b334e]">{{ $item->title }}</p>
                                    <p class="truncate text-xs text-gray-600">{{ Str::limit(strip_tags($item->content), 50) }}</p>
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
                            @endphp
                            @if($approval)
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                        <i class="fas fa-check-circle"></i>
                                        Disetujui
                                    </span>
                                    <span class="text-xs text-gray-500">oleh {{ $approval->user->name ?? 'Unknown' }}</span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700">
                                    <i class="fas fa-clock"></i>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.news.show', $item->id) }}" class="rounded-lg p-2 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('news.show', $item->slug) }}" class="rounded-lg p-2 text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all" target="_blank" title="Lihat Publik">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($news->hasPages())
        <div class="border-t border-[#D8C4B6]/40 px-6 py-4">
            {{ $news->withQueryString()->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                <i class="fas fa-newspaper text-gray-400 text-xl"></i>
            </div>
            <h3 class="mt-4 text-sm font-semibold text-[#1b334e]">Belum Ada Berita</h3>
            <p class="mt-1 text-sm text-gray-500">Belum ada berita yang tersedia</p>
        </div>
        @endif
    </div>
</div>
@endsection

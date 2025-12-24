@extends('layouts.koordinator-redaksi')

@section('title', 'Manajemen Berita')
@section('header', 'Manajemen Berita')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Berita</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola berita dan konten redaksi</p>
        </div>
        <a href="{{ route('koordinator-redaksi.news.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <i class="fas fa-plus"></i>Tambah Berita
        </a>
    </div>

    <!-- Table -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persetujuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40">
                    @foreach($news as $item)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-[#1b334e]">{{ $item->title }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($item->content), 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-[#1b334e] text-white">
                                {{ $item->category?->name ?? 'Tidak ada kategori' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $item->type?->name ?? 'Tidak ada tipe' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                {{ $item->genres->pluck('name')->implode(', ') ?: 'Tidak ada genre' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $item->user?->name ?? 'Tidak ada penulis' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[#1b334e]">{{ $item->views ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $item->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $approval = $item->approval()->with('user')->first();
                                $isAllowed = auth()->check() && in_array(auth()->user()->role, [\App\Models\User::ROLE_KOORDINATOR_JURNALISTIK, \App\Models\User::ROLE_KOORDINATOR_REDAKSI, \App\Models\User::ROLE_ANGGOTA_REDAKSI]);
                                $isApprover = $approval && auth()->check() && $approval->user_id === auth()->id();
                            @endphp
                            <div class="flex items-center space-x-2">
                                @if($approval)
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">Disetujui oleh: {{ $approval->user->name ?? 'Unknown' }}</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-[#f9b61a]/10 px-2 py-0.5 text-xs font-medium text-[#1b334e]">Belum disetujui</span>
                                @endif
                                @if($isAllowed && !$approval)
                                    <form action="{{ route('news.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-3 py-1 text-xs font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('news.show', $item->slug) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" target="_blank" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-redaksi.news.edit', $item->id) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-redaksi.news.destroy', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($news->hasPages())
        <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
            {{ $news->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


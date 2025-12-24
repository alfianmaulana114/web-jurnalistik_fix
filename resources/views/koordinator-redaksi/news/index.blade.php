@extends('layouts.koordinator-redaksi')

@section('title', 'Manajemen Berita')
@section('header', 'Manajemen Berita')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Berita</h2>
        <a href="{{ route('koordinator-redaksi.news.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Tambah Berita
        </a>
    </div>
    <div class="p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
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
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($news as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($item->content), 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $item->category?->name ?? 'Tidak ada kategori' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $item->type?->name ?? 'Tidak ada tipe' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ $item->genres->pluck('name')->implode(', ') ?: 'Tidak ada genre' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $item->user?->name ?? 'Tidak ada penulis' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $item->views ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $item->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $approval = $item->approval()->with('user')->first();
                                $isAllowed = auth()->check() && in_array(auth()->user()->role, [\App\Models\User::ROLE_KOORDINATOR_JURNALISTIK, \App\Models\User::ROLE_KOORDINATOR_REDAKSI, \App\Models\User::ROLE_ANGGOTA_REDAKSI]);
                                $isApprover = $approval && auth()->check() && $approval->user_id === auth()->id();
                            @endphp
                            <div class="flex items-center space-x-2">
                                @if($approval)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Disetujui oleh: {{ $approval->user->name ?? 'Unknown' }}</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Belum disetujui</span>
                                @endif
                                @if($isAllowed && !$approval)
                                    <form action="{{ route('news.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('news.show', $item->slug) }}" class="text-green-500 hover:text-green-700 mr-3" target="_blank" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('koordinator-redaksi.news.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('koordinator-redaksi.news.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $news->links() }}
        </div>
    </div>
</div>
@endsection


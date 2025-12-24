@extends('layouts.koordinator-litbang')

@section('title', 'Manajemen Brief')
@section('header', 'Manajemen Brief Divisi Litbang')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Brief</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola brief divisi litbang</p>
        </div>
        <a href="{{ route('koordinator-litbang.briefs.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Tambah Brief
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                    <i class="fas fa-file-alt text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalBriefs }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                    <i class="fas fa-calendar text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Brief Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $briefs->where('tanggal', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Brief</h3>
        </div>
        @if($briefs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brief</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referensi</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($briefs as $brief)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('koordinator-litbang.briefs.show', $brief) }}" class="hover:text-green-600">{{ $brief->judul }}</a>
                            </div>
                            <div class="text-sm text-gray-500">{{ Str::limit($brief->isi_brief, 80) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $brief->tanggal ? $brief->tanggal->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($brief->link_referensi)
                                <textarea readonly onclick="this.select()" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded px-2 py-1 text-sm cursor-pointer resize-none">{{ $brief->link_referensi }}</textarea>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('koordinator-litbang.briefs.show', $brief) }}" class="text-gray-600 hover:text-gray-900" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-litbang.briefs.edit', $brief) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-litbang.briefs.destroy', $brief) }}" method="POST" class="inline" onsubmit="return confirm('Hapus brief ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
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
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $briefs->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada brief</h3>
            <p class="text-gray-500 mb-6">Buat brief baru untuk memulai.</p>
            <a href="{{ route('koordinator-litbang.briefs.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Tambah Brief Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
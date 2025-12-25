@extends('layouts.koordinator-humas')

@section('title', 'Brief Humas')
@section('header', 'Brief Humas')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Brief Humas</h1>
            <p class="text-gray-600">Kelola brief untuk divisi Humas</p>
        </div>
        <a href="{{ route('koordinator-humas.brief-humas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Tambah Brief
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm text-gray-700 mb-1">Cari</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Cari judul">
            </div>
            <button type="submit" class="px-4 py-2 border rounded-md text-sm">Terapkan</button>
            <a href="{{ route('koordinator-humas.brief-humas.index') }}" class="px-4 py-2 border rounded-md text-sm">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($briefs->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Drive</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($briefs as $brief)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $brief->judul }}</td>
                    <td class="px-6 py-4 text-sm text-blue-600"><a href="{{ $brief->link_drive }}" target="_blank" class="hover:underline">Buka</a></td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($brief->catatan, 80) }}</td>
                    <td class="px-6 py-4 text-sm text-right">
                        <a href="{{ route('koordinator-humas.brief-humas.show', $brief) }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Lihat</a>
                        <a href="{{ route('koordinator-humas.brief-humas.edit', $brief) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('koordinator-humas.brief-humas.destroy', $brief) }}" method="POST" class="inline" onsubmit="return confirm('Hapus brief ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-3">{{ $briefs->links() }}</div>
        @else
        <div class="p-6 text-center">
            <p class="text-gray-500">Belum ada brief humas</p>
        </div>
        @endif
    </div>
@endsection


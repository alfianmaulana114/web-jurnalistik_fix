@extends('layouts.koordinator-media-kreatif')

@section('title', 'Notulensi Rapat (Read-Only)')
@section('header', 'Notulensi Rapat (Read-Only)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Notulensi Rapat</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat notulensi rapat UKM Jurnalistik (Read-Only)</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700">
            <i class="fas fa-lock"></i>
            Mode Read-Only
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notulensi->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($notulensi as $n)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900">{{ $n->judul }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $n->tanggal->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $n->creator->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('koordinator-media-kreatif.view.sekretaris.notulensi.show', $n) }}" class="px-3 py-1 text-xs rounded-md bg-blue-600 text-white hover:bg-blue-700">Lihat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $notulensi->links() }}</div>
        @else
            <div class="p-6 text-center">
                <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada notulensi</p>
            </div>
        @endif
    </div>
</div>
@endsection

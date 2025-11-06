@extends('layouts.sekretaris')

@section('title', 'Notulensi Rapat')
@section('header', 'Notulensi Rapat')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notulensi Rapat</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola notulensi rapat UKM Jurnalistik</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('sekretaris.notulensi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Tambah Notulensi
            </a>
        </div>
    </div>

    <!-- Notulensi List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notulensi->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($notulensi as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('sekretaris.notulensi.show', $item) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            {{ $item->judul }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $item->tanggal->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                        @if($item->waktu_selesai)
                            - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $item->creator->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('sekretaris.notulensi.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('sekretaris.notulensi.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('sekretaris.notulensi.destroy', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus notulensi ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $notulensi->links() }}
        </div>
        @else
        <div class="p-6 text-center">
            <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Belum ada notulensi rapat</p>
            <a href="{{ route('sekretaris.notulensi.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Tambah Notulensi Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection


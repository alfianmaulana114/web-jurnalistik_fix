@extends('layouts.koordinator-redaksi')

@section('title', 'Manajemen Funfact')
@section('header', 'Manajemen Funfact')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Funfact</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola funfact untuk konten menarik</p>
        </div>
        <a href="{{ route('koordinator-redaksi.funfacts.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i>
            Tambah Funfact
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-lightbulb text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Funfact</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalFunfacts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-calendar text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Funfact Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $funfacts->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funfacts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Daftar Funfact</h3>
                <span class="text-sm text-gray-500">{{ $funfacts->total() }} funfact ditemukan</span>
            </div>
        </div>

        @if($funfacts->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funfact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Referensi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($funfacts as $funfact)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-lightbulb text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('koordinator-redaksi.funfacts.show', $funfact) }}" class="hover:text-red-600">
                                            {{ $funfact->judul }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($funfact->isi, 80) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($funfact->link_referensi)
                                @php($links = $funfact->getLinksArray())
                                @if(count($links) > 0)
                                    <div class="space-y-1">
                                        @foreach(array_slice($links, 0, 2) as $link)
                                            <a href="{{ $link }}" target="_blank" class="block text-blue-600 hover:text-blue-800 truncate max-w-xs" title="{{ $link }}">
                                                <i class="fas fa-external-link-alt mr-1"></i>{{ Str::limit($link, 40) }}
                                            </a>
                                        @endforeach
                                        @if(count($links) > 2)
                                            <span class="text-xs text-gray-500">+{{ count($links) - 2 }} link lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $funfact->creator->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $funfact->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('koordinator-redaksi.funfacts.show', $funfact) }}" class="text-gray-600 hover:text-gray-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-redaksi.funfacts.edit', $funfact) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-redaksi.funfacts.destroy', $funfact) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus funfact ini?')">
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

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $funfacts->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-lightbulb text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada funfact</h3>
            <p class="text-gray-500 mb-6">Mulai dengan membuat funfact pertama Anda.</p>
            <a href="{{ route('koordinator-redaksi.funfacts.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Tambah Funfact Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection


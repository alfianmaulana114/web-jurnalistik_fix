@extends('layouts.sekretaris')

@section('title', 'Program Kerja')
@section('header', 'Manajemen Program Kerja')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Program Kerja</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola program kerja UKM Jurnalistik</p>
        </div>
        <a href="{{ route('sekretaris.proker.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <i class="fas fa-plus"></i>
            Tambah Proker
        </a>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md p-5">
        <form method="GET" action="{{ route('sekretaris.proker.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1.5">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama proker..." class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a] text-sm">
            </div>
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a] text-sm">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Proker::getAllStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year" class="block text-xs font-medium text-gray-700 mb-1.5">Tahun</label>
                <select name="year" id="year" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a] text-sm">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-search"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('sekretaris.proker.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Proker</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $prokers->total() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Planning</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $prokers->where('status', 'planning')->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Ongoing</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $prokers->where('status', 'ongoing')->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-play text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $prokers->where('status', 'completed')->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-check text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Prokers Table -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
            <div>
                <h3 class="text-base font-semibold text-[#1b334e]">Daftar Program Kerja</h3>
                <p class="mt-0.5 text-xs text-gray-600">Semua program kerja UKM Jurnalistik</p>
            </div>
        </div>
        
        @if($prokers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Program Kerja
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Panitia
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat Oleh
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40">
                    @foreach($prokers as $proker)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $proker->nama_proker }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($proker->deskripsi, 60) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $proker->tanggal_mulai->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">s/d {{ $proker->tanggal_selesai->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($proker->status === 'planning') bg-[#f9b61a]/10 text-[#1b334e]
                                @elseif($proker->status === 'ongoing') bg-green-50 text-green-700
                                @elseif($proker->status === 'completed') bg-gray-50 text-gray-700
                                @else bg-red-50 text-red-700 @endif">
                                {{ $proker->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $proker->panitia->count() }} orang</div>
                            <div class="text-sm text-gray-500">
                                @if($proker->panitia->count() > 0)
                                    {{ $proker->panitia->take(2)->pluck('name')->join(', ') }}
                                    @if($proker->panitia->count() > 2)
                                        +{{ $proker->panitia->count() - 2 }} lainnya
                                    @endif
                                @else
                                    Belum ada panitia
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $proker->creator->name }}</div>
                            <div class="text-sm text-gray-500">{{ $proker->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('sekretaris.proker.show', $proker) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sekretaris.proker.edit', $proker) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('sekretaris.proker.destroy', $proker) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proker ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all" title="Hapus">
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
        <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
            {{ $prokers->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-tasks text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada program kerja</h3>
            <p class="text-gray-500 mb-6">Mulai dengan membuat program kerja pertama Anda.</p>
            <a href="{{ route('sekretaris.proker.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <i class="fas fa-plus"></i>
                Tambah Proker
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
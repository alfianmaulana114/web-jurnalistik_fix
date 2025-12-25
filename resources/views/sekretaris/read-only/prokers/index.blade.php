@extends($layout ?? 'layouts.sekretaris')

@section('title', 'Program Kerja')
@section('header', 'Program Kerja (Read-Only)')

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

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Program Kerja</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat program kerja UKM Jurnalistik (Read-Only)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#1b334e]">Total Proker</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $prokers->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#1b334e]">Planning</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $prokers->where('status', 'planning')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-play text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#1b334e]">Ongoing</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $prokers->where('status', 'ongoing')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]/40">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $prokers->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route(($routePrefix ?? 'sekretaris.view').'.prokers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama proker..." class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Proker::getAllStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                <select name="year" id="year" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.prokers.index') }}" class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
            <h3 class="text-lg font-medium text-[#1b334e]">Daftar Program Kerja</h3>
        </div>
        
        @if($prokers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Kerja</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panitia</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="prokersTableBody">
                    @foreach($prokers as $proker)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-[#1b334e]">{{ $proker->nama_proker }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($proker->deskripsi, 60) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $proker->tanggal_mulai->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">s/d {{ $proker->tanggal_selesai->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($proker->status === 'planning') bg-yellow-100 text-yellow-800
                                @elseif($proker->status === 'ongoing') bg-green-100 text-green-800
                                @elseif($proker->status === 'completed') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
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
                            <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.prokers.show', $proker) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
            {{ $prokers->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-tasks text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-[#1b334e] mb-2">Belum ada program kerja</h3>
            <p class="text-gray-500">Belum ada program kerja yang tersedia</p>
        </div>
        @endif
    </div>
</div>
@endsection

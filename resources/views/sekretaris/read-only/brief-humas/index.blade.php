@extends($layout ?? 'layouts.sekretaris')

@section('title', 'Brief Humas')
@section('header', 'Brief Humas (Read-Only)')

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

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Brief Humas</h1>
            <p class="text-sm text-gray-600">Lihat brief untuk divisi humas (Read-Only)</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-3 items-end" action="{{ route(($routePrefix ?? 'sekretaris.view').'.brief-humas.index') }}">
            <div class="flex-1">
                <label class="block text-sm text-gray-700 mb-1">Cari</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Cari judul">
            </div>
            <button type="submit" class="px-4 py-2 border rounded-md text-sm">Terapkan</button>
            <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.brief-humas.index') }}" class="px-4 py-2 border rounded-md text-sm">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($briefs->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($briefs as $briefHumas)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.brief-humas.show', $briefHumas) }}" class="font-medium text-[#1b334e] hover:text-[#f9b61a]">{{ $briefHumas->judul }}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($briefHumas->catatan, 80) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.brief-humas.show', $briefHumas) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $briefs->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-[#1b334e] mb-2">Belum ada brief humas</h3>
            <p class="text-gray-500">Belum ada data brief humas yang tersedia</p>
        </div>
        @endif
    </div>
</div>
@endsection


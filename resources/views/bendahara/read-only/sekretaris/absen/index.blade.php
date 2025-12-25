@extends('layouts.bendahara')

@section('title', 'Absen Anggota (Read-Only)')
@section('header', 'Absen Anggota (Read-Only)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Absen Anggota</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat absensi anggota UKM Jurnalistik (Read-Only)</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700">
            <i class="fas fa-lock"></i>
            Mode Read-Only
        </div>
    </div>

    @if(!$notulensi_id)
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Rapat</h3>
            <form method="GET" action="{{ route('bendahara.view.sekretaris.absen.index') }}" class="flex items-center gap-2">
                <input type="text" name="meeting_search" value="{{ request('meeting_search') }}" placeholder="Cari judul/tempat"
                       class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Bulan</option>
                    <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                    <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                    <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                    <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                </select>
                <button type="submit" class="px-3 py-2 bg-gray-600 text-white rounded-md text-sm">Cari</button>
                <a href="{{ route('bendahara.view.sekretaris.absen.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md text-sm">Reset</a>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($meetings as $m)
            <a href="{{ route('bendahara.view.sekretaris.absen.index', ['notulensi_id' => $m->id]) }}" class="group block rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-base font-medium text-gray-900 group-hover:text-[#1b334e]">{{ $m->judul }}</h4>
                        <p class="mt-1 text-sm text-gray-600">{{ $m->tanggal->format('d M Y') }}</p>
                    </div>
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-600">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    @php $selectedMeeting = $meetings->firstWhere('id', $notulensi_id); @endphp
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $selectedMeeting->judul ?? 'Rapat' }}</h3>
                <p class="text-sm text-gray-600">{{ optional($selectedMeeting->tanggal)->format('d M Y') }}</p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700">
                <i class="fas fa-lock"></i>
                Read-Only
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notulensi_id && $users->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                @php $absen = $absenData->get($user->id); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->nim }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $user->getDivision())) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($absen)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absen->getStatusBadgeClass() }}">
                                {{ $absen->getStatusLabel() }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Input</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $absen->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @elseif($notulensi_id)
            <div class="p-6 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada anggota untuk ditampilkan</p>
            </div>
        @endif
    </div>
</div>
@endsection
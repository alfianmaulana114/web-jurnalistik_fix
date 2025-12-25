@extends('layouts.koordinator-jurnalistik')

@section('title', 'Riwayat Kas Anggota')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Kas Anggota</h1>
            <p class="text-gray-600 mt-1">Tabel status kas per bulan, termasuk yang belum bayar</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Anggota</label>
                <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama atau NIM" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" id="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    @foreach($tahunOptions as $th)
                        <option value="{{ $th }}" {{ (string)$tahun === (string)$th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="divisi" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                <select name="divisi" id="divisi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua</option>
                    <option value="redaksi" {{ ($divisi ?? '')==='redaksi' ? 'selected' : '' }}>Redaksi</option>
                    <option value="litbang" {{ ($divisi ?? '')==='litbang' ? 'selected' : '' }}>Litbang</option>
                    <option value="humas" {{ ($divisi ?? '')==='humas' ? 'selected' : '' }}>Humas</option>
                    <option value="media_kreatif" {{ ($divisi ?? '')==='media_kreatif' ? 'selected' : '' }}>Media Kreatif</option>
                    <option value="pengurus" {{ ($divisi ?? '')==='pengurus' ? 'selected' : '' }}>Pengurus</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('koordinator-jurnalistik.kas-anggota.riwayat') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                        @foreach($periodeOrder as $periode)
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ ucfirst($periode) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($riwayat as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row['user']->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row['user']->nim }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $row['user']->getDivision())) }}</td>
                            @foreach($periodeOrder as $periode)
                                @php $cell = $row['byMonth'][$periode]; @endphp
                                <td class="px-2 py-3 text-center">
                                    @if($cell['status'] === 'lunas')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800" title="Dibayar: {{ optional($cell['tanggal_pembayaran'])->format('d/m/Y') }}; Jumlah: {{ number_format($cell['jumlah_terbayar'],0,',','.') }}">
                                            Lunas
                                        </span>
                                    @elseif($cell['status'] === 'sebagian')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800" title="Dibayar: {{ optional($cell['tanggal_pembayaran'])->format('d/m/Y') }}; Jumlah: {{ number_format($cell['jumlah_terbayar'],0,',','.') }}">
                                            Sebagian
                                        </span>
                                    @elseif($cell['status'] === 'terlambat')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <div>Tidak ada data</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.bendahara')

@section('title', 'Detail Kas Anggota')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">Detail Kas Anggota</h2>
            <div class="flex space-x-2">
                <a href="{{ route('bendahara.kas-anggota.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <a href="{{ route('bendahara.kas-anggota.edit', $kasAnggota) }}" class="px-3 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors text-sm">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Member Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>Informasi Anggota
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nama Anggota</label>
                            <p class="text-gray-900 font-medium">{{ $kasAnggota->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Divisi</label>
                            <p class="text-gray-900">{{ $kasAnggota->user->getDivision() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $kasAnggota->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-calendar mr-2 text-green-500"></i>Periode & Status
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Periode</label>
                            <p class="text-gray-900 font-medium">
                                {{ DateTime::createFromFormat('!m', $kasAnggota->bulan)->format('F') }} {{ $kasAnggota->tahun }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $kasAnggota->status === 'lunas' ? 'bg-green-100 text-green-800' : 
                                   ($kasAnggota->status === 'belum_lunas' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $kasAnggota->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Details -->
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2 text-blue-500"></i>Detail Keuangan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Dibayar</label>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($kasAnggota->jumlah_terbayar, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Sisa Hutang</label>
                        <p class="text-lg font-bold {{ (\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) - $kasAnggota->jumlah_terbayar) > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) - $kasAnggota->jumlah_terbayar, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Persentase Bayar</label>
                        <p class="text-lg font-bold text-blue-600">
                            {{ round(($kasAnggota->jumlah_terbayar / \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000)) * 100, 1) }}%
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($kasAnggota->tanggal_pembayaran)
            <div class="mt-6 bg-green-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Riwayat Pembayaran
                </h3>
                <div class="space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Dibayar</label>
                        <p class="text-gray-900">{{ $kasAnggota->tanggal_pembayaran->format('d F Y H:i') }}</p>
                    </div>
                    @if($kasAnggota->verified_by)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Diverifikasi Oleh</label>
                        <p class="text-gray-900">{{ $kasAnggota->verifiedBy->name }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($kasAnggota->keterangan)
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>Keterangan
                </h3>
                <p class="text-gray-900">{{ $kasAnggota->keterangan }}</p>
            </div>
            @endif

            <!-- Audit Information -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-gray-500"></i>Informasi Sistem
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dibuat Oleh</label>
                        <p class="text-gray-900">{{ $kasAnggota->createdBy->name ?? 'Sistem' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ $kasAnggota->created_at->format('d F Y H:i') }}</p>
                    </div>
                    @if($kasAnggota->updated_by)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate Oleh</label>
                        <p class="text-gray-900">{{ $kasAnggota->updatedBy->name }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate</label>
                        <p class="text-gray-900">{{ $kasAnggota->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <div class="flex space-x-2">
                    @if($kasAnggota->status !== 'lunas')
                    <a href="{{ route('bendahara.kas-anggota.edit', $kasAnggota) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Pembayaran
                    </a>
                    @endif
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    <form action="{{ route('bendahara.kas-anggota.destroy', $kasAnggota) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kas anggota ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .container {
        max-width: none;
        margin: 0;
        padding: 0;
    }
}
</style>
@endsection
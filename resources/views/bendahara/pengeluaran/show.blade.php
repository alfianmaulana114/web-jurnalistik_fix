@extends('layouts.bendahara')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">Detail Pengeluaran</h2>
            <div class="flex space-x-2">
                <a href="{{ route('bendahara.pengeluaran.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <a href="{{ route('bendahara.pengeluaran.edit', $pengeluaran) }}" class="px-3 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors text-sm">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                @if($pengeluaran->status === 'pending')
                    <form action="{{ route('bendahara.pengeluaran.verify', $pengeluaran) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi pengeluaran ini?')">
                            <i class="fas fa-check mr-1"></i>Verifikasi
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <div class="p-6">
            <!-- Transaction Code & Status -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-md font-semibold text-gray-700">Kode Transaksi</h3>
                        <p class="text-lg font-mono text-red-600">{{ $pengeluaran->kode_transaksi }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-md font-semibold text-gray-700">Status</h3>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $pengeluaran->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $pengeluaran->getStatusLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>Informasi Dasar
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Tanggal</label>
                            <p class="text-gray-900 font-medium">{{ $pengeluaran->tanggal->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Deskripsi</label>
                            <p class="text-gray-900">{{ $pengeluaran->deskripsi }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Kategori</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $pengeluaran->getKategoriLabel() }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Penerima</label>
                            <p class="text-gray-900">{{ $pengeluaran->penerima }}</p>
                        </div>
                        @if($pengeluaran->tujuan)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Tujuan</label>
                            <p class="text-gray-900">{{ $pengeluaran->tujuan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="bg-red-50 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-red-500"></i>Informasi Keuangan
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Jumlah</label>
                            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</p>
                        </div>
                        @if($pengeluaran->metode_pembayaran)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Metode Pembayaran</label>
                            <p class="text-gray-900">{{ $pengeluaran->getMetodePembayaranLabel() }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            @if($pengeluaran->bukti_pembayaran)
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-file-image mr-2 text-purple-500"></i>Bukti Pembayaran
                </h3>
                <div class="flex items-center space-x-4">
                    @if(in_array(pathinfo($pengeluaran->bukti_pembayaran, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" 
                             alt="Bukti Pembayaran" 
                             class="w-32 h-32 object-cover rounded-lg border">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-lg border flex items-center justify-center">
                            <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-2">File: {{ basename($pengeluaran->bukti_pembayaran) }}</p>
                        <a href="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Nota/Kwitansi -->
            @if($pengeluaran->nota_kwitansi)
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-receipt mr-2 text-orange-500"></i>Nota/Kwitansi
                </h3>
                <div class="flex items-center space-x-4">
                    @if(in_array(pathinfo($pengeluaran->nota_kwitansi, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $pengeluaran->nota_kwitansi) }}" 
                             alt="Nota/Kwitansi" 
                             class="w-32 h-32 object-cover rounded-lg border">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-lg border flex items-center justify-center">
                            <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-2">File: {{ basename($pengeluaran->nota_kwitansi) }}</p>
                        <a href="{{ asset('storage/' . $pengeluaran->nota_kwitansi) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($pengeluaran->keterangan)
            <div class="mt-6 bg-yellow-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>Keterangan
                </h3>
                <p class="text-gray-900">{{ $pengeluaran->keterangan }}</p>
            </div>
            @endif

            <!-- Verification Information -->
            @if($pengeluaran->status === 'verified')
            <div class="mt-6 bg-green-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Informasi Verifikasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Diverifikasi Oleh</label>
                        <p class="text-gray-900">{{ $pengeluaran->verifiedBy->name ?? 'Sistem' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Verifikasi</label>
                        <p class="text-gray-900">{{ $pengeluaran->verified_at?->format('d F Y H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Audit Information -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-history mr-2 text-gray-500"></i>Informasi Sistem
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dibuat Oleh</label>
                        <p class="text-gray-900">{{ $pengeluaran->createdBy->name ?? 'Sistem' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ $pengeluaran->created_at->format('d F Y H:i') }}</p>
                    </div>
                    @if($pengeluaran->updated_by)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate Oleh</label>
                        <p class="text-gray-900">{{ $pengeluaran->updatedBy->name }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate</label>
                        <p class="text-gray-900">{{ $pengeluaran->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <div class="flex space-x-2">
                    @if($pengeluaran->status === 'pending')
                    <form action="{{ route('bendahara.pengeluaran.verify', $pengeluaran) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi pengeluaran ini?')">
                            <i class="fas fa-check mr-2"></i>Verifikasi
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('bendahara.pengeluaran.edit', $pengeluaran) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    <form action="{{ route('bendahara.pengeluaran.destroy', $pengeluaran) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?')">
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
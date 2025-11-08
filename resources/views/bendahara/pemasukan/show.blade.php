@extends('layouts.bendahara')

@section('title', 'Detail Pemasukan')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">Detail Pemasukan</h2>
            <div class="flex space-x-2">
                <a href="{{ route('bendahara.pemasukan.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <a href="{{ route('bendahara.pemasukan.edit', $pemasukan) }}" class="px-3 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors text-sm">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                @if($pemasukan->status === 'pending')
                    <form action="{{ route('bendahara.pemasukan.verify', $pemasukan) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi pemasukan ini?')">
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
                        <p class="text-lg font-mono text-blue-600">{{ $pemasukan->kode_transaksi }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-md font-semibold text-gray-700">Status</h3>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $pemasukan->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $pemasukan->getStatusLabel() }}
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
                            <p class="text-gray-900 font-medium">{{ $pemasukan->tanggal->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Deskripsi</label>
                            <p class="text-gray-900">{{ $pemasukan->deskripsi }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Kategori</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $pemasukan->getKategoriLabel() }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Sumber</label>
                            <p class="text-gray-900">{{ $pemasukan->sumber }}</p>
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>Informasi Keuangan
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Jumlah</label>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</p>
                        </div>
                        @if($pemasukan->metode_pembayaran)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Metode Pembayaran</label>
                            <p class="text-gray-900">{{ $pemasukan->getMetodePembayaranLabel() }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kas Anggota Information -->
            @if($pemasukan->kasAnggota)
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-users mr-2 text-blue-500"></i>Kas Anggota Terkait
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Anggota</label>
                        <p class="text-gray-900 font-medium">{{ $pemasukan->kasAnggota->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Periode</label>
                        <p class="text-gray-900">
                            {{ ucfirst($pemasukan->kasAnggota->periode) }} 
                            {{ $pemasukan->kasAnggota->tahun }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status Kas</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pemasukan->kasAnggota->status === 'lunas' ? 'bg-green-100 text-green-800' : 
                               ($pemasukan->kasAnggota->status === 'belum_lunas' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $pemasukan->kasAnggota->status)) }}
                        </span>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('bendahara.kas-anggota.show', $pemasukan->kasAnggota) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail Kas Anggota
                    </a>
                </div>
            </div>
            @endif

            <!-- Bukti Transfer -->
            @php $proof = $pemasukan->bukti_pemasukan; @endphp
            @if($proof)
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-file-image mr-2 text-purple-500"></i>Bukti Transfer
                </h3>
                @php
                    $path = parse_url($proof, PHP_URL_PATH) ?? '';
                    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg','jpeg','png','gif','webp']);
                    $isUrl = filter_var($proof, FILTER_VALIDATE_URL);
                @endphp
                <div class="flex items-center space-x-4">
                    @if($isUrl)
                        @if($isImage)
                            <img src="{{ $proof }}" 
                                 alt="Bukti Transfer" 
                                 class="w-32 h-32 object-cover rounded-lg border">
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-lg border flex items-center justify-center">
                                <i class="fas fa-file-alt text-4xl text-gray-500"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Bukti: {{ basename($path) ?: 'Tautan' }}</p>
                            <a href="{{ $proof }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i>Buka Bukti
                            </a>
                        </div>
                    @else
                        @php $legacyUrl = asset('storage/' . $proof); @endphp
                        @if($isImage)
                            <img src="{{ $legacyUrl }}" 
                                 alt="Bukti Transfer" 
                                 class="w-32 h-32 object-cover rounded-lg border">
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-lg border flex items-center justify-center">
                                <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 mb-2">File: {{ basename($proof) }}</p>
                            <a href="{{ $legacyUrl }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>Lihat Bukti
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($pemasukan->keterangan)
            <div class="mt-6 bg-yellow-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>Keterangan
                </h3>
                <p class="text-gray-900">{{ $pemasukan->keterangan }}</p>
            </div>
            @endif

            <!-- Verification Information -->
            @if($pemasukan->status === 'verified')
            <div class="mt-6 bg-green-50 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Informasi Verifikasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Diverifikasi Oleh</label>
                        <p class="text-gray-900">{{ $pemasukan->verifiedBy->name ?? 'Sistem' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Verifikasi</label>
                        <p class="text-gray-900">{{ $pemasukan->verified_at?->format('d F Y H:i') ?? '-' }}</p>
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
                        <p class="text-gray-900">{{ $pemasukan->createdBy->name ?? 'Sistem' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ $pemasukan->created_at->format('d F Y H:i') }}</p>
                    </div>
                    @if($pemasukan->updated_by)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate Oleh</label>
                        <p class="text-gray-900">{{ $pemasukan->updatedBy->name }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate</label>
                        <p class="text-gray-900">{{ $pemasukan->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <div class="flex space-x-2">
                    @if($pemasukan->status === 'pending')
                    <form action="{{ route('bendahara.pemasukan.verify', $pemasukan) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi pemasukan ini?')">
                            <i class="fas fa-check mr-2"></i>Verifikasi
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('bendahara.pemasukan.edit', $pemasukan) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    <form action="{{ route('bendahara.pemasukan.destroy', $pemasukan) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemasukan ini?')">
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
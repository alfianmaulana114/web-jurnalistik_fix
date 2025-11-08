@extends('layouts.bendahara')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Edit Pengeluaran</h3>
        <p class="text-sm text-gray-600 mb-6">Kode Transaksi: {{ $pengeluaran->kode_transaksi }}</p>
        
        <form action="{{ route('bendahara.pengeluaran.update', $pengeluaran) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Pengeluaran -->
                    <div>
                    <label for="tanggal_pengeluaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_pengeluaran" 
                               name="tanggal_pengeluaran" 
                               value="{{ old('tanggal_pengeluaran', optional($pengeluaran->tanggal)->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('tanggal_pengeluaran') border-red-500 @enderror"
                               required>
                        @error('tanggal_pengeluaran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keperluan -->
                    <div>
                    <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keperluan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="keperluan"
                               name="keperluan"
                               value="{{ old('keperluan', $pengeluaran->keperluan) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('keperluan') border-red-500 @enderror"
                               placeholder="Contoh: Pembelian alat tulis, sewa tempat, dll"
                               required>
                        @error('keperluan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi (opsional) -->
                <div class="col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Rincian tambahan (opsional)">{{ old('deskripsi', $pengeluaran->deskripsi) }}</textarea>
                        @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori" 
                                name="kategori" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('kategori') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="operasional" {{ old('kategori', $pengeluaran->kategori) === 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="acara" {{ old('kategori', $pengeluaran->kategori) === 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="peralatan" {{ old('kategori', $pengeluaran->kategori) === 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="konsumsi" {{ old('kategori', $pengeluaran->kategori) === 'konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                            <option value="transport" {{ old('kategori', $pengeluaran->kategori) === 'transport' ? 'selected' : '' }}>Transport</option>
                            <option value="administrasi" {{ old('kategori', $pengeluaran->kategori) === 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="lainnya" {{ old('kategori', $pengeluaran->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penerima -->
                    <div>
                    <label for="penerima" class="block text-sm font-medium text-gray-700 mb-2">
                            Penerima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="penerima" 
                               name="penerima" 
                               value="{{ old('penerima', $pengeluaran->penerima) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('penerima') border-red-500 @enderror"
                               placeholder="Nama penerima atau vendor"
                               required>
                        @error('penerima')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>
                    <!-- Jumlah -->
                    <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" 
                                   id="jumlah" 
                                   name="jumlah" 
                                   value="{{ old('jumlah', $pengeluaran->jumlah) }}"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('jumlah') border-red-500 @enderror"
                                   placeholder="0"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        @error('jumlah')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select id="metode_pembayaran" 
                                name="metode_pembayaran" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('metode_pembayaran') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Metode</option>
                            <option value="tunai" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer_bank" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="e_wallet" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="cek" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'cek' ? 'selected' : '' }}>Cek</option>
                            <option value="lainnya" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('metode_pembayaran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bukti Pengeluaran (URL) -->
                <div class="col-span-2">
                    <label for="bukti_pengeluaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Link Bukti Pengeluaran
                        </label>
                        @if($pengeluaran->bukti_pengeluaran)
                            @php $isUrl = filter_var($pengeluaran->bukti_pengeluaran, FILTER_VALIDATE_URL); @endphp
                            <div class="mb-2 p-3 bg-blue-50 rounded-md">
                                @if($isUrl)
                                    <a href="{{ $pengeluaran->bukti_pengeluaran }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-external-link-alt mr-1"></i>Lihat Bukti
                                    </a>
                                @else
                                    <p class="text-sm text-blue-700 mb-2">File saat ini: {{ basename($pengeluaran->bukti_pengeluaran) }}</p>
                                    <a href="{{ asset('storage/' . $pengeluaran->bukti_pengeluaran) }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-external-link-alt mr-1"></i>Lihat File
                                    </a>
                                @endif
                            </div>
                        @endif
                        <input type="url" 
                               id="bukti_pengeluaran" 
                               name="bukti_pengeluaran" 
                               value="{{ old('bukti_pengeluaran', $pengeluaran->bukti_pengeluaran) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('bukti_pengeluaran') border-red-500 @enderror"
                               placeholder="https://contoh.com/bukti-pengeluaran">
                        <p class="text-xs text-gray-500 mt-1">Tempelkan tautan ke bukti (gambar atau dokumen) yang dapat diakses. Kosongkan jika tidak ingin mengubah.</p>
                        @error('bukti_pengeluaran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                <div class="col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea id="keterangan" 
                                  name="keterangan" 
                                  rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('bendahara.pengeluaran.show', $pengeluaran) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput = document.getElementById('jumlah');

    // Format number input
    jumlahInput.addEventListener('input', function() {
        // Remove any non-digit characters except for the decimal point
        let value = this.value.replace(/[^\d]/g, '');
        this.value = value;
    });

    // Tidak ada validasi file karena bukti berupa tautan URL
    
    // Double click protection
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });
});
</script>
@endsection
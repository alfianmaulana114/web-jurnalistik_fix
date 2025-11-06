@extends('layouts.bendahara')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Tambah Pengeluaran Baru</h2>
            <p class="text-sm text-gray-600 mt-1">Masukkan detail pengeluaran organisasi</p>
        </div>
        
        <form action="{{ route('bendahara.pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Tanggal Pengeluaran -->
                    <div>
                        <label for="tanggal_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_pengeluaran" 
                               name="tanggal_pengeluaran" 
                               value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_pengeluaran') border-red-500 @enderror"
                               required>
                        @error('tanggal_pengeluaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keperluan -->
                    <div>
                        <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-1">
                            Keperluan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="keperluan"
                               name="keperluan"
                               value="{{ old('keperluan') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('keperluan') border-red-500 @enderror"
                               placeholder="Contoh: Pembelian alat tulis, sewa tempat, dll"
                               required>
                        @error('keperluan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi (opsional) -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Rincian tambahan (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori" 
                                name="kategori" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="operasional" {{ old('kategori') === 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="acara" {{ old('kategori') === 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="peralatan" {{ old('kategori') === 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="konsumsi" {{ old('kategori') === 'konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                            <option value="transport" {{ old('kategori') === 'transport' ? 'selected' : '' }}>Transport</option>
                            <option value="administrasi" {{ old('kategori') === 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="lainnya" {{ old('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penerima -->
                    <div>
                        <label for="penerima" class="block text-sm font-medium text-gray-700 mb-1">
                            Penerima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="penerima" 
                               name="penerima" 
                               value="{{ old('penerima') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('penerima') border-red-500 @enderror"
                               placeholder="Nama penerima atau vendor"
                               required>
                        @error('penerima')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Jumlah -->
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" 
                                   id="jumlah" 
                                   name="jumlah" 
                                   value="{{ old('jumlah') }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah') border-red-500 @enderror"
                                   placeholder="0"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        @error('jumlah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                            Metode Pembayaran
                        </label>
                        <select id="metode_pembayaran" 
                                name="metode_pembayaran" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Metode</option>
                            <option value="tunai" {{ old('metode_pembayaran') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer_bank" {{ old('metode_pembayaran') === 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="e_wallet" {{ old('metode_pembayaran') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="cek" {{ old('metode_pembayaran') === 'cek' ? 'selected' : '' }}>Cek</option>
                            <option value="lainnya" {{ old('metode_pembayaran') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Bukti Pengeluaran -->
                    <div>
                        <label for="bukti_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                            Bukti Pengeluaran
                        </label>
                        <input type="file" 
                               id="bukti_pengeluaran" 
                               name="bukti_pengeluaran" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bukti_pengeluaran') border-red-500 @enderror"
                               accept="image/*,.pdf">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 2MB</p>
                        @error('bukti_pengeluaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan
                        </label>
                        <textarea id="keterangan" 
                                  name="keterangan" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Informasi Penting</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan semua data yang dimasukkan sudah benar sebelum menyimpan</li>
                                <li>Upload bukti pembayaran dan nota/kwitansi untuk transparansi keuangan</li>
                                <li>Pengeluaran dengan status "Pending" perlu diverifikasi oleh bendahara</li>
                                <li>Semua pengeluaran akan tercatat dalam laporan keuangan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <a href="{{ route('bendahara.pengeluaran.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                
                <div class="flex space-x-2">
                    <button type="reset" 
                            class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Pengeluaran
                    </button>
                </div>
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

    // File size validation
    const fileInputs = ['bukti_pengeluaran'];
    
    fileInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.size > 2 * 1024 * 1024) { // 2MB
                    alert('Ukuran file tidak boleh lebih dari 2MB');
                    this.value = '';
                }
            });
        }
    });
});
</script>
@endsection
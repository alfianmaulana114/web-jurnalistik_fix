@extends('layouts.bendahara')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Edit Pengeluaran</h2>
            <p class="text-sm text-gray-600 mt-1">Kode Transaksi: {{ $pengeluaran->kode_transaksi }}</p>
        </div>
        
        <form action="{{ route('bendahara.pengeluaran.update', $pengeluaran) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror"
                               required>
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Masukkan deskripsi pengeluaran..."
                                  required>{{ old('deskripsi', $pengeluaran->deskripsi) }}</textarea>
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
                            <option value="operasional" {{ old('kategori', $pengeluaran->kategori) === 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="acara" {{ old('kategori', $pengeluaran->kategori) === 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="peralatan" {{ old('kategori', $pengeluaran->kategori) === 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="konsumsi" {{ old('kategori', $pengeluaran->kategori) === 'konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                            <option value="transport" {{ old('kategori', $pengeluaran->kategori) === 'transport' ? 'selected' : '' }}>Transport</option>
                            <option value="lainnya" {{ old('kategori', $pengeluaran->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                               value="{{ old('penerima', $pengeluaran->penerima) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('penerima') border-red-500 @enderror"
                               placeholder="Nama penerima atau vendor"
                               required>
                        @error('penerima')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tujuan -->
                    <div>
                        <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-1">
                            Tujuan
                        </label>
                        <textarea id="tujuan" 
                                  name="tujuan" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tujuan atau keperluan pengeluaran">{{ old('tujuan', $pengeluaran->tujuan) }}</textarea>
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
                                   value="{{ old('jumlah', $pengeluaran->jumlah) }}"
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
                            <option value="tunai" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="e_wallet" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="lainnya" {{ old('metode_pembayaran', $pengeluaran->metode_pembayaran) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div>
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                            Bukti Pembayaran
                        </label>
                        @if($pengeluaran->bukti_pembayaran)
                            <div class="mb-2 p-3 bg-blue-50 rounded-md">
                                <p class="text-sm text-blue-700 mb-2">File saat ini: {{ basename($pengeluaran->bukti_pembayaran) }}</p>
                                <a href="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Lihat File
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="bukti_pembayaran" 
                               name="bukti_pembayaran" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bukti_pembayaran') border-red-500 @enderror"
                               accept="image/*,.pdf">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        @error('bukti_pembayaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nota/Kwitansi -->
                    <div>
                        <label for="nota_kwitansi" class="block text-sm font-medium text-gray-700 mb-1">
                            Nota/Kwitansi
                        </label>
                        @if($pengeluaran->nota_kwitansi)
                            <div class="mb-2 p-3 bg-blue-50 rounded-md">
                                <p class="text-sm text-blue-700 mb-2">File saat ini: {{ basename($pengeluaran->nota_kwitansi) }}</p>
                                <a href="{{ asset('storage/' . $pengeluaran->nota_kwitansi) }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Lihat File
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="nota_kwitansi" 
                               name="nota_kwitansi" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nota_kwitansi') border-red-500 @enderror"
                               accept="image/*,.pdf">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        @error('nota_kwitansi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                required>
                            <option value="pending" {{ old('status', $pengeluaran->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ old('status', $pengeluaran->status) === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        </select>
                        @error('status')
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
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
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
                                <li>Status "Terverifikasi" hanya dapat diubah oleh bendahara</li>
                                <li>Perubahan akan tercatat dalam log audit sistem</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <a href="{{ route('bendahara.pengeluaran.show', $pengeluaran) }}" 
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
                        <i class="fas fa-save mr-2"></i>Update Pengeluaran
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
    const fileInputs = ['bukti_pembayaran', 'nota_kwitansi'];
    
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
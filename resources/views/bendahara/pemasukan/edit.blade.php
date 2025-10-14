@extends('layouts.bendahara')

@section('title', 'Edit Pemasukan')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Edit Pemasukan</h2>
            <p class="text-sm text-gray-600 mt-1">Kode Transaksi: {{ $pemasukan->kode_transaksi }}</p>
        </div>
        
        <form action="{{ route('bendahara.pemasukan.update', $pemasukan) }}" method="POST" enctype="multipart/form-data" class="p-6">
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
                               value="{{ old('tanggal', $pemasukan->tanggal->format('Y-m-d')) }}"
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
                                  placeholder="Masukkan deskripsi pemasukan..."
                                  required>{{ old('deskripsi', $pemasukan->deskripsi) }}</textarea>
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
                            <option value="kas_anggota" {{ old('kategori', $pemasukan->kategori) === 'kas_anggota' ? 'selected' : '' }}>Kas Anggota</option>
                            <option value="donasi" {{ old('kategori', $pemasukan->kategori) === 'donasi' ? 'selected' : '' }}>Donasi</option>
                            <option value="sponsor" {{ old('kategori', $pemasukan->kategori) === 'sponsor' ? 'selected' : '' }}>Sponsor</option>
                            <option value="penjualan" {{ old('kategori', $pemasukan->kategori) === 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                            <option value="lainnya" {{ old('kategori', $pemasukan->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kas Anggota Terkait (conditional) -->
                    <div id="kasAnggotaField" class="{{ old('kategori', $pemasukan->kategori) === 'kas_anggota' ? '' : 'hidden' }}">
                        <label for="kas_anggota_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Kas Anggota Terkait
                        </label>
                        <select id="kas_anggota_id" 
                                name="kas_anggota_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kas Anggota</option>
                            @foreach($kasAnggotas as $kas)
                                <option value="{{ $kas->id }}" 
                                        data-amount="{{ \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) }}"
                                        {{ old('kas_anggota_id', $pemasukan->kas_anggota_id) == $kas->id ? 'selected' : '' }}>
                                    {{ $kas->user->name }} - 
                                    {{ ucfirst($kas->periode) }} {{ $kas->tahun }}
                                    (Rp {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000), 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sumber -->
                    <div>
                        <label for="sumber" class="block text-sm font-medium text-gray-700 mb-1">
                            Sumber <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="sumber" 
                               name="sumber" 
                               value="{{ old('sumber', $pemasukan->sumber) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sumber') border-red-500 @enderror"
                               placeholder="Contoh: John Doe, PT ABC, dll"
                               required>
                        @error('sumber')
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
                                   value="{{ old('jumlah', $pemasukan->jumlah) }}"
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
                            <option value="tunai" {{ old('metode_pembayaran', $pemasukan->metode_pembayaran) === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_pembayaran', $pemasukan->metode_pembayaran) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="e_wallet" {{ old('metode_pembayaran', $pemasukan->metode_pembayaran) === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="lainnya" {{ old('metode_pembayaran', $pemasukan->metode_pembayaran) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Bukti Transfer -->
                    <div>
                        <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-1">
                            Bukti Transfer
                        </label>
                        @if($pemasukan->bukti_transfer)
                            <div class="mb-2 p-3 bg-blue-50 rounded-md">
                                <p class="text-sm text-blue-700 mb-2">File saat ini: {{ basename($pemasukan->bukti_transfer) }}</p>
                                <a href="{{ asset('storage/' . $pemasukan->bukti_transfer) }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Lihat File
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="bukti_transfer" 
                               name="bukti_transfer" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bukti_transfer') border-red-500 @enderror"
                               accept="image/*,.pdf">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        @error('bukti_transfer')
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
                            <option value="pending" {{ old('status', $pemasukan->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ old('status', $pemasukan->status) === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
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
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $pemasukan->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan semua data yang dimasukkan sudah benar sebelum menyimpan</li>
                                <li>Jika mengubah kategori menjadi "Kas Anggota", pilih kas anggota yang sesuai</li>
                                <li>Upload bukti transfer untuk transparansi keuangan</li>
                                <li>Status "Terverifikasi" hanya dapat diubah oleh bendahara</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <a href="{{ route('bendahara.pemasukan.show', $pemasukan) }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                
                <div class="flex space-x-2">
                    <button type="reset" 
                            class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Pemasukan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kategoriSelect = document.getElementById('kategori');
    const kasAnggotaField = document.getElementById('kasAnggotaField');
    const kasAnggotaSelect = document.getElementById('kas_anggota_id');
    const jumlahInput = document.getElementById('jumlah');

    // Toggle kas anggota field based on kategori
    kategoriSelect.addEventListener('change', function() {
        if (this.value === 'kas_anggota') {
            kasAnggotaField.classList.remove('hidden');
            kasAnggotaSelect.required = true;
        } else {
            kasAnggotaField.classList.add('hidden');
            kasAnggotaSelect.required = false;
            kasAnggotaSelect.value = '';
        }
    });

    // Auto-fill amount when kas anggota is selected
    kasAnggotaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.amount) {
            jumlahInput.value = selectedOption.dataset.amount;
        }
    });

    // Format number input
    jumlahInput.addEventListener('input', function() {
        // Remove any non-digit characters except for the decimal point
        let value = this.value.replace(/[^\d]/g, '');
        this.value = value;
    });
});
</script>
@endsection
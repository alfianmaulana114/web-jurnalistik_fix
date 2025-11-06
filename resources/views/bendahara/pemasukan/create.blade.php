@extends('layouts.bendahara')

@section('title', 'Tambah Pemasukan Baru')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Tambah Pemasukan Baru</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('bendahara.pemasukan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <!-- Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal_pemasukan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tanggal_pemasukan') border-red-500 @enderror" 
                                   id="tanggal_pemasukan" name="tanggal_pemasukan" value="{{ old('tanggal_pemasukan', date('Y-m-d')) }}" required>
                            @error('tanggal_pemasukan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" required
                                      placeholder="Deskripsi detail pemasukan...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('kategori') border-red-500 @enderror" 
                                    id="kategori" name="kategori" required onchange="toggleKasAnggotaField()">
                                <option value="">Pilih Kategori</option>
                                @foreach(App\Models\Pemasukan::getKategoriOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jumlah') border-red-500 @enderror" 
                                       id="jumlah" name="jumlah" value="{{ old('jumlah') }}" 
                                       placeholder="0" min="0" step="1000" required>
                            </div>
                            @error('jumlah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sumber -->
                        <div class="mb-4">
                            <label for="sumber_pemasukan" class="block text-sm font-medium text-gray-700 mb-1">Sumber <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('sumber_pemasukan') border-red-500 @enderror" 
                                   id="sumber_pemasukan" name="sumber_pemasukan" value="{{ old('sumber_pemasukan') }}" 
                                   placeholder="Sumber pemasukan..." required>
                            @error('sumber_pemasukan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <!-- Kas Anggota (for kas_anggota category) -->
                        <div class="mb-4" id="kas_anggota_group" style="display: none;">
                            <label for="kas_anggota_id" class="block text-sm font-medium text-gray-700 mb-1">Kas Anggota Terkait</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('kas_anggota_id') border-red-500 @enderror" 
                                    id="kas_anggota_id" name="kas_anggota_id">
                                <option value="">Pilih Kas Anggota (Opsional)</option>
                                @foreach($kasAnggotaBelumLunas as $kas)
                                    <option value="{{ $kas->id }}" {{ old('kas_anggota_id') == $kas->id ? 'selected' : '' }}
                                            data-jumlah="{{ $kas->jumlah_hutang }}">
                                        {{ $kas->user->name }} - {{ ucfirst($kas->periode) }} {{ $kas->tahun }} 
                                        (Sisa: Rp {{ number_format($kas->jumlah_hutang, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-gray-500 text-xs mt-1">Pilih jika pemasukan ini adalah pembayaran kas anggota</p>
                            @error('kas_anggota_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-4">
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('metode_pembayaran') border-red-500 @enderror" 
                                    id="metode_pembayaran" name="metode_pembayaran">
                                <option value="">Pilih Metode Pembayaran</option>
                                @foreach(App\Models\Pemasukan::getMetodePembayaranOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ old('metode_pembayaran') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bukti Transfer -->
                        <div class="mb-4">
                            <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('bukti_transfer') border-red-500 @enderror" 
                                   id="bukti_transfer" name="bukti_transfer" accept="image/*,.pdf">
                            <p class="text-gray-500 text-xs mt-1">
                                Upload bukti transfer (JPG, PNG, PDF). Maksimal 5MB.
                            </p>
                            @error('bukti_transfer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                    id="status" name="status" required>
                                @foreach(App\Models\Pemasukan::getStatusOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ old('status', 'pending') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-4">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror" 
                                      id="keterangan" name="keterangan" rows="3" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 rounded-lg p-3">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>Informasi:
                            </h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Pemasukan akan otomatis mendapat kode transaksi</li>
                                <li>• Status "Pending" memerlukan verifikasi bendahara</li>
                                <li>• Jika kategori "Kas Anggota", pilih anggota terkait</li>
                                <li>• Upload bukti transfer untuk transparansi</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
                    <a href="{{ route('bendahara.pemasukan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Pemasukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleKasAnggotaField() {
    const kategori = document.getElementById('kategori').value;
    const kasAnggotaGroup = document.getElementById('kas_anggota_group');
    
    if (kategori === 'kas_anggota') {
        kasAnggotaGroup.style.display = 'block';
    } else {
        kasAnggotaGroup.style.display = 'none';
        document.getElementById('kas_anggota_id').value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const kasAnggotaSelect = document.getElementById('kas_anggota_id');
    const jumlahInput = document.getElementById('jumlah');
    
    // Auto-fill amount when kas anggota is selected
    kasAnggotaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.jumlah && !jumlahInput.value) {
            jumlahInput.value = selectedOption.dataset.jumlah;
        }
    });
    
    // Initialize on page load
    toggleKasAnggotaField();
});
</script>
@endsection
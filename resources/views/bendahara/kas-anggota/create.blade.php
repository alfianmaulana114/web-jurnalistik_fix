@extends('layouts.bendahara')

@section('title', 'Tambah Kas Anggota Baru')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Tambah Kas Anggota Baru</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('bendahara.kas-anggota.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <!-- Anggota -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Anggota <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror" 
                                    id="user_id" name="user_id" required>
                                <option value="">Pilih Anggota</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->getDivision() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Periode -->
                        <div class="mb-4">
                            <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Periode <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('periode') border-red-500 @enderror" 
                                    id="periode" name="periode" required>
                                <option value="">Pilih Periode</option>
                                @foreach(\App\Models\KasAnggota::getAllPeriode() as $key => $value)
                                    <option value="{{ $key }}" {{ old('periode', date('n')) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bulan -->
                        <div class="mb-4" style="display: none;">
                            <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('bulan') border-red-500 @enderror" 
                                    id="bulan" name="bulan" required>
                                <option value="">Pilih Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                            @error('bulan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun -->
                        <div class="mb-4">
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tahun') border-red-500 @enderror" 
                                    id="tahun" name="tahun" required>
                                <option value="">Pilih Tahun</option>
                                @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}" {{ old('tahun', date('Y')) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                            @error('tahun')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Terbayar -->
                        <div class="mb-4">
                            <label for="jumlah_terbayar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Terbayar</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jumlah_terbayar') border-red-500 @enderror"
                                       id="jumlah_terbayar" name="jumlah_terbayar" value="{{ \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) }}"
                                       min="0" step="1000">
                            </div>
                            <p class="text-gray-500 text-xs mt-1">Nilai default {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000), 0, ',', '.') }}, dapat diedit sesuai kebutuhan</p>
                            @error('jumlah_terbayar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <!-- Jumlah Dibayar -->
                        <div class="mb-4">
                            <label for="jumlah_dibayar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dibayar</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jumlah_dibayar') border-red-500 @enderror" 
                                       id="jumlah_dibayar" name="jumlah_dibayar" value="{{ old('jumlah_dibayar', 0) }}" 
                                       min="0" max="{{ \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) }}" step="1000" placeholder="0">
                            </div>
                            <p class="text-gray-500 text-xs mt-1">Kosongkan jika belum ada pembayaran</p>
                            @error('jumlah_dibayar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Dibayar -->
                        <div class="mb-4">
                            <label for="tanggal_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tanggal_pembayaran') border-red-500 @enderror"
                       id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran') }}">
                <p class="text-gray-500 text-xs mt-1">Otomatis terisi saat jumlah dibayar diisi</p>
                @error('tanggal_pembayaran')
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
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
                    <a href="{{ route('bendahara.kas-anggota.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Kas Anggota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto calculate jumlah_hutang when jumlah_terbayar changes
document.addEventListener('DOMContentLoaded', function() {
    const jumlahTerbayar = document.getElementById('jumlah_terbayar');
    const tanggalPembayaran = document.getElementById('tanggal_pembayaran');
    
    // Auto set tanggal_pembayaran when jumlah_terbayar is filled
    jumlahTerbayar.addEventListener('input', function() {
        if (this.value > 0 && !tanggalPembayaran.value) {
            tanggalPembayaran.value = new Date().toISOString().split('T')[0];
        } else if (this.value == 0) {
            tanggalPembayaran.value = '';
        }
    });
});
</script>
@endsection
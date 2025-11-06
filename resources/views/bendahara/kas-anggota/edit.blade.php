@extends('layouts.bendahara')

@section('title', 'Edit Kas Anggota')
@section('header', 'Edit Kas Anggota')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6">
            <form action="{{ route('bendahara.kas-anggota.update', $kasAnggota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <!-- Anggota -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Anggota <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror" 
                                    id="user_id" name="user_id" required>
                                <option value="">Pilih Anggota</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $kasAnggota->user_id) == $user->id ? 'selected' : '' }}>
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
                                    <option value="{{ $key }}" {{ old('periode', $kasAnggota->periode) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode')
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
                                    <option value="{{ $year }}" {{ old('tahun', $kasAnggota->tahun) == $year ? 'selected' : '' }}>
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
                                       id="jumlah_terbayar" name="jumlah_terbayar" value="{{ old('jumlah_terbayar', $kasAnggota->jumlah_terbayar) }}"
                                       min="0" step="1000">
                            </div>
                            <p class="text-gray-500 text-xs mt-1">Nilai default {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000), 0, ',', '.') }}, dapat diedit sesuai kebutuhan</p>
                            @error('jumlah_terbayar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <!-- Tanggal Dibayar -->
                        <div class="mb-4">
                            <label for="tanggal_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tanggal_pembayaran') border-red-500 @enderror"
                                   id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', $kasAnggota->tanggal_pembayaran?->format('Y-m-d')) }}">
                            <p class="text-gray-500 text-xs mt-1">Otomatis terisi saat jumlah dibayar diisi</p>
                            @error('tanggal_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status (Calculated) -->
                        <div class="mb-4">
                            <label for="status_display" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="flex items-center space-x-2">
                                <span id="status_badge" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $kasAnggota->status === 'lunas' ? 'bg-green-100 text-green-800' : 
                                       ($kasAnggota->status === 'belum_lunas' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $kasAnggota->status)) }}
                                </span>
                                <span class="text-gray-500 text-xs">(Otomatis berdasarkan pembayaran)</span>
                            </div>
                        </div>

                        <!-- Sisa Hutang (Calculated) -->
                        <div class="mb-4">
                            <label for="jumlah_hutang_display" class="block text-sm font-medium text-gray-700 mb-1">Sisa Hutang</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="text" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" 
                                       id="jumlah_hutang_display" readonly>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-4">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror" 
                                      id="keterangan" name="keterangan" rows="4" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $kasAnggota->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Status Info -->
                        <div class="bg-blue-50 rounded-lg p-3">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Saat Ini:</h4>
                            <div class="text-xs text-blue-700 space-y-1">
                                <p>Dibuat: {{ $kasAnggota->created_at->format('d/m/Y H:i') }}</p>
                                <p>Terakhir diupdate: {{ $kasAnggota->updated_at->format('d/m/Y H:i') }}</p>
                                @if($kasAnggota->verified_by)
                                <p>Diverifikasi oleh: {{ $kasAnggota->verifiedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
                    <a href="{{ route('bendahara.kas-anggota.show', $kasAnggota) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Kas Anggota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateHutang() {
    const jumlahTerbayar = parseFloat(document.getElementById('jumlah_terbayar').value) || 0;
    const standardAmount = {{ \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) }};
    const jumlahHutang = Math.max(0, standardAmount - jumlahTerbayar);
    
    // Update display
    document.getElementById('jumlah_hutang_display').value = new Intl.NumberFormat('id-ID').format(jumlahHutang);
    
    // Update status badge
    const statusBadge = document.getElementById('status_badge');
    
    let status, statusClass;
    
    if (jumlahHutang === 0) {
        status = 'Lunas';
        statusClass = 'bg-green-100 text-green-800';
    } else {
        status = 'Belum Lunas';
        statusClass = 'bg-yellow-100 text-yellow-800';
    }
    
    statusBadge.textContent = status;
    statusBadge.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const jumlahTerbayar = document.getElementById('jumlah_terbayar');
    const tanggalTerbayar = document.getElementById('tanggal_pembayaran');
    
    // Calculate initial hutang
    calculateHutang();
    
    // Auto set tanggal_pembayaran when jumlah_terbayar is filled
    jumlahTerbayar.addEventListener('input', function() {
        if (this.value > 0 && !tanggalTerbayar.value) {
            tanggalTerbayar.value = new Date().toISOString().split('T')[0];
        } else if (this.value == 0) {
            tanggalTerbayar.value = '';
        }
        calculateHutang();
    });
});
</script>
@endsection
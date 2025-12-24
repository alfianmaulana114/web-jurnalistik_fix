@extends('layouts.koordinator-humas')

@section('title', 'Edit Penjadwalan')
@section('header', 'Edit Penjadwalan')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm p-6">
        <h3 class="text-2xl font-medium text-[#1b334e] mb-6">Edit Penjadwalan</h3>

        <form action="{{ route('koordinator-humas.penjadwalan.update', $penjadwalan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Anggota Humas <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] {{ $errors->has('user_id') ? 'border-red-500' : '' }}">
                        <option value="">Pilih Anggota</option>
                        @foreach($anggotaHumas as $anggota)
                            <option value="{{ $anggota->id }}" {{ old('user_id', $penjadwalan->user_id) == $anggota->id ? 'selected' : '' }}>{{ $anggota->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $penjadwalan->tanggal->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] {{ $errors->has('tanggal') ? 'border-red-500' : '' }}">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a]" placeholder="Keterangan tambahan...">{{ old('keterangan', $penjadwalan->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                        <option value="pending" {{ old('status', $penjadwalan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('status', $penjadwalan->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ old('status', $penjadwalan->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('koordinator-humas.penjadwalan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-[#1b334e] text-white rounded-lg hover:bg-[#1b334e]/90 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
@endpush
@endsection


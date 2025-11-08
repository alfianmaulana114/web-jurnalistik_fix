@extends('layouts.koordinator-jurnalistik')

@section('title', 'Edit Brief Berita')
@section('header', 'Edit Brief Berita')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Edit Brief Berita</h3>

        <form action="{{ route('koordinator-jurnalistik.briefs.update', $brief) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Brief <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $brief->judul) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('judul') border-red-500 @enderror" required>
                    @error('judul')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $brief->tanggal ? $brief->tanggal->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="isi_brief" class="block text-sm font-medium text-gray-700 mb-2">Isi Brief <span class="text-red-500">*</span></label>
                    <textarea name="isi_brief" id="isi_brief" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('isi_brief') border-red-500 @enderror" placeholder="Isi lengkap brief berita" required>{{ old('isi_brief', $brief->isi_brief) }}</textarea>
                    @error('isi_brief')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="link_referensi" class="block text-sm font-medium text-gray-700 mb-2">Link Referensi</label>
                    <textarea name="link_referensi" id="link_referensi" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('link_referensi') border-red-500 @enderror" placeholder="Masukkan link referensi (pisahkan dengan enter untuk multiple link)">{{ old('link_referensi', $brief->link_referensi) }}</textarea>
                    @error('link_referensi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('koordinator-jurnalistik.briefs.show', $brief) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Batal</a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
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
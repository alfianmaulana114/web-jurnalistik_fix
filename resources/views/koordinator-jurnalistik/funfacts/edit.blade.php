@extends('layouts.koordinator-jurnalistik')

@section('title', 'Edit Funfact')
@section('header', 'Edit Funfact')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Edit Funfact</h3>

        <form action="{{ route('koordinator-jurnalistik.funfacts.update', $funfact) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Funfact <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $funfact->judul) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('judul') border-red-500 @enderror" required>
                    @error('judul')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">Isi Funfact <span class="text-red-500">*</span></label>
                    <textarea name="isi" id="isi" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('isi') border-red-500 @enderror" placeholder="Tulis isi funfact di sini..." required>{{ old('isi', $funfact->isi) }}</textarea>
                    @error('isi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="link_referensi" class="block text-sm font-medium text-gray-700 mb-2">Link Referensi</label>
                    <textarea name="link_referensi" id="link_referensi" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('link_referensi') border-red-500 @enderror" placeholder="Masukkan link referensi (pisahkan dengan enter untuk multiple link)">{{ old('link_referensi', $funfact->link_referensi) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Pisahkan setiap link dengan baris baru</p>
                    @error('link_referensi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('koordinator-jurnalistik.funfacts.show', $funfact) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Batal</a>
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


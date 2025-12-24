@extends('layouts.koordinator-media-kreatif')

@section('title', 'Edit Desain - ' . $design->judul)
@section('header', 'Edit Desain')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm p-6">
        <h3 class="text-2xl font-medium text-[#1b334e] mb-6">Edit Desain Media</h3>

        <form action="{{ route('koordinator-media-kreatif.designs.update', $design) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Otomatis dari Berita -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul (otomatis dari berita)</label>
                    <input type="text" id="judul_auto" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg bg-gray-100 focus:ring-[#f9b61a] focus:border-[#f9b61a]" value="{{ $design->berita->title ?? $design->judul }}" placeholder="Pilih berita untuk menampilkan judul" disabled>
                    <p class="text-xs text-gray-500 mt-1">Judul akan disesuaikan dengan judul berita terpilih.</p>
                </div>

                <!-- Jenis -->
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                    @php($jenisOptions = \App\Models\Design::getJenisOptions())
                    <select name="jenis" id="jenis" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis', $design->jenis) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Media -->
                <div class="col-span-2">
                    <label for="media_url" class="block text-sm font-medium text-gray-700 mb-2">Link Media <span class="text-red-500">*</span></label>
                    <input type="url" name="media_url" id="media_url" value="{{ old('media_url', $design->media_url) }}" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a]" placeholder="https://...">
                    @error('media_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Berita terkait -->
                <div class="col-span-2">
                    <label for="berita_id" class="block text-sm font-medium text-gray-700 mb-2">Terkait Berita (Opsional)</label>
                    <select name="berita_id" id="berita_id" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                        <option value="">Pilih Berita</option>
                        @foreach($availableNews as $news)
                            <option value="{{ $news->id }}" data-title="{{ $news->title }}" {{ old('berita_id', $design->berita_id) == $news->id ? 'selected' : '' }}>{{ $news->title }}</option>
                        @endforeach
                    </select>
                    @error('berita_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="col-span-2">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="4" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a]" placeholder="Catatan tambahan">{{ old('catatan', $design->catatan) }}</textarea>
                    @error('catatan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('koordinator-media-kreatif.designs.show', $design) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
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
    const beritaSelect = document.getElementById('berita_id');
    const judulAuto = document.getElementById('judul_auto');
    function updateJudulAuto() {
        const opt = beritaSelect.options[beritaSelect.selectedIndex];
        const title = opt ? opt.getAttribute('data-title') : '';
        if (title) {
            judulAuto.value = title;
        }
    }
    beritaSelect.addEventListener('change', updateJudulAuto);
    
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


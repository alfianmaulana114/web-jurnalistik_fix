@extends('layouts.koordinator-jurnalistik')

@section('title', 'Tambah Desain')
@section('header', 'Tambah Desain')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Tambah Desain Media</h3>

        <form action="{{ route('koordinator-jurnalistik.designs.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Otomatis dari Berita -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul (otomatis dari berita)</label>
                    <input type="text" id="judul_auto" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:ring-2 focus:ring-blue-400 focus:border-transparent" value="{{ $selectedNewsTitle ?? '' }}" placeholder="Pilih berita untuk menampilkan judul" disabled>
                    <p class="text-xs text-gray-500 mt-1">Judul akan disesuaikan dengan judul berita terpilih.</p>
                </div>

                <!-- Jenis -->
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                    @php($jenisOptions = \App\Models\Design::getJenisOptions())
                    <select name="jenis" id="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Media -->
                <div class="col-span-2">
                    <label for="media_url" class="block text-sm font-medium text-gray-700 mb-2">Link Media <span class="text-red-500">*</span></label>
                    <input type="url" name="media_url" id="media_url" value="{{ old('media_url') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" placeholder="https://...">
                    @error('media_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Berita terkait -->
                <div class="col-span-2">
                    <label for="berita_id" class="block text-sm font-medium text-gray-700 mb-2">Terkait Berita (Opsional)</label>
                    <select name="berita_id" id="berita_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        <option value="">Pilih Berita</option>
                        @foreach($availableNews as $news)
                            <option value="{{ $news->id }}" data-title="{{ $news->title ?? $news->judul }}" {{ old('berita_id', $selectedNews->id ?? null) == $news->id ? 'selected' : '' }}>{{ $news->title ?? $news->judul }}</option>
                        @endforeach
                    </select>
                    @error('berita_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="col-span-2">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('koordinator-jurnalistik.designs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Batal</a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan
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
        judulAuto.value = title || '';
    }
    beritaSelect.addEventListener('change', updateJudulAuto);
    // Initialize on load
    updateJudulAuto();
    
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
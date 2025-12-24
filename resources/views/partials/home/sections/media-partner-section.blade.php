<!-- Media Partner Section -->
<div class="mb-12">
    <div class="flex items-center justify-between border-b-2 border-gray-300 mb-6 pb-2">
        <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">Media Partner</h2>
        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua →</a>
    </div>
    @if($mediaPartnerNews->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($mediaPartnerNews as $mediaPartner)
                <x-news-card :news="$mediaPartner" variant="media-partner" />
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">Belum ada berita media partner</p>
        </div>
    @endif
</div>


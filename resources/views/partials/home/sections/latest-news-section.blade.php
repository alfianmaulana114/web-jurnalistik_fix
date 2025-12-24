<!-- Latest News Section -->
<div>
    <div class="flex items-center justify-between border-b-2 border-gray-300 mb-6 pb-2">
        <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">Berita Terbaru</h2>
        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua →</a>
    </div>
    @if($allNews->count() > 0)
        <div class="space-y-6">
            @foreach($allNews as $news)
                <x-news-card :news="$news" />
            @endforeach
        </div>
        <div class="mt-8 flex justify-center">
            {{ $allNews->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">Belum ada berita tersedia</p>
        </div>
    @endif
</div>


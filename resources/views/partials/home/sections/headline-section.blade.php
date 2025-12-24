<!-- Headline Section -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
    @if($latestNews)
        <!-- Main Headline -->
        <div class="lg:col-span-8">
            <x-news-headline :news="$latestNews" />
        </div>

        <!-- Side Headlines -->
        <div class="lg:col-span-4 space-y-4">
            @forelse($recentNews as $news)
                <x-news-sidebar-card :news="$news" />
            @empty
                <p class="text-gray-500 text-sm">Tidak ada berita terbaru</p>
            @endforelse
        </div>
    @else
        <div class="col-span-12 text-center py-12">
            <p class="text-gray-500">Belum ada berita tersedia</p>
        </div>
    @endif
</div>


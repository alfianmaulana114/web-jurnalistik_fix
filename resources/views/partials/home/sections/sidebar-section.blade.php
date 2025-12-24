<!-- Sidebar -->
<div class="lg:col-span-4">
    <!-- Popular News -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 border-b-2 border-blue-600 -mb-[2px] pb-3 mb-6 uppercase tracking-tight">Berita Populer</h2>
        @if($recentNews->count() > 0)
            <div class="space-y-5">
                @foreach($recentNews->take(5) as $index => $news)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                        <span class="text-3xl font-bold text-blue-600 leading-none min-w-[2rem]">{{ $index + 1 }}</span>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2 hover:text-blue-600 transition-colors">
                                <a href="{{ route('news.show', $news->slug) }}">
                                    {{ $news->title }}
                                </a>
                            </h3>
                            <span class="text-xs text-gray-500">
                                {{ $news->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Tidak ada berita populer</p>
        @endif
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 border-b-2 border-blue-600 -mb-[2px] pb-3 mb-6 uppercase tracking-tight">Kategori</h2>
        <div class="space-y-2">
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors text-gray-700">Nasional</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors text-gray-700">Internasional</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors text-gray-700">Media Partner</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors text-gray-700">Teknologi</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors text-gray-700">Olahraga</a>
        </div>
    </div>
</div>


@extends('layouts.koordinator-jurnalistik')

@section('title', 'Detail Desain - ' . $design->judul)
@section('header', 'Detail Desain')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $design->judul }}</h1>
                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                        <span>Dibuat {{ $design->created_at->format('d M Y, H:i') }}</span>
                        @if($design->creator)
                            <span>oleh {{ $design->creator->name }}</span>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $design->type)) }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('koordinator-jurnalistik.designs.edit', $design) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <form action="{{ route('koordinator-jurnalistik.designs.destroy', $design) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus desain ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Design Preview -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Preview Desain</h3>
                </div>
                <div class="p-6">
                    @if($design->file_path)
                        <div class="text-center">
                            @php
                                $fileExtension = pathinfo($design->file_path, PATHINFO_EXTENSION);
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            @endphp
                            
                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                <img src="{{ asset('storage/' . $design->file_path) }}" alt="{{ $design->judul }}" class="max-w-full h-auto rounded-lg shadow-lg mx-auto" style="max-height: 500px;">
                            @else
                                <div class="bg-gray-100 rounded-lg p-12 text-center">
                                    <i class="fas fa-file-alt text-6xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900">{{ strtoupper($fileExtension) }} File</p>
                                    <p class="text-sm text-gray-500 mt-2">{{ $design->judul }}</p>
                                    <a href="{{ asset('storage/' . $design->file_path) }}" target="_blank" class="inline-flex items-center mt-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                                        <i class="fas fa-download mr-2"></i>
                                        Download File
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-lg p-12 text-center">
                            <i class="fas fa-image text-6xl text-gray-400 mb-4"></i>
                            <p class="text-lg font-medium text-gray-900">Belum ada file</p>
                            <p class="text-sm text-gray-500 mt-2">File desain belum diupload</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Deskripsi</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $design->deskripsi }}</p>
                </div>
            </div>

            <!-- Revision Notes -->
            @if($design->catatan_revisi)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Catatan Revisi</h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sticky-note text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 whitespace-pre-line">{{ $design->catatan_revisi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Content/Proker -->
            @if($design->content || $design->proker)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Terkait Dengan</h3>
                    </div>
                    <div class="p-6">
                        @if($design->content)
                            <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-newspaper text-2xl text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $design->content->judul }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($design->content->type) }}
                                        </span>
                                        <span class="ml-2">oleh {{ $design->content->creator->name ?? 'N/A' }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-2">{{ Str::limit($design->content->konten, 150) }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('koordinator-jurnalistik.contents.show', $design->content) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat Konten <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($design->proker)
                            <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-project-diagram text-2xl text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $design->proker->nama }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ ucfirst($design->proker->status) }}
                                        </span>
                                        <span class="ml-2">{{ $design->proker->tanggal_mulai->format('d M Y') }} - {{ $design->proker->tanggal_selesai->format('d M Y') }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-2">{{ Str::limit($design->proker->deskripsi, 150) }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('koordinator-jurnalistik.prokers.show', $design->proker) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Lihat Program Kerja <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Quick Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                'review' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'needs_revision' => 'bg-red-100 text-red-800'
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'in_progress' => 'Dalam Proses',
                                'review' => 'Review',
                                'completed' => 'Selesai',
                                'needs_revision' => 'Perlu Revisi'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$design->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$design->status] ?? ucfirst($design->status) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tipe Desain</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $design->type)) }}</p>
                    </div>

                    @if($design->lebar && $design->tinggi)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dimensi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $design->lebar }} x {{ $design->tinggi }} px</p>
                        </div>
                    @endif

                    @if($design->ukuran_file)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Ukuran File</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($design->ukuran_file) }} KB</p>
                        </div>
                    @endif

                    @if($design->creator)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Pembuat</label>
                            <div class="mt-1 flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-red-800">{{ substr($design->creator->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $design->creator->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $design->creator->role }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($design->reviewer)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Reviewer</label>
                            <div class="mt-1 flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-800">{{ substr($design->reviewer->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $design->reviewer->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $design->reviewer->role }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Dibuat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $design->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    @if($design->updated_at != $design->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $design->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($design->file_path)
                        <a href="{{ asset('storage/' . $design->file_path) }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-download mr-2"></i>
                            Download File
                        </a>
                    @endif

                    @if($design->status === 'draft')
                        <form action="{{ route('koordinator-jurnalistik.designs.update', $design) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <i class="fas fa-play mr-2"></i>
                                Mulai Proses
                            </button>
                        </form>
                    @endif

                    @if($design->status === 'in_progress')
                        <form action="{{ route('koordinator-jurnalistik.designs.update', $design) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="review">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim untuk Review
                            </button>
                        </form>
                    @endif

                    @if($design->status === 'review')
                        <div class="space-y-2">
                            <form action="{{ route('koordinator-jurnalistik.designs.update', $design) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('Setujui desain ini?')">
                                    <i class="fas fa-check mr-2"></i>
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('koordinator-jurnalistik.designs.update', $design) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="needs_revision">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Minta revisi untuk desain ini?')">
                                    <i class="fas fa-undo mr-2"></i>
                                    Minta Revisi
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($design->status === 'needs_revision')
                        <form action="{{ route('koordinator-jurnalistik.designs.update', $design) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <i class="fas fa-edit mr-2"></i>
                                Mulai Revisi
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('koordinator-jurnalistik.designs.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-copy mr-2"></i>
                        Duplikasi Desain
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Total Views</span>
                        <span class="text-sm font-medium text-gray-900">{{ $design->views ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Downloads</span>
                        <span class="text-sm font-medium text-gray-900">{{ $design->downloads ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Revisi</span>
                        <span class="text-sm font-medium text-gray-900">{{ $design->revision_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
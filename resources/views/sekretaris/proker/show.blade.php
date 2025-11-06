@extends('layouts.sekretaris')

@section('title', 'Detail Program Kerja')
@section('header', 'Detail Program Kerja')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $proker->nama_proker }}</h1>
            <p class="mt-1 text-sm text-gray-600">Dibuat oleh {{ $proker->creator->name }} pada {{ $proker->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('sekretaris.proker.edit', $proker) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('sekretaris.proker.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Proker Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Program Kerja</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $proker->deskripsi }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $proker->tanggal_mulai->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $proker->tanggal_selesai->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Durasi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $proker->tanggal_mulai->diffInDays($proker->tanggal_selesai) + 1 }} hari</p>
                    </div>

                    @if($proker->catatan)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $proker->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Committee -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Susunan Panitia</h3>
                        <span class="text-sm text-gray-500">{{ $proker->committees->count() }} orang</span>
                    </div>
                </div>
                <div class="p-6">
                    @if($proker->committees->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($proker->committees as $committee)
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-red-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $committee->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $committee->role }}</p>
                                @if($committee->pivot->jabatan)
                                <p class="text-xs text-red-600 font-medium">{{ $committee->pivot->jabatan }}</p>
                                @endif
                                @if($committee->pivot->tugas)
                                <p class="text-xs text-gray-500 mt-1">{{ $committee->pivot->tugas }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500">Belum ada panitia yang ditugaskan</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Designs -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Desain Terkait</h3>
                        <a href="{{ route('koordinator-jurnalistik.designs.create', ['proker_id' => $proker->id]) }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Desain
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($proker->designs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($proker->designs as $design)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $design->judul }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $design->getTypeLabel() }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($design->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($design->status === 'review') bg-yellow-100 text-yellow-800
                                    @elseif($design->status === 'approved') bg-green-100 text-green-800
                                    @elseif($design->status === 'published') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $design->getStatusLabel() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">{{ Str::limit($design->deskripsi, 60) }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $design->creator->name }}</span>
                                <a href="{{ route('koordinator-jurnalistik.designs.show', $design) }}" class="text-red-600 hover:text-red-800">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-palette text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 mb-4">Belum ada desain untuk program kerja ini</p>
                        <a href="{{ route('koordinator-jurnalistik.designs.create', ['proker_id' => $proker->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Desain
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($proker->status === 'planning') bg-yellow-100 text-yellow-800
                            @elseif($proker->status === 'ongoing') bg-green-100 text-green-800
                            @elseif($proker->status === 'completed') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($proker->status === 'planning')
                                <i class="fas fa-clock mr-2"></i>
                            @elseif($proker->status === 'ongoing')
                                <i class="fas fa-play mr-2"></i>
                            @elseif($proker->status === 'completed')
                                <i class="fas fa-check mr-2"></i>
                            @else
                                <i class="fas fa-times mr-2"></i>
                            @endif
                            {{ $proker->getStatusLabel() }}
                        </span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress</span>
                            <span>{{ $proker->getProgress() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $proker->getProgress() }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Anggota Panitia</span>
                        <span class="text-sm font-medium text-gray-900">{{ $proker->committees->count() }} orang</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Desain Terkait</span>
                        <span class="text-sm font-medium text-gray-900">{{ $proker->designs->count() }} desain</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Dibuat</span>
                        <span class="text-sm font-medium text-gray-900">{{ $proker->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Terakhir Update</span>
                        <span class="text-sm font-medium text-gray-900">{{ $proker->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('sekretaris.proker.edit', $proker) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Program Kerja
                    </a>
                    <form action="{{ route('sekretaris.proker.destroy', $proker) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program kerja ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Program Kerja
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

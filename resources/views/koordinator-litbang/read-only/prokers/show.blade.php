@extends($layout ?? 'layouts.koordinator-litbang')

@section('title', 'Detail Program Kerja')
@section('header', 'Detail Program Kerja (Read-Only)')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Read-Only Banner --}}
    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">{{ $proker->nama_proker }}</h1>
            <p class="mt-1 text-sm text-gray-600">Dibuat oleh {{ $proker->creator->name }} pada {{ $proker->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route(($routePrefix ?? 'koordinator-litbang.view').'.prokers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
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
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Informasi Program Kerja</h3>
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
            @if($proker->panitia && $proker->panitia->count() > 0)
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-[#1b334e]">Susunan Panitia</h3>
                        <span class="text-sm text-gray-500">{{ $proker->panitia->count() }} orang</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($proker->panitia as $panitia)
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#1b334e] rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ strtoupper(substr($panitia->name, 0, 2)) }}</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-[#1b334e]">{{ $panitia->name }}</h4>
                                @if($panitia->pivot && $panitia->pivot->jabatan_panitia)
                                <p class="text-xs text-[#f9b61a] font-medium mt-1">{{ $panitia->pivot->jabatan_panitia }}</p>
                                @endif
                                @if($panitia->pivot && $panitia->pivot->tugas_khusus)
                                <p class="text-xs text-gray-500 mt-1">{{ $panitia->pivot->tugas_khusus }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Status</h3>
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
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Anggota Panitia</span>
                        <span class="text-sm font-medium text-[#1b334e]">{{ $proker->panitia->count() }} orang</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Dibuat</span>
                        <span class="text-sm font-medium text-[#1b334e]">{{ $proker->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Terakhir Update</span>
                        <span class="text-sm font-medium text-[#1b334e]">{{ $proker->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


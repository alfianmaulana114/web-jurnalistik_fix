@extends($layout ?? 'layouts.koordinator-humas')

@section('title', 'Detail Caption')
@section('header', 'Detail Caption (Read-Only)')

@section('content')
<div class="container-fluid">
    {{-- Read-Only Banner --}}
    <div class="mb-4 rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border border-[#D8C4B6]/40">
                <div class="card-header bg-white border-b border-[#D8C4B6]/40">
                    <h3 class="card-title text-[#1b334e]">Detail Caption</h3>
                    <div class="card-tools">
                        <a href="{{ route(($routePrefix ?? 'koordinator-humas.view').'.contents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-md-8">
                            <div class="card border border-[#D8C4B6]/40">
                                <div class="card-header bg-white border-b border-[#D8C4B6]/40">
                                    <h4 class="card-title text-[#1b334e]">{{ $content->judul }}</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Caption Type -->
                                    <div class="mb-3">
                                        <span class="badge badge-info mr-2">
                                            {{ App\Models\Content::getCaptionTypes()[$content->jenis_konten] ?? $content->jenis_konten }}
                                        </span>
                                    </div>

                                    <!-- News Reference -->
                                    @if($content->isCaptionBerita() && $content->berita)
                                        <div class="card mb-3 border border-[#D8C4B6]/40">
                                            <div class="card-header bg-white border-b border-[#D8C4B6]/40">
                                                <h5 class="card-title mb-0 text-[#1b334e]">
                                                    <i class="fas fa-newspaper"></i> Referensi Berita
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $content->berita->title ?? $content->berita->judul }}</h6>
                                                <a href="{{ route(($routePrefix ?? 'koordinator-humas.view').'.news.show', $content->berita->id) }}" 
                                                   target="_blank" 
                                                    class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fas fa-eye"></i> Lihat Berita Lengkap
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Design Reference -->
                                    @if($content->isCaptionDesain() && $content->desain)
                                        <div class="card mb-3 border border-[#D8C4B6]/40">
                                            <div class="card-header bg-white border-b border-[#D8C4B6]/40">
                                                <h5 class="card-title mb-0 text-[#1b334e]">
                                                    <i class="fas fa-palette"></i> Referensi Desain
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $content->desain->judul }}</h6>
                                                <a href="{{ route(($routePrefix ?? 'koordinator-humas.view').'.designs.show', $content->desain) }}" 
                                                   target="_blank" 
                                                    class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fas fa-eye"></i> Lihat Desain Lengkap
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Caption Content -->
                                    <div class="card border border-[#D8C4B6]/40">
                                        <div class="card-header bg-white border-b border-[#D8C4B6]/40">
                                            <h5 class="card-title mb-0 text-[#1b334e]">
                                                <i class="fas fa-quote-left"></i> Caption
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="caption-content">
                                                {!! nl2br(e($content->caption ?? '')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Information Card -->
                            <div class="card border border-[#D8C4B6]/40">
                                <div class="card-header bg-white border-b border-[#D8C4B6]/40">
                                    <h5 class="card-title mb-0 text-[#1b334e]">
                                        <i class="fas fa-info-circle"></i> Informasi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Pembuat:</strong></td>
                                            <td>{{ $content->creator->name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Dibuat:</strong></td>
                                            <td>{{ $content->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Terakhir Diupdate:</strong></td>
                                            <td>{{ $content->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        
                                        @if($content->isCaptionBerita() && $content->berita)
                                            <tr>
                                                <td><strong>Referensi:</strong></td>
                                                <td>
                                                    <span class="badge badge-info">Berita</span>
                                                    {{ $content->berita->title ?? $content->berita->judul }}
                                                </td>
                                            </tr>
                                        @elseif($content->isCaptionDesain() && $content->desain)
                                            <tr>
                                                <td><strong>Referensi:</strong></td>
                                                <td>
                                                    <span class="badge badge-primary">Desain</span>
                                                    {{ $content->desain->judul }}
                                                </td>
                                            </tr>
                                        @endif
                                        
                                        @if($content->published_at)
                                            <tr>
                                                <td><strong>Tanggal Publikasi:</strong></td>
                                                <td>{{ $content->published_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.caption-content {
    font-size: 1.1em;
    line-height: 1.6;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #1b334e;
}
</style>
@endpush
@endsection


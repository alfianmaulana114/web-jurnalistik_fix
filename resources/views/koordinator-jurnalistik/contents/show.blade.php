@extends('layouts.koordinator-jurnalistik')

@section('title', 'Detail Caption')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Caption</h3>
                    <div class="card-tools">
                        <a href="{{ route('koordinator-jurnalistik.contents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('koordinator-jurnalistik.contents.edit', $content) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ $content->judul }}</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Caption Type and Status -->
                                    <div class="mb-3">
                                        <span class="badge badge-info mr-2">
                                            {{ App\Models\Content::getCaptionTypes()[$content->jenis_konten] ?? $content->jenis_konten }}
                                        </span>
                                        <span class="badge {{ $content->getStatusColorClass() }}">
                                            {{ $content->getStatusLabel() }}
                                        </span>
                                    </div>

                                    <!-- News Reference (for News Captions) -->
                                    @if($content->isCaptionBerita() && $content->berita)
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-newspaper"></i> Referensi Berita
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $content->berita->judul }}</h6>
                                                <p class="text-muted">{{ Str::limit($content->berita->isi, 150) }}</p>
                                                <a href="{{ route('koordinator-jurnalistik.beritas.show', $content->berita) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Lihat Berita Lengkap
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Design Reference (for Design Captions) -->
                                    @if($content->isCaptionDesain() && $content->desain)
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-palette"></i> Referensi Desain
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $content->desain->judul }}</h6>
                                                @if($content->desain->deskripsi)
                                                    <p class="text-muted">{{ Str::limit($content->desain->deskripsi, 150) }}</p>
                                                @endif
                                                @if($content->desain->file_path)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/' . $content->desain->file_path) }}" 
                                                             alt="Desain Referensi" 
                                                             class="img-fluid rounded" 
                                                             style="max-height: 200px;">
                                                    </div>
                                                @endif
                                                <a href="{{ route('koordinator-jurnalistik.designs.show', $content->desain) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fas fa-eye"></i> Lihat Desain Lengkap
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Caption Content -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-quote-left"></i> Caption
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="caption-content">
                                                {!! nl2br(e($content->caption)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Information Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
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
                                        
                                        <!-- Reference Information -->
                                        @if($content->isCaptionBerita() && $content->berita)
                                            <tr>
                                                <td><strong>Referensi:</strong></td>
                                                <td>
                                                    <span class="badge badge-info">Berita</span>
                                                    {{ $content->berita->judul }}
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
                                        
                                        @if($content->reviewer)
                                            <tr>
                                                <td><strong>Reviewer:</strong></td>
                                                <td>{{ $content->reviewer->name }}</td>
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





                            <!-- Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-cogs"></i> Aksi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($content->status === 'draft')
                                            <button type="button" class="btn btn-info btn-sm" onclick="updateStatus('review')">
                                                <i class="fas fa-eye"></i> Kirim untuk Review
                                            </button>
                                        @endif

                                        @if($content->status === 'review' && auth()->user()->hasRole('koordinator-jurnalistik'))
                                            <button type="button" class="btn btn-success btn-sm" onclick="updateStatus('published')">
                                                <i class="fas fa-check"></i> Publikasikan
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" onclick="updateStatus('draft')">
                                                <i class="fas fa-edit"></i> Kembalikan ke Draft
                                            </button>
                                        @endif

                                        @if($content->status === 'published')
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="updateStatus('archived')">
                                                <i class="fas fa-archive"></i> Arsipkan
                                            </button>
                                        @endif

                                        <a href="{{ route('koordinator-jurnalistik.contents.edit', $content) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit Caption
                                        </a>

                                        <form action="{{ route('koordinator-jurnalistik.contents.destroy', $content) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus caption ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Hapus Caption
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(newStatus) {
    if (confirm('Yakin ingin mengubah status caption ini?')) {
        fetch(`{{ route('koordinator-jurnalistik.contents.update-status', $content) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.caption-content {
    font-size: 1.1em;
    line-height: 1.6;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}
</style>
@endpush
@endsection
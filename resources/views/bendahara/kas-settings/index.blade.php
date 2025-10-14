@extends('layouts.app')

@section('title', 'Pengaturan Kas Anggota')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs mr-2"></i>
                        Pengaturan Kas Anggota
                    </h3>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('bendahara.kas-settings.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="jumlah_kas_anggota" class="form-label">
                                        <i class="fas fa-money-bill-wave mr-2"></i>
                                        Jumlah Kas Anggota (Rp)
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('jumlah_kas_anggota') is-invalid @enderror" 
                                               id="jumlah_kas_anggota" 
                                               name="jumlah_kas_anggota" 
                                               value="{{ old('jumlah_kas_anggota', $kasSettings['jumlah_kas_anggota']->value ?? 15000) }}"
                                               min="1000" 
                                               max="100000" 
                                               step="1000"
                                               required>
                                    </div>
                                    @error('jumlah_kas_anggota')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Jumlah kas yang harus dibayar oleh setiap anggota per periode. Minimal Rp 1.000, maksimal Rp 100.000.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Pengaturan
                                    </button>
                                    <a href="{{ route('bendahara.dashboard') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Kembali ke Dashboard
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informasi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <strong>Pengaturan Saat Ini:</strong>
                                    </p>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-money-bill-wave text-success mr-2"></i>
                                            Jumlah Kas: <strong>Rp {{ number_format($kasSettings['jumlah_kas_anggota']->value ?? 15000, 0, ',', '.') }}</strong>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-calendar text-info mr-2"></i>
                                            Terakhir Diubah: 
                                            <small class="text-muted">
                                                {{ isset($kasSettings['jumlah_kas_anggota']) ? $kasSettings['jumlah_kas_anggota']->updated_at->format('d/m/Y H:i') : 'Belum pernah diubah' }}
                                            </small>
                                        </li>
                                    </ul>
                                    
                                    <hr>
                                    
                                    <p class="mb-2">
                                        <strong>Catatan:</strong>
                                    </p>
                                    <ul class="small text-muted">
                                        <li>Perubahan pengaturan akan berlaku untuk kas anggota yang baru dibuat</li>
                                        <li>Kas anggota yang sudah ada tidak akan terpengaruh</li>
                                        <li>Pastikan jumlah kas sesuai dengan kemampuan anggota</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format number input
    $('#jumlah_kas_anggota').on('input', function() {
        let value = $(this).val();
        if (value) {
            // Remove any non-digit characters except for the decimal point
            value = value.replace(/[^\d]/g, '');
            $(this).val(value);
        }
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
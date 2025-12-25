@extends($layout ?? 'layouts.bendahara')

@section('title', 'Detail Brief Humas')
@section('header', 'Detail Brief Humas (Read-Only)')

@section('content')
<div class="container-fluid">
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
                <div class="card-header bg-white border-b border-[#D8C4B6]/40 d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-[#1b334e]">Detail Brief Humas</h3>
                    <div class="card-tools">
                        <a href="{{ route(($routePrefix ?? 'bendahara.view').'.brief-humas.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Judul</dt>
                        <dd class="col-sm-9">{{ $briefHumas->judul }}</dd>

                        <dt class="col-sm-3">Link Drive</dt>
                        <dd class="col-sm-9">
                            @if($briefHumas->link_drive)
                            <a href="{{ $briefHumas->link_drive }}" target="_blank" class="text-blue-600 hover:underline">{{ $briefHumas->link_drive }}</a>
                            @else
                            <span class="text-gray-500">-</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Catatan</dt>
                        <dd class="col-sm-9">{{ $briefHumas->catatan ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
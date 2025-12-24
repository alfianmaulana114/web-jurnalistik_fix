{{-- Dashboard Bendahara
    - Ringkasan keuangan: saldo, pemasukan, pengeluaran
    - Akses cepat ke manajemen kas anggota dan laporan
--}}
@extends('layouts.bendahara')

@section('title', 'Dashboard')
@section('header', 'Dashboard Bendahara')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-6 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-[#1b334e] sm:text-3xl">
                    Selamat Datang, <span class="text-[#f9b61a]">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Kelola keuangan UKM Jurnalistik dengan mudah dan efisien
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                    <svg class="h-4 w-4 text-[#f9b61a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-[#f9b61a]/10">
                    <i class="fas fa-coins text-5xl text-[#f9b61a]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Saldo -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Saldo</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kas + Pemasukan - Pengeluaran</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Kas Bulan Ini -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Kas Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($kasAnggotaBulanIni, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kas anggota bulan ini</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-100">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Pemasukan Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tidak termasuk kas</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 text-green-600 transition-colors group-hover:bg-green-100">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Pengeluaran Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengeluaran bulan ini</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-50 text-red-600 transition-colors group-hover:bg-red-100">
                    <i class="fas fa-arrow-down text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <!-- Total Anggota -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="mt-2 text-xl font-bold tracking-tight text-[#1b334e]">{{ $totalAnggota }}</p>
                    <p class="text-xs text-gray-500 mt-1">Anggota aktif</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Kas Lunas -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Kas Lunas</p>
                    <p class="mt-2 text-xl font-bold tracking-tight text-[#1b334e]">{{ $kasStats['lunas'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Anggota yang sudah lunas</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Kas Belum Lunas -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Kas Belum Lunas</p>
                    <p class="mt-2 text-xl font-bold tracking-tight text-[#1b334e]">{{ $kasStats['belum_lunas'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Anggota belum bayar</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-50 text-yellow-600">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Kas Settings Section -->
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Pengaturan Kas Anggota</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Konfigurasi jumlah kas per periode</p>
                </div>
                <a href="{{ route('bendahara.kas-settings.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-3 py-1.5 text-xs font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    Kelola Pengaturan
                </a>
            </div>
        </div>
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Jumlah Kas Anggota Saat Ini</p>
                    <p class="mt-1.5 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000), 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Per periode pembayaran</p>
                </div>
                <div class="text-right">
                    <button onclick="showKasSettingModal()" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                        <i class="fas fa-cog"></i>Ubah Jumlah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kas Anggota Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Statistik Kas Anggota -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Statistik Kas Anggota</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Ringkasan status pembayaran kas</p>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $kasStats['lunas'] }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">Lunas</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $kasStats['belum_lunas'] }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">Belum Lunas</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $kasStats['nunggak'] }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">Nunggak</p>
                    </div>
                </div>
                
                <!-- Ringkasan Per Divisi -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-900 mb-2.5">Ringkasan Per Divisi</h4>
                    <div class="space-y-2">
                        @foreach($ringkasanDivisi as $divisi => $data)
                        <div class="flex items-center justify-between rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] mr-2.5">
                                    @switch($divisi)
                                        @case('redaksi')
                                            <i class="fas fa-pen text-sm"></i>
                                            @break
                                        @case('litbang')
                                            <i class="fas fa-search text-sm"></i>
                                            @break
                                        @case('humas')
                                            <i class="fas fa-bullhorn text-sm"></i>
                                            @break
                                        @case('media_kreatif')
                                            <i class="fas fa-palette text-sm"></i>
                                            @break
                                        @case('pengurus')
                                            <i class="fas fa-crown text-sm"></i>
                                            @break
                                    @endswitch
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-[#1b334e] capitalize">{{ str_replace('_', ' ', $divisi) }}</p>
                                    <p class="text-xs text-gray-600">{{ $data['total'] }} anggota</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">Lunas: <span class="font-semibold text-[#1b334e]">{{ $data['lunas'] }}</span></p>
                                <p class="text-xs text-gray-600">Belum: <span class="font-semibold text-[#1b334e]">{{ $data['belum_lunas'] }}</span></p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Anggota Belum Bayar -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Anggota Belum Bayar</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Daftar anggota yang belum membayar kas</p>
                    </div>
                    <a href="{{ route('bendahara.kas-anggota.index', ['status' => 'belum_lunas']) }}" class="text-xs font-medium text-[#1b334e] hover:text-[#f9b61a]">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-2">
                    @forelse($anggotaBelumBayar as $kas)
                    <div class="flex items-center justify-between rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/5 cursor-pointer" 
                         onclick="window.location.href='{{ route('bendahara.kas-anggota.show', $kas->id) }}'">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] mr-2.5">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-[#1b334e]">{{ $kas->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $kas->user->getDivision())) }}</p>
                                <p class="text-xs text-red-600 mt-0.5">Belum bayar dari {{ $kas->belum_bayar_dari ?? $kas->bulan_tahun }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-[#1b334e]">Rp {{ number_format($kas->jumlah_belum_bayar, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $kas->bulan_tahun }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-500">Semua anggota sudah bayar</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Transaksi Terbaru</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Riwayat transaksi keuangan terakhir</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('bendahara.pemasukan.index') }}" class="text-xs font-medium text-[#1b334e] hover:text-[#f9b61a]">
                        Pemasukan
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('bendahara.pengeluaran.index') }}" class="text-xs font-medium text-[#1b334e] hover:text-[#f9b61a]">
                        Pengeluaran
                    </a>
                </div>
            </div>
        </div>
            <div class="p-5">
            <div class="space-y-2">
                @forelse($recentTransactions as $transaction)
                <div class="flex items-start space-x-3 rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md">
                    <div class="p-2 rounded-lg 
                        @if(isset($transaction->type) && $transaction->type === 'kas') bg-blue-50 text-blue-600
                        @elseif($transaction instanceof \App\Models\Pemasukan || (isset($transaction->type) && $transaction->type === 'pemasukan')) bg-[#f9b61a]/10 text-[#f9b61a]
                        @else bg-red-50 text-red-600
                        @endif">
                        <i class="fas 
                            @if(isset($transaction->type) && $transaction->type === 'kas') fa-coins
                            @elseif($transaction instanceof \App\Models\Pemasukan || (isset($transaction->type) && $transaction->type === 'pemasukan')) fa-arrow-up
                            @else fa-arrow-down
                            @endif text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-[#1b334e]">{{ $transaction->deskripsi }}</h4>
                            <span class="text-sm font-semibold 
                                @if(isset($transaction->type) && $transaction->type === 'kas') text-blue-600
                                @elseif($transaction instanceof \App\Models\Pemasukan || (isset($transaction->type) && $transaction->type === 'pemasukan')) text-[#1b334e]
                                @else text-red-600
                                @endif">
                                @if(isset($transaction->type) && $transaction->type === 'kas')
                                    +Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                @else
                                    {{ $transaction instanceof \App\Models\Pemasukan || (isset($transaction->type) && $transaction->type === 'pemasukan') ? '+' : '-' }}Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">{{ $transaction->kategori ?? 'Kas Anggota' }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">
                                @if(isset($transaction->display_date))
                                    {{ $transaction->display_date->format('d M Y H:i') }}
                                @else
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                @endif
                            </p>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                @if(isset($transaction->type) && $transaction->type === 'kas') bg-green-50 text-green-700
                                @elseif($transaction->status === 'verified' || $transaction->status === 'approved' || $transaction->status === 'lunas') bg-green-50 text-green-700
                                @elseif($transaction->status === 'paid' || $transaction->status === 'completed') bg-green-50 text-green-700
                                @else bg-gray-50 text-gray-700 @endif">
                                @if(isset($transaction->type) && $transaction->type === 'kas')
                                    Lunas
                                @else
                                    {{ ucfirst($transaction->status ?? 'verified') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Kas Setting Modal -->
<div id="kasSettingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-[#D8C4B6]/40 w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-[#1b334e]">Ubah Jumlah Kas Anggota</h3>
                <button onclick="hideKasSettingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="kasSettingForm" action="{{ route('bendahara.kas-settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="jumlah_kas_anggota" class="block text-xs font-medium text-gray-700 mb-2">
                        Jumlah Kas Anggota (Rp)
                    </label>
                    <input type="number" 
                           id="jumlah_kas_anggota" 
                           name="jumlah_kas_anggota" 
                           value="{{ \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) }}"
                           min="1000" 
                           max="100000" 
                           step="1000"
                           class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f9b61a] focus:border-transparent"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Minimal Rp 1.000, Maksimal Rp 100.000</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="hideKasSettingModal()"
                            class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg hover:bg-[#f9b61a]/10 transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg hover:bg-[#f9b61a]/10 hover:shadow-md transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Financial Chart
    const ctx = document.getElementById('financialChart').getContext('2d');
    const financialChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Kas Anggota',
                data: {!! json_encode($chartData['kas'] ?? []) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }, {
                label: 'Pemasukan',
                data: {!! json_encode($chartData['pemasukan']) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4
            }, {
                label: 'Pengeluaran',
                data: {!! json_encode($chartData['pengeluaran']) !!},
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Kas Setting Modal Functions
    function showKasSettingModal() {
        document.getElementById('kasSettingModal').classList.remove('hidden');
    }

    function hideKasSettingModal() {
        document.getElementById('kasSettingModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('kasSettingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideKasSettingModal();
        }
    });
</script>
@endpush
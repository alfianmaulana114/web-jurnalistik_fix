{{-- Dashboard Bendahara
    - Ringkasan keuangan: saldo, pemasukan, pengeluaran
    - Akses cepat ke manajemen kas anggota dan laporan
--}}
@extends('layouts.bendahara')

@section('title', 'Dashboard')
@section('header', 'Dashboard Bendahara')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-green-100 mt-1">Kelola keuangan UKM Jurnalistik dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-coins text-6xl text-green-200"></i>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Saldo -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Saldo</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pemasukan Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-arrow-down text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pengeluaran Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Anggota -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalAnggota }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kas Settings Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Pengaturan Kas Anggota</h3>
                <a href="{{ route('bendahara.kas-settings.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    Kelola Pengaturan
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Jumlah Kas Anggota Saat Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000), 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Per periode pembayaran</p>
                </div>
                <div class="text-right">
                    <button onclick="showKasSettingModal()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                        <i class="fas fa-cog mr-2"></i>Ubah Jumlah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kas Anggota Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistik Kas Anggota -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Kas Anggota</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $kasStats['lunas'] }}</p>
                        <p class="text-sm text-gray-600">Lunas</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ $kasStats['belum_lunas'] }}</p>
                        <p class="text-sm text-gray-600">Belum Lunas</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-600">{{ $kasStats['nunggak'] }}</p>
                        <p class="text-sm text-gray-600">Nunggak</p>
                    </div>
                </div>
                
                <!-- Ringkasan Per Divisi -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Ringkasan Per Divisi</h4>
                    <div class="space-y-2">
                        @foreach($ringkasanDivisi as $divisi => $data)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
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
                                    <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $divisi) }}</p>
                                    <p class="text-sm text-gray-600">{{ $data['total'] }} anggota</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-green-600">Lunas: {{ $data['lunas'] }}</p>
                                <p class="text-sm text-red-600">Belum: {{ $data['belum_lunas'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Pending -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Transaksi Pending</h3>
                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                        {{ $pendingTransactions->count() }} transaksi
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($pendingTransactions as $transaction)
                    <div class="flex items-start justify-between p-3 bg-yellow-50 border border-yellow-200 rounded">
                        <div class="flex-1">
                            <div class="flex items-center">
                                @if($transaction instanceof \App\Models\Pemasukan)
                                    <i class="fas fa-arrow-up text-green-600 mr-2"></i>
                                    <p class="font-medium text-gray-900">{{ $transaction->deskripsi }}</p>
                                @else
                                    <i class="fas fa-arrow-down text-red-600 mr-2"></i>
                                    <p class="font-medium text-gray-900">{{ $transaction->deskripsi }}</p>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $transaction->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full ml-3">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                        <p class="text-gray-500">Tidak ada transaksi pending</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Financial Trends -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tren Keuangan Bulanan</h3>
            </div>
            <div class="p-6">
                <canvas id="financialChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Anggota Belum Bayar -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Anggota Belum Bayar Bulan Ini</h3>
                    <a href="{{ route('bendahara.kas-anggota.index', ['status' => 'belum_lunas']) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($anggotaBelumBayar as $kas)
                    <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded cursor-pointer hover:bg-red-100 transition-colors" 
                         onclick="window.location.href='{{ route('bendahara.kas-anggota.show', $kas->id) }}'">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $kas->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $kas->user->getDivision())) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-red-600 font-medium">Rp {{ number_format($kas->jumlah_belum_bayar, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $kas->bulan_tahun }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                        <p class="text-gray-500">Semua anggota sudah bayar bulan ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('bendahara.pemasukan.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Pemasukan
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('bendahara.pengeluaran.index') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Pengeluaran
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg">
                    <div class="p-2 rounded-full {{ $transaction instanceof \App\Models\Pemasukan ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <i class="fas {{ $transaction instanceof \App\Models\Pemasukan ? 'fa-arrow-up' : 'fa-arrow-down' }} text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-gray-900">{{ $transaction->deskripsi }}</h4>
                            <span class="text-lg font-semibold {{ $transaction instanceof \App\Models\Pemasukan ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction instanceof \App\Models\Pemasukan ? '+' : '-' }}Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $transaction->kategori }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status === 'verified' || $transaction->status === 'approved') bg-blue-100 text-blue-800
                                @elseif($transaction->status === 'paid' || $transaction->status === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Kas Setting Modal -->
<div id="kasSettingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Ubah Jumlah Kas Anggota</h3>
                <button onclick="hideKasSettingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="kasSettingForm" action="{{ route('bendahara.kas-settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="jumlah_kas_anggota" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Kas Anggota (Rp)
                    </label>
                    <input type="number" 
                           id="jumlah_kas_anggota" 
                           name="jumlah_kas_anggota" 
                           value="{{ \App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) }}"
                           min="1000" 
                           max="100000" 
                           step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Minimal Rp 1.000, Maksimal Rp 100.000</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="hideKasSettingModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
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
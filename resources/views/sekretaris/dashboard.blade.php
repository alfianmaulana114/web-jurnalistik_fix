{{-- Dashboard Sekretaris
    - Ringkasan administrasi: notulensi, proker, absen
    - Navigasi cepat untuk operasional sekretariat
--}}
@extends('layouts.sekretaris')

@section('title', 'Dashboard')
@section('header', 'Dashboard Sekretaris')

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
                    Kelola administrasi UKM Jurnalistik dengan mudah dan efisien
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
                    <i class="fas fa-clipboard text-5xl text-[#f9b61a]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_users'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total News -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_news'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Prokers -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Proker</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_prokers'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Notulensi -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Notulensi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_notulensi'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-file-alt text-xl"></i>
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
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($finance['totalSaldo'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kas + Pemasukan - Pengeluaran (keseluruhan)</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Pemasukan -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-green-600">Rp {{ number_format($finance['totalPemasukan'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-arrow-down text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Pengeluaran -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-red-600">Rp {{ number_format($finance['totalPengeluaran'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Finance Chart: Pemasukan & Pengeluaran -->
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div>
                <h3 class="text-base font-semibold text-[#1b334e]">Grafik Pemasukan & Pengeluaran</h3>
                <p class="mt-0.5 text-xs text-gray-600">Pemasukan (kas + pemasukan) dibanding pengeluaran</p>
            </div>
        </div>
        <div class="p-5">
            <div class="h-64">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        const financialChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($financeChart['labels'] ?? []) !!},
                datasets: [{
                    label: 'Pemasukan (Kas + Pemasukan)',
                    data: {!! json_encode($financeChart['income_combined'] ?? []) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Pengeluaran',
                    data: {!! json_encode($financeChart['pengeluaran'] ?? []) !!},
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
                    legend: { position: 'top' },
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
    </script>

    <!-- Top Absent Members -->
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Anggota Paling Sering Tidak Hadir</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Top 5 berdasarkan total ketidakhadiran</p>
                </div>
                <a href="{{ route('sekretaris.absen.index') }}" class="text-xs font-medium text-[#1b334e] hover:text-[#f9b61a]">Lihat Lainnya →</a>
            </div>
        </div>
        <div class="p-5">
            <div class="space-y-2">
                @forelse(($topAbsent ?? []) as $item)
                <div class="flex items-center justify-between rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-[#1b334e]">{{ $item['name'] }}</div>
                            <div class="text-xs text-gray-500">NIM {{ $item['nim'] ?? '-' }} • {{ ucfirst(str_replace('_', ' ', $item['divisi'] ?? '-')) }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-red-600">{{ $item['total'] }} kali</div>
                        <div class="text-[11px] text-gray-500">Tidak hadir</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-gray-500">Tidak ada data ketidakhadiran</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent News -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Berita Terbaru</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Berita terbaru yang dipublikasikan</p>
                </div>
            </div>
            <div class="p-5">
                @forelse($recentActivities['news'] as $news)
                <div class="mb-3 pb-3 border-b border-[#D8C4B6]/40 last:border-0 last:pb-0">
                    <h4 class="text-sm font-medium text-[#1b334e]">{{ $news->title }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">Tidak ada berita</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Prokers -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Program Kerja Terbaru</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Program kerja yang baru ditambahkan</p>
                </div>
            </div>
            <div class="p-5">
                @forelse($recentActivities['prokers'] as $proker)
                <div class="mb-3 pb-3 border-b border-[#D8C4B6]/40 last:border-0 last:pb-0">
                    <h4 class="text-sm font-medium text-[#1b334e]">{{ $proker->nama_proker }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ $proker->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">Tidak ada proker</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


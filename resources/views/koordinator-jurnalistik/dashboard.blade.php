{{-- Dashboard Koordinator Jurnalistik
    - Ikhtisar aktivitas redaksi, konten, desain, funfact, proker
    - Gunakan komponen untuk kartu statistik dan list
--}}
@extends('layouts.koordinator-jurnalistik')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Jurnalistik')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-[#1b334e] to-[#16283e] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-white/70 mt-1">Kelola aktivitas UKM Jurnalistik dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-newspaper text-6xl text-white/40"></i>
            </div>
        </div>
    </div>

    <!-- General Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total News -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#1b334e]/10 text-[#1b334e]">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalNews }}</p>
                </div>
            </div>
        </div>


        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#1b334e]/10 text-[#1b334e]">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#1b334e]/10 text-[#1b334e]">
                    <i class="fas fa-eye text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalViews) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Status Kas Anggota</h3>
            </div>
            <div class="p-6">
                @php
                    $kasBelumBayar = \App\Models\KasAnggota::where('status_pembayaran', \App\Models\KasAnggota::STATUS_BELUM_BAYAR)->count();
                    $kasSebagian = \App\Models\KasAnggota::where('status_pembayaran', \App\Models\KasAnggota::STATUS_SEBAGIAN)->count();
                    $kasLunas = \App\Models\KasAnggota::where('status_pembayaran', \App\Models\KasAnggota::STATUS_LUNAS)->count();
                    $kasTerlambat = \App\Models\KasAnggota::where('status_pembayaran', \App\Models\KasAnggota::STATUS_TERLAMBAT)->count();
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $kasBelumBayar }}</p>
                        <p class="text-sm text-gray-600">Belum Bayar</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $kasSebagian }}</p>
                        <p class="text-sm text-gray-600">Sebagian</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $kasLunas }}</p>
                        <p class="text-sm text-gray-600">Lunas</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $kasTerlambat }}</p>
                        <p class="text-sm text-gray-600">Terlambat</p>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="button" id="showUnpaidKasBtn" class="px-4 py-2 bg-[#1b334e] text-white rounded hover:bg-[#16283e]">Lihat yang Belum Bayar</button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Ringkasan Keuangan</h3>
            </div>
            <div class="p-6">
                @php
                    $totalPemasukan = \App\Models\Pemasukan::verified()->sum('jumlah');
                    $totalPengeluaran = \App\Models\Pengeluaran::paid()->sum('jumlah');
                    $saldo = $totalPemasukan - $totalPengeluaran;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Pemasukan Terverifikasi</p>
                        <p class="text-2xl font-bold text-[#1b334e]">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Pengeluaran Dibayar</p>
                        <p class="text-2xl font-bold text-[#1b334e]">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Saldo</p>
                        <p class="text-2xl font-bold text-[#1b334e]">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Division Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Division Overview -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Per Divisi</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($divisionStats as $division => $stats)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                                @switch($division)
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
                                <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $division) }}</p>
                                <p class="text-sm text-gray-600">{{ $stats['coordinators'] }} Koordinator, {{ $stats['members'] }} Anggota</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Konten: {{ $stats['content'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Brief: {{ $stats['briefs'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Desain: {{ $stats['designs'] ?? 0 }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Proker Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Program Kerja</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $prokerStats['total'] }}</p>
                        <p class="text-sm text-gray-600">Total Proker</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $prokerStats['active'] }}</p>
                        <p class="text-sm text-gray-600">Sedang Berjalan</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#1b334e]">{{ $prokerStats['completed'] }}</p>
                        <p class="text-sm text-gray-600">Selesai</p>
                    </div>
                </div>
                
                <!-- Recent Prokers -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Proker Terbaru</h4>
                    <div class="space-y-2">
                        @forelse($recentProkers as $proker)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-medium text-gray-900">{{ $proker->nama_proker }}</p>
                                <p class="text-sm text-gray-600">{{ $proker->tanggal_mulai->format('d M Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($proker->status === 'planning') bg-yellow-100 text-yellow-800
                                @elseif($proker->status === 'ongoing') bg-blue-100 text-blue-800
                                @elseif($proker->status === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($proker->status) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Belum ada proker</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Trends -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tren Bulanan</h3>
            </div>
            <div class="p-6">
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Urgent Briefs -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Brief Mendesak</h3>
                    <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="text-[#1b334e] hover:text-[#16283e] text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($urgentBriefs as $brief)
                    <div class="flex items-start justify-between p-3 bg-red-50 border border-red-200 rounded">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $brief->judul }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($brief->deskripsi, 60) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Deadline: {{ $brief->deadline ? $brief->deadline->format('d M Y') : 'Tidak ada deadline' }}
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full ml-3">
                            {{ ucfirst($brief->prioritas) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                        <p class="text-gray-500">Tidak ada brief mendesak</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent News and Comments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent News -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Berita Terbaru</h3>
                    <a href="{{ route('koordinator-jurnalistik.news.index') }}" class="text-[#1b334e] hover:text-[#16283e] text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentNews as $news)
                    <div class="flex items-start space-x-3">
                        @if($news->image)
                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-16 h-16 object-cover rounded">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 line-clamp-2">{{ $news->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $news->views }} views
                                
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada berita</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
    <!-- Modal: Unpaid Kas -->
    <div id="unpaidKasModal" class="hidden fixed inset-0 z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/40" onclick="document.getElementById('unpaidKasModal').classList.add('hidden')"></div>
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full relative z-10">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Kas Belum Bayar</h3>
                    <button class="text-gray-600" onclick="document.getElementById('unpaidKasModal').classList.add('hidden')"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6">
                    @php
                        $unpaidKas = \App\Models\KasAnggota::where('status_pembayaran', \App\Models\KasAnggota::STATUS_BELUM_BAYAR)->with('user')->orderBy('tahun', 'desc')->get();
                    @endphp
                    @if($unpaidKas->count() === 0)
                        <p class="text-gray-500">Semua anggota sudah membayar.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terbayar</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($unpaidKas as $kas)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $kas->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($kas->periode) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $kas->tahun }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($kas->jumlah_terbayar, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button class="px-4 py-2 bg-[#1b334e] text-white rounded hover:bg-[#16283e]" onclick="document.getElementById('unpaidKasModal').classList.add('hidden')">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Monthly Chart
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Berita',
                data: {!! json_encode($newsData) !!},
                borderColor: '#1b334e',
                backgroundColor: 'rgba(27, 51, 78, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    document.getElementById('showUnpaidKasBtn')?.addEventListener('click', function() {
        document.getElementById('unpaidKasModal').classList.remove('hidden');
    });
</script>
@endpush
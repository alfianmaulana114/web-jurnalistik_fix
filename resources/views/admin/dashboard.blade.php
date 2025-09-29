@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
<!-- Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Berita -->
    <div class="bg-white rounded-lg shadow-sm p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                <i class="fas fa-newspaper text-blue-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm font-medium">Total Berita</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $newsCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Komentar -->
    <div class="bg-white rounded-lg shadow-sm p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                <i class="fas fa-comments text-green-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm font-medium">Total Komentar</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $commentCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Total User -->
    <div class="bg-white rounded-lg shadow-sm p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                <i class="fas fa-users text-purple-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm font-medium">Total User</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $userCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Views -->
    <div class="bg-white rounded-lg shadow-sm p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                <i class="fas fa-eye text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm font-medium">Total Views</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalViews }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Grafik dan Tabel -->
<div class="grid grid-cols-1 gap-6 mb-6">
    <!-- Grafik Statistik -->
    <div class="bg-white rounded-lg shadow-sm p-6 transition-all duration-300 hover:shadow-md w-full">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistik Berita & Komentar</h2>
        <div class="h-96">
            <canvas id="statsChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statsCtx = document.getElementById('statsChart').getContext('2d');
        new Chart(statsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Berita',
                    data: {!! json_encode($newsData) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Komentar',
                    data: {!! json_encode($commentData) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1f2937',
                        bodyColor: '#4b5563',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        bodyFont: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        titleFont: {
                            size: 14,
                            family: "'Inter', sans-serif",
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: "'Inter', sans-serif"
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush


<!-- Recent News -->
<div class="bg-white rounded-lg shadow-sm mb-6 transition-all duration-300 hover:shadow-md">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Berita Terbaru</h2>
    </div>
    <div class="p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentNews as $news)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $news->title }}</div>
                        </td>
                       
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $news->views }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $news->created_at->format('d M Y') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.news.index') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">Lihat semua berita</a>
        </div>
    </div>
</div>

<!-- Recent Comments -->
<div class="bg-white rounded-lg shadow-sm transition-all duration-300 hover:shadow-md">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Komentar Terbaru</h2>
    </div>
    <div class="p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berita</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentComments as $comment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $comment->name }}</div>
                            <div class="text-sm text-gray-500">{{ $comment->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $comment->news->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ Str::limit($comment->content, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $comment->created_at->format('d M Y') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.comments.index') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">Lihat semua komentar</a>
        </div>
    </div>
</div>
@endsection
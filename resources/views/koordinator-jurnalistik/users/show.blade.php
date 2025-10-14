@extends('layouts.koordinator-jurnalistik')

@section('title', 'Detail User - ' . $user->name)
@section('header', 'Detail User')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <div class="flex items-center space-x-4 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if(str_contains($user->role, 'koordinator'))
                                    bg-purple-100 text-purple-800
                                @elseif(str_contains($user->role, 'anggota'))
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-green-100 text-green-800
                                @endif
                            ">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $user->email }}</span>
                            <span class="text-sm text-gray-500">NIM: {{ $user->nim }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('koordinator-jurnalistik.users.edit', $user) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus
                    </button>
                    <a href="{{ route('koordinator-jurnalistik.users.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIM</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->nim }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $user->email }}" class="text-red-600 hover:text-red-500">{{ $user->email }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-red-600 hover:text-red-500">{{ $user->phone }}</a>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Angkatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->angkatan ?? 'Tidak diketahui' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bergabung</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                    </dl>
                    
                    @if($user->bio)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Bio/Deskripsi</dt>
                        <dd class="mt-2 text-sm text-gray-900 bg-gray-50 rounded-lg p-4">{{ $user->bio }}</dd>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Activity Statistics -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik Aktivitas</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $user->contents_count ?? 0 }}</div>
                            <div class="text-sm text-blue-600">Konten</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $user->briefs_count ?? 0 }}</div>
                            <div class="text-sm text-green-600">Brief</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $user->designs_count ?? 0 }}</div>
                            <div class="text-sm text-purple-600">Desain</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aktivitas Terbaru</h3>
                </div>
                <div class="px-6 py-4">
                    @if(isset($recentActivities) && $recentActivities->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($recentActivities as $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full 
                                                    @if($activity->type === 'content') bg-blue-500
                                                    @elseif($activity->type === 'brief') bg-green-500
                                                    @elseif($activity->type === 'design') bg-purple-500
                                                    @else bg-gray-500
                                                    @endif
                                                    flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas 
                                                        @if($activity->type === 'content') fa-file-alt
                                                        @elseif($activity->type === 'brief') fa-clipboard-list
                                                        @elseif($activity->type === 'design') fa-palette
                                                        @else fa-circle
                                                        @endif
                                                        text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Cepat</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->email_verified_at)
                                bg-green-100 text-green-800
                            @else
                                bg-red-100 text-red-800
                            @endif
                        ">
                            @if($user->email_verified_at)
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            @else
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Belum Verifikasi
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Role</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Divisi</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if(str_contains($user->role, 'redaksi'))
                                Redaksi
                            @elseif(str_contains($user->role, 'litbang'))
                                Litbang
                            @elseif(str_contains($user->role, 'humas'))
                                Humas
                            @elseif(str_contains($user->role, 'media_kreatif'))
                                Media Kreatif
                            @elseif(str_contains($user->role, 'jurnalistik'))
                                Jurnalistik
                            @else
                                Pengurus
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Bergabung</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Terakhir Update</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <a href="mailto:{{ $user->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-envelope mr-2"></i>
                        Kirim Email
                    </a>
                    
                    @if($user->phone)
                    <a href="tel:{{ $user->phone }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-phone mr-2"></i>
                        Telepon
                    </a>
                    @endif
                    
                    <button type="button" onclick="resetPassword()" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-key mr-2"></i>
                        Reset Password
                    </button>
                    
                    @if(!$user->email_verified_at)
                    <button type="button" onclick="resendVerification()" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Ulang Verifikasi
                    </button>
                    @endif
                </div>
            </div>

            <!-- Related Content -->
            @if(isset($user->contents) && $user->contents->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Konten Terbaru</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        @foreach($user->contents->take(3) as $content)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                    @if($content->status === 'published') bg-green-100 text-green-800
                                    @elseif($content->status === 'review') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ ucfirst($content->status) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $content->title }}</p>
                                <p class="text-xs text-gray-500">{{ $content->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($user->contents->count() > 3)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('koordinator-jurnalistik.contents.index', ['creator' => $user->id]) }}" class="text-sm text-red-600 hover:text-red-500">
                            Lihat semua konten ({{ $user->contents->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus User</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>? 
                    Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form action="{{ route('koordinator-jurnalistik.users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Hapus
                    </button>
                </form>
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function resetPassword() {
    if (confirm('Apakah Anda yakin ingin mereset password user ini? Link reset akan dikirim ke email user.')) {
        // Here you would typically make an AJAX request to reset password
        fetch(`{{ route('koordinator-jurnalistik.users.reset-password', $user) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Link reset password telah dikirim ke email user.');
            } else {
                alert('Gagal mengirim link reset password.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim link reset password.');
        });
    }
}

function resendVerification() {
    if (confirm('Apakah Anda yakin ingin mengirim ulang email verifikasi?')) {
        // Here you would typically make an AJAX request to resend verification
        fetch(`{{ route('koordinator-jurnalistik.users.resend-verification', $user) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Email verifikasi telah dikirim ulang.');
            } else {
                alert('Gagal mengirim email verifikasi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim email verifikasi.');
        });
    }
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
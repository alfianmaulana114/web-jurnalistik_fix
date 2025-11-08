@extends('layouts.sekretaris')

@section('title', 'Absen Anggota')
@section('header', 'Absen Anggota')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Absen Anggota</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola absensi anggota UKM Jurnalistik</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="showBulkInputModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Input Absen Bulk
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('sekretaris.absen.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="notulensi_id" class="block text-sm font-medium text-gray-700">Rapat</label>
                <select name="notulensi_id" id="notulensi_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Pilih rapat</option>
                    @foreach($meetings as $m)
                        <option value="{{ $m->id }}" {{ (string)request('notulensi_id') === (string)$m->id ? 'selected' : '' }}>
                            {{ $m->judul }} - {{ $m->tanggal->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama anggota..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Absen Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if(!$notulensi_id)
        <div class="px-6 pt-6">
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 9V5h2v4H9zm0 2h2v4H9v-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Pilih rapat untuk melihat status kehadiran</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Tabel tetap ditampilkan agar semua anggota terlihat. Status akan terisi setelah memilih rapat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($users->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                @php
                    $absen = $absenData->get($user->id);
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->nim }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $user->getDivision())) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($absen)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absen->getStatusBadgeClass() }}">
                                {{ $absen->getStatusLabel() }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Belum Input
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $absen->keterangan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($absen)
                            <button onclick="editAbsen({{ $absen->id }}, '{{ $absen->status }}', '{{ $absen->keterangan ?? '' }}')" class="text-blue-600 hover:text-blue-900 mr-3" title="Edit Absen">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('sekretaris.absen.destroy', $absen) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus absen ini?')" title="Hapus Absen">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-6 text-center">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Tidak ada anggota untuk ditampilkan</p>
        </div>
        @endif
    </div>
</div>

<!-- Bulk Input Modal -->
<div id="bulkInputModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full relative z-10">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Input Absen Bulk</h3>
            </div>
            <form id="bulkAbsenForm" action="{{ route('sekretaris.absen.store-bulk') }}" method="POST">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bulk_notulensi_id" class="block text-sm font-medium text-gray-700">Rapat</label>
                            <select name="notulensi_id" id="bulk_notulensi_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach($meetings as $m)
                                    <option value="{{ $m->id }}" {{ (string)request('notulensi_id') === (string)$m->id ? 'selected' : '' }}>
                                        {{ $m->judul }} - {{ $m->tanggal->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" required value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                    <div class="max-h-96 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $user->name }}</td>
                                    <td class="px-3 py-2">
                                        <select name="absens[{{ $user->id }}][status]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <option value="">Pilih (Opsional)</option>
                                            @foreach(\App\Models\Absen::getAllStatus() as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" name="absens[{{ $user->id }}][keterangan]" placeholder="Opsional"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <input type="hidden" name="absens[{{ $user->id }}][user_id]" value="{{ $user->id }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">* Hanya anggota yang diisi statusnya yang akan disimpan</p>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="hideBulkInputModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Modal (Simple) -->
<div id="addModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative z-10">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tambah Absen</h3>
            </div>
            <form id="addAbsenForm" action="{{ route('sekretaris.absen.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="add_user_id">
                <input type="hidden" name="notulensi_id" id="add_notulensi_id" value="{{ $notulensi_id }}">
                <input type="hidden" name="tanggal" id="add_tanggal">
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="add_status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="add_status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Status</option>
                            @foreach(\App\Models\Absen::getAllStatus() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="add_keterangan" class="block text-sm font-medium text-gray-700">Keterangan <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" name="keterangan" id="add_keterangan" placeholder="Keterangan (opsional)"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="hideAddModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative z-10">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Absen</h3>
            </div>
            <form id="editAbsenForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="edit_status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach(\App\Models\Absen::getAllStatus() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <input type="text" name="keterangan" id="edit_keterangan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="hideEditModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showBulkInputModal() {
    document.getElementById('bulkInputModal').classList.remove('hidden');
}

function hideBulkInputModal() {
    document.getElementById('bulkInputModal').classList.add('hidden');
}

function editAbsen(id, status, keterangan) {
    document.getElementById('editAbsenForm').action = `/sekretaris/absen/${id}`;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_keterangan').value = keterangan || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function hideEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function addAbsen(userId) {
    const notulensiId = document.getElementById('notulensi_id').value;
    if (!notulensiId) {
        alert('Pilih rapat terlebih dahulu!');
        return;
    }
    
    // Ambil tanggal dari notulensi yang dipilih
    const meetings = @json($meetings);
    const selectedMeeting = meetings.find(m => m.id == notulensiId);
    const tanggal = selectedMeeting ? selectedMeeting.tanggal : '{{ date('Y-m-d') }}';
    
    document.getElementById('add_user_id').value = userId;
    document.getElementById('add_notulensi_id').value = notulensiId;
    document.getElementById('add_tanggal').value = tanggal;
    document.getElementById('add_status').value = '';
    document.getElementById('add_keterangan').value = '';
    document.getElementById('addModal').classList.remove('hidden');
}

function hideAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

// Double click protection untuk form add
document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addAbsenForm');
    const addSubmitBtn = document.getElementById('submitBtn');
    
    if (addForm && addSubmitBtn) {
        let isSubmitting = false;
        
        addForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            
            isSubmitting = true;
            addSubmitBtn.disabled = true;
            addSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        });
    }
});
</script>
@endpush
@endsection


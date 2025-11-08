@extends('layouts.koordinator-redaksi')

@section('title', 'Penjadwalan')
@section('header', 'Penjadwalan Anggota Redaksi')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Filter dan Tombol Tambah -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Penjadwalan {{ $bulanLabel }} {{ $tahun }}</h2>
                
                <!-- Filter Bulan dan Tahun -->
                <form method="GET" action="{{ route('koordinator-redaksi.penjadwalan.index') }}" class="flex items-center gap-2">
                    <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$i-1] }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </form>
            </div>
            
            <button onclick="showCreateModal()" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Jadwal
            </button>
        </div>
    </div>

    <!-- Kalender -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div id="calendar" class="w-full"></div>
    </div>

    <!-- Legenda -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Legenda</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-yellow-400 rounded"></div>
                <span class="text-sm text-gray-700">Pending</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-400 rounded"></div>
                <span class="text-sm text-gray-700">Selesai</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-400 rounded"></div>
                <span class="text-sm text-gray-700">Dibatalkan</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create/Edit -->
<div id="penjadwalanModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideModal()"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative z-10">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Tambah Jadwal</h3>
            </div>
            <form id="penjadwalanForm" method="POST" action="{{ route('koordinator-redaksi.penjadwalan.store') }}">
                @csrf
                <div id="methodField"></div>
                <div class="px-6 py-4 space-y-4">
                    
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Anggota Redaksi <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" id="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent {{ $errors->has('user_id') ? 'border-red-500' : '' }}">
                            <option value="">Pilih Anggota</option>
                            @foreach($anggotaRedaksi as $anggota)
                                <option value="{{ $anggota->id }}" {{ old('user_id') == $anggota->id ? 'selected' : '' }}>{{ $anggota->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent {{ $errors->has('tanggal') ? 'border-red-500' : '' }}">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="hideModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700" data-submit-button>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-daygrid-event {
        white-space: normal;
        font-size: 0.85rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($events),
        eventClick: function(info) {
            const event = info.event;
            // Tampilkan opsi edit atau delete
            if (confirm('Pilih aksi:\nOK = Edit\nCancel = Hapus')) {
                editPenjadwalan(event.id, {
                    user_id: event.extendedProps.user_id,
                    tanggal: event.startStr,
                    keterangan: event.extendedProps.keterangan || '',
                    status: event.extendedProps.status
                });
            } else {
                deletePenjadwalan(event.id);
            }
        },
        dateClick: function(info) {
            // Ketika klik tanggal kosong, buka modal create dengan tanggal terisi
            showCreateModal(info.dateStr);
        },
        eventDidMount: function(info) {
            // Warna berdasarkan status
            const status = info.event.extendedProps.status;
            if (status === 'completed') {
                info.el.style.backgroundColor = '#10b981';
                info.el.style.borderColor = '#10b981';
            } else if (status === 'cancelled') {
                info.el.style.backgroundColor = '#ef4444';
                info.el.style.borderColor = '#ef4444';
            } else {
                info.el.style.backgroundColor = '#fbbf24';
                info.el.style.borderColor = '#fbbf24';
            }
        }
    });
    calendar.render();

    // Fungsi untuk menampilkan modal create
    window.showCreateModal = function(date = null) {
        document.getElementById('modalTitle').textContent = 'Tambah Jadwal';
        const form = document.getElementById('penjadwalanForm');
        form.action = '{{ route("koordinator-redaksi.penjadwalan.store") }}';
        form.method = 'POST';
        form.setAttribute('data-submit-allowed', 'true');
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('user_id').value = date ? '' : '{{ old('user_id', '') }}';
        document.getElementById('tanggal').value = date || '{{ old('tanggal', '') }}';
        document.getElementById('keterangan').value = '{{ old('keterangan', '') }}';
        document.getElementById('status').value = '{{ old('status', 'pending') }}';
        document.getElementById('penjadwalanModal').classList.remove('hidden');
        
        // Reset submit button dan flag
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Simpan';
        isSubmitting = false;
    };
    
    // Jika ada error, buka modal otomatis setelah DOM loaded
    @if($errors->any() || session('error'))
        setTimeout(function() {
            showCreateModal();
        }, 100);
    @endif

    // Fungsi untuk menampilkan modal edit
    window.editPenjadwalan = function(id, data) {
        document.getElementById('modalTitle').textContent = 'Edit Jadwal';
        const form = document.getElementById('penjadwalanForm');
        form.action = `/koordinator-redaksi/penjadwalan/${id}`;
        form.method = 'POST';
        form.setAttribute('data-submit-allowed', 'true');
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('user_id').value = data.user_id;
        document.getElementById('tanggal').value = data.tanggal;
        document.getElementById('keterangan').value = data.keterangan;
        document.getElementById('status').value = data.status;
        document.getElementById('penjadwalanModal').classList.remove('hidden');
        
        // Reset submit button dan flag
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Simpan';
        isSubmitting = false;
    };

    // Fungsi untuk menyembunyikan modal
    window.hideModal = function() {
        document.getElementById('penjadwalanModal').classList.add('hidden');
    };

    // Handle form submit dengan double-click protection
    const form = document.getElementById('penjadwalanForm');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;
    
    // Handle form submit event
    form.addEventListener('submit', function(e) {
        // Validasi form sebelum submit
        const userId = document.getElementById('user_id').value;
        const tanggal = document.getElementById('tanggal').value;

        if (!userId || !tanggal) {
            e.preventDefault();
            e.stopPropagation();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Simpan';
            return false;
        }

        // Cegah double submit
        if (isSubmitting) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        // Set flag dan disable button
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        
        // Biarkan form submit secara normal - jangan preventDefault
        // Form akan submit dan redirect jika berhasil
    });

    // Handle delete
    window.deletePenjadwalan = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/koordinator-redaksi/penjadwalan/${id}`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    };
});
</script>
@endpush


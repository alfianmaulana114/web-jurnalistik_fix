@extends('layouts.koordinator-litbang')

@section('title', 'Penjadwalan')
@section('header', 'Penjadwalan Anggota Litbang')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Penjadwalan {{ $bulanLabel }} {{ $tahun }}</h2>
                <form method="GET" action="{{ route('koordinator-litbang.penjadwalan.index') }}" class="flex items-center gap-2">
                    <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$i-1] }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"><i class="fas fa-filter mr-2"></i>Filter</button>
                </form>
            </div>
            <button onclick="showCreateModal()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"><i class="fas fa-plus mr-2"></i>Tambah Jadwal</button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div id="calendar" class="w-full"></div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Legenda</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2"><div class="w-4 h-4 bg-yellow-400 rounded"></div><span class="text-sm text-gray-700">Pending</span></div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 bg-green-400 rounded"></div><span class="text-sm text-gray-700">Selesai</span></div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 bg-red-400 rounded"></div><span class="text-sm text-gray-700">Dibatalkan</span></div>
        </div>
    </div>
</div>

<div id="penjadwalanModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideModal()"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative z-10">
            <div class="px-6 py-4 border-b border-gray-200"><h3 id="modalTitle" class="text-lg font-medium text-gray-900">Tambah Jadwal</h3></div>
            <form id="penjadwalanForm" method="POST" action="{{ route('koordinator-litbang.penjadwalan.store') }}">
                @csrf
                <div id="methodField"></div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Anggota Litbang <span class="text-red-500">*</span></label>
                        <select name="user_id" id="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Pilih Anggota</option>
                            @foreach($anggotaLitbang as $anggota)
                                <option value="{{ $anggota->id }}">{{ $anggota->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="pending">Pending</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="hideModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        events: @json($events),
        eventClick: function(info) {
            const event = info.event;
            if (confirm('Pilih aksi:\nOK = Edit\nCancel = Hapus')) {
                editPenjadwalan(event.id, { user_id: event.extendedProps.user_id, tanggal: event.startStr, keterangan: event.extendedProps.keterangan || '', status: event.extendedProps.status });
            } else {
                deletePenjadwalan(event.id);
            }
        },
        dateClick: function(info) { showCreateModal(info.dateStr); },
        eventDidMount: function(info) {
            const status = info.event.extendedProps.status;
            if (status === 'completed') { info.el.style.backgroundColor = '#10b981'; info.el.style.borderColor = '#10b981'; }
            else if (status === 'cancelled') { info.el.style.backgroundColor = '#ef4444'; info.el.style.borderColor = '#ef4444'; }
            else { info.el.style.backgroundColor = '#fbbf24'; info.el.style.borderColor = '#fbbf24'; }
        }
    });
    calendar.render();

    window.showCreateModal = function(date = null) {
        document.getElementById('modalTitle').textContent = 'Tambah Jadwal';
        const form = document.getElementById('penjadwalanForm');
        form.action = '{{ route("koordinator-litbang.penjadwalan.store") }}';
        form.method = 'POST';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('tanggal').value = date || '';
        document.getElementById('keterangan').value = '';
        document.getElementById('status').value = 'pending';
        document.getElementById('penjadwalanModal').classList.remove('hidden');
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Simpan';
        isSubmitting = false;
    };

    window.editPenjadwalan = function(id, data) {
        document.getElementById('modalTitle').textContent = 'Edit Jadwal';
        const form = document.getElementById('penjadwalanForm');
        form.action = `/koordinator-litbang/penjadwalan/${id}`;
        form.method = 'POST';
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('user_id').value = data.user_id;
        document.getElementById('tanggal').value = data.tanggal;
        document.getElementById('keterangan').value = data.keterangan;
        document.getElementById('status').value = data.status;
        document.getElementById('penjadwalanModal').classList.remove('hidden');
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Simpan';
        isSubmitting = false;
    };

    window.hideModal = function() { document.getElementById('penjadwalanModal').classList.add('hidden'); };

    const form = document.getElementById('penjadwalanForm');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;
    form.addEventListener('submit', function(e) {
        const userId = document.getElementById('user_id').value;
        const tanggal = document.getElementById('tanggal').value;
        if (!userId || !tanggal) { e.preventDefault(); e.stopPropagation(); alert('Mohon lengkapi semua field wajib.'); isSubmitting = false; submitBtn.disabled = false; submitBtn.innerHTML = 'Simpan'; return false; }
        if (isSubmitting) { e.preventDefault(); e.stopPropagation(); return false; }
        isSubmitting = true; submitBtn.disabled = true; submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });

    window.deletePenjadwalan = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/koordinator-litbang/penjadwalan/${id}`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    };
});
</script>
@endpush
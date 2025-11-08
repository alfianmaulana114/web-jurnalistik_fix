@extends('layouts.sekretaris')

@section('title', 'Edit Notulensi')
@section('header', 'Edit Notulensi Rapat')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Edit Notulensi Rapat</h3>

        <form action="{{ route('sekretaris.notulensi.update', $notulensi) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul -->
                <div class="col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Rapat</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $notulensi->judul) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    @error('judul')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        value="{{ old('tanggal', $notulensi->tanggal->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    @error('tanggal')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai"
                        value="{{ old('waktu_mulai', \Carbon\Carbon::parse($notulensi->waktu_mulai)->format('H:i')) }}"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    @error('waktu_mulai')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai"
                        value="{{ old('waktu_selesai', $notulensi->waktu_selesai ? \Carbon\Carbon::parse($notulensi->waktu_selesai)->format('H:i') : '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    @error('waktu_selesai')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat -->
                <div>
                    <label for="tempat" class="block text-sm font-medium text-gray-700 mb-2">Tempat</label>
                    <input type="text" name="tempat" id="tempat" value="{{ old('tempat', $notulensi->tempat) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    @error('tempat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Peserta (pilih via modal) -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Peserta</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="showPesertaModal()"
                            class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                            Pilih Peserta
                        </button>
                        <span id="pesertaCount" class="text-sm text-gray-600">Memuat peserta terpilih...</span>
                    </div>
                    <div id="pesertaSelectedList" class="mt-2 bg-gray-50 rounded-md p-3 text-sm text-gray-700 hidden">
                    </div>
                    <div id="pesertaHiddenInputs"></div>
                    @error('peserta_user_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi Notulensi -->
                <div class="col-span-2">
                    <label for="isi_notulensi" class="block text-sm font-medium text-gray-700 mb-2">Isi Notulensi</label>
                    <textarea name="isi_notulensi" id="isi_notulensi" rows="10" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Isi notulensi rapat...">{{ old('isi_notulensi', $notulensi->isi_notulensi) }}</textarea>
                    @error('isi_notulensi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kesimpulan -->
                <div class="col-span-2">
                    <label for="kesimpulan" class="block text-sm font-medium text-gray-700 mb-2">Kesimpulan</label>
                    <textarea name="kesimpulan" id="kesimpulan" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Kesimpulan rapat...">{{ old('kesimpulan', $notulensi->kesimpulan) }}</textarea>
                    @error('kesimpulan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('sekretaris.notulensi.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showPesertaModal() {
    document.getElementById('pesertaModal').classList.remove('hidden');
}
function hidePesertaModal() {
    document.getElementById('pesertaModal').classList.add('hidden');
}
function updatePesertaSummary() {
    const checkboxes = document.querySelectorAll('input[name="peserta_user_ids[]"]');
    const selected = Array.from(checkboxes).filter(cb => cb.checked);
    const namesWithStatus = [];
    const countEl = document.getElementById('pesertaCount');
    const listEl = document.getElementById('pesertaSelectedList');
    const hiddenContainer = document.getElementById('pesertaHiddenInputs');
    // Rebuild hidden inputs inside the form to submit selected IDs
    hiddenContainer.innerHTML = '';
    // Enable/disable status selects based on checkbox state
    document.querySelectorAll('#pesertaList .peserta-item').forEach(row => {
        const cb = row.querySelector('input[name="peserta_user_ids[]"]');
        const select = row.querySelector('select.status-select');
        if (cb && select) {
            select.disabled = !cb.checked;
        }
    });
    selected.forEach(cb => {
        // Hidden input for user id
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'peserta_user_ids[]';
        input.value = cb.value;
        hiddenContainer.appendChild(input);

        // Hidden input for attendance status per user
        const statusSelect = document.querySelector(`select.status-select[data-user-id="${cb.value}"]`);
        const statusVal = statusSelect ? statusSelect.value : 'hadir';
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = `peserta_kehadiran[${cb.value}]`;
        statusInput.value = statusVal;
        hiddenContainer.appendChild(statusInput);

        namesWithStatus.push(`${cb.dataset.name} — ${formatStatusText(statusVal)}`);
    });
    if (selected.length > 0) {
        countEl.textContent = `${selected.length} peserta terpilih`;
        listEl.innerHTML = namesWithStatus.map(n => `• ${n}`).join('<br>');
        listEl.classList.remove('hidden');
    } else {
        countEl.textContent = 'Tidak ada peserta terpilih';
        listEl.classList.add('hidden');
        listEl.innerHTML = '';
    }
}
function formatStatusText(status) {
    switch (status) {
        case 'hadir': return 'Hadir';
        case 'izin': return 'Izin';
        case 'sakit': return 'Sakit';
        case 'tidak_hadir': return 'Tidak Hadir';
        default: return status;
    }
}
function filterPeserta() {
    const q = document.getElementById('pesertaSearch').value.trim().toLowerCase();
    document.querySelectorAll('#pesertaList .peserta-item').forEach(row => {
        const name = row.dataset.name.toLowerCase();
        row.classList.toggle('hidden', q && !name.includes(q));
    });
}
document.addEventListener('DOMContentLoaded', () => {
    // Preselect based on existing peserta (names separated by newline)
    const existing = @json(array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $notulensi->peserta ?? '')))));
    const existingAttendance = @json($notulensi->absens->mapWithKeys(fn($a) => [$a->user_id => $a->status]));
    document.querySelectorAll('input[name="peserta_user_ids[]"]').forEach(cb => {
        if (existing.includes(cb.dataset.name)) cb.checked = true;
    });
    // Set status selects from existing attendance map
    document.querySelectorAll('select.status-select').forEach(sel => {
        const userId = sel.dataset.userId;
        if (existingAttendance[userId]) {
            sel.value = existingAttendance[userId];
        }
    });
    // Attach change listener to status selects
    document.querySelectorAll('select.status-select').forEach(sel => {
        sel.addEventListener('change', updatePesertaSummary);
    });
    updatePesertaSummary();
    
    // Double click protection
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });
});
</script>
@endpush

<!-- Peserta Modal -->
<div id="pesertaModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hidePesertaModal()"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full relative z-10">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Pilih Peserta Rapat</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="hidePesertaModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <input type="text" id="pesertaSearch" oninput="filterPeserta()" placeholder="Cari nama anggota..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="max-h-96 overflow-y-auto" id="pesertaList">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pilih</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Divisi</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="peserta-item" data-name="{{ $user->name }}" data-user-id="{{ $user->id }}">
                                <td class="px-3 py-2">
                                    <input type="checkbox" name="peserta_user_ids[]" value="{{ $user->id }}" data-name="{{ $user->name }}" onchange="updatePesertaSummary()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-900">{{ $user->name }}</td>
                                <td class="px-3 py-2 text-sm text-gray-600">{{ $user->nim }}</td>
                                <td class="px-3 py-2 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $user->getDivision())) }}</td>
                                <td class="px-3 py-2">
                                    <select data-user-id="{{ $user->id }}" class="status-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="updatePesertaSummary()" disabled>
                                        <option value="hadir">Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="tidak_hadir">Tidak Hadir</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="hidePesertaModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Tutup</button>
                <button type="button" onclick="hidePesertaModal()" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">Selesai</button>
            </div>
        </div>
    </div>
    </div>


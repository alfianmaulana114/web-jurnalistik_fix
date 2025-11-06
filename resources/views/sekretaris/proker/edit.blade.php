@extends('layouts.sekretaris')

@section('title', 'Edit Program Kerja')
@section('header', 'Edit Program Kerja')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Edit Program Kerja</h3>
                <a href="{{ route('sekretaris.proker.show', $proker) }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <form action="{{ route('sekretaris.proker.update', $proker) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Dasar</h4>
                
                <div>
                    <label for="nama_proker" class="block text-sm font-medium text-gray-700">Nama Program Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_proker" id="nama_proker" value="{{ old('nama_proker', $proker->nama_proker) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nama_proker') border-red-300 @enderror" required>
                    @error('nama_proker')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('deskripsi') border-red-300 @enderror" required>{{ old('deskripsi', $proker->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $proker->tanggal_mulai->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_mulai') border-red-300 @enderror" required>
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $proker->tanggal_selesai->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_selesai') border-red-300 @enderror" required>
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-300 @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="planning" {{ old('status', $proker->status) === 'planning' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="ongoing" {{ old('status', $proker->status) === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="completed" {{ old('status', $proker->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ old('status', $proker->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('catatan') border-red-300 @enderror" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $proker->catatan) }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Committee Members -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b pb-2">
                    <h4 class="text-md font-medium text-gray-900">Susunan Panitia</h4>
                    <button type="button" id="addCommittee" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Anggota
                    </button>
                </div>

                <div id="committeeContainer" class="space-y-3">
                    @foreach($proker->panitia as $index => $committee)
                    <div class="committee-item bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-start justify-between mb-3">
                            <h5 class="text-sm font-medium text-gray-900">Anggota Panitia #{{ $index + 1 }}</h5>
                            <button type="button" class="remove-committee text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Anggota <span class="text-red-500">*</span></label>
                                <select name="panitia[{{ $index }}][user_id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $committee->id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input type="text" name="panitia[{{ $index }}][jabatan_panitia]" value="{{ $committee->pivot->jabatan_panitia }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Ketua, Sekretaris, dll">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tugas</label>
                                <input type="text" name="panitia[{{ $index }}][tugas_khusus]" value="{{ $committee->pivot->tugas_khusus }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Deskripsi tugas">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @error('panitia')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('panitia.*')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('sekretaris.proker.show', $proker) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Update Program Kerja
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let committeeIndex = {{ $proker->panitia->count() }};
    const addCommitteeBtn = document.getElementById('addCommittee');
    const committeeContainer = document.getElementById('committeeContainer');
    const users = @json($users);

    // Add committee member
    addCommitteeBtn.addEventListener('click', function() {
        const committeeItem = document.createElement('div');
        committeeItem.className = 'committee-item bg-gray-50 p-4 rounded-lg border';
        committeeItem.innerHTML = `
            <div class="flex items-start justify-between mb-3">
                <h5 class="text-sm font-medium text-gray-900">Anggota Panitia #${committeeIndex + 1}</h5>
                <button type="button" class="remove-committee text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Anggota <span class="text-red-500">*</span></label>
                    <select name="panitia[${committeeIndex}][user_id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Pilih Anggota</option>
                        ${users.map(user => `<option value="${user.id}">${user.name} (${user.role})</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <input type="text" name="panitia[${committeeIndex}][jabatan_panitia]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Ketua, Sekretaris, dll">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tugas</label>
                    <input type="text" name="panitia[${committeeIndex}][tugas_khusus]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Deskripsi tugas">
                </div>
            </div>
        `;
        
        committeeContainer.appendChild(committeeItem);
        committeeIndex++;
        updateCommitteeNumbers();
    });

    // Remove committee member
    committeeContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-committee')) {
            e.target.closest('.committee-item').remove();
            updateCommitteeNumbers();
        }
    });

    // Update committee numbers
    function updateCommitteeNumbers() {
        const items = committeeContainer.querySelectorAll('.committee-item');
        items.forEach((item, index) => {
            const title = item.querySelector('h5');
            title.textContent = `Anggota Panitia #${index + 1}`;
        });
    }

    // Date validation
    const startDateInput = document.getElementById('tanggal_mulai');
    const endDateInput = document.getElementById('tanggal_selesai');

    function validateDates() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDate && endDate && startDate > endDate) {
            endDateInput.setCustomValidity('Tanggal selesai harus setelah tanggal mulai');
        } else {
            endDateInput.setCustomValidity('');
        }
    }

    startDateInput.addEventListener('change', validateDates);
    endDateInput.addEventListener('change', validateDates);
});
</script>
@endpush
@endsection

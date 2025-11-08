@extends('layouts.koordinator-jurnalistik')

@section('title', 'Edit User - ' . $user->name)
@section('header', 'Edit User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Edit User: {{ $user->name }}</h3>
                <a href="{{ route('koordinator-jurnalistik.users.show', $user) }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <form action="{{ route('koordinator-jurnalistik.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Dasar</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('name') border-red-300 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM <span class="text-red-500">*</span></label>
                        <input type="number" name="nim" id="nim" value="{{ old('nim', $user->nim) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('nim') border-red-300 @enderror" required>
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('email') border-red-300 @enderror" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($user->email !== old('email', $user->email))
                    <p class="mt-1 text-xs text-yellow-600">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Mengubah email akan memerlukan verifikasi ulang
                    </p>
                    @endif
                </div>
            </div>

            <!-- Role & Division -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Role & Divisi</h4>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('role') border-red-300 @enderror" required>
                        <option value="">Pilih Role</option>
                        
                        <optgroup label="Koordinator">
                            <option value="koordinator_jurnalistik" {{ old('role', $user->role) === 'koordinator_jurnalistik' ? 'selected' : '' }}>Koordinator Jurnalistik</option>
                            <option value="koordinator_redaksi" {{ old('role', $user->role) === 'koordinator_redaksi' ? 'selected' : '' }}>Koordinator Redaksi</option>
                            <option value="koordinator_litbang" {{ old('role', $user->role) === 'koordinator_litbang' ? 'selected' : '' }}>Koordinator Litbang</option>
                            <option value="koordinator_humas" {{ old('role', $user->role) === 'koordinator_humas' ? 'selected' : '' }}>Koordinator Humas</option>
                            <option value="koordinator_media_kreatif" {{ old('role', $user->role) === 'koordinator_media_kreatif' ? 'selected' : '' }}>Koordinator Media Kreatif</option>
                        </optgroup>
                        
                        <optgroup label="Anggota Divisi">
                            <option value="anggota_redaksi" {{ old('role', $user->role) === 'anggota_redaksi' ? 'selected' : '' }}>Anggota Redaksi</option>
                            <option value="anggota_litbang" {{ old('role', $user->role) === 'anggota_litbang' ? 'selected' : '' }}>Anggota Litbang</option>
                            <option value="anggota_humas" {{ old('role', $user->role) === 'anggota_humas' ? 'selected' : '' }}>Anggota Humas</option>
                            <option value="anggota_media_kreatif" {{ old('role', $user->role) === 'anggota_media_kreatif' ? 'selected' : '' }}>Anggota Media Kreatif</option>
                        </optgroup>
                        
                        <optgroup label="Pengurus">
                            <option value="sekretaris" {{ old('role', $user->role) === 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="bendahara" {{ old('role', $user->role) === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                        </optgroup>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Change Warning -->
                <div id="roleChangeWarning" class="hidden bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Peringatan Perubahan Role</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Mengubah role akan mempengaruhi hak akses user. Pastikan perubahan ini sudah sesuai dengan tanggung jawab yang akan diberikan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Description -->
                <div id="roleDescription" class="hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h5 class="text-sm font-medium text-blue-800 mb-2">Deskripsi Role:</h5>
                        <p id="roleDescriptionText" class="text-sm text-blue-700"></p>
                    </div>
                </div>
            </div>

            <!-- Password Change -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Ubah Password (Opsional)</h4>
                
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('password') border-red-300 @enderror">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm">
                                <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="eyeIconConfirmation"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div id="passwordStrength" class="hidden mt-4">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div id="strengthBar" class="h-2 rounded-full transition-all duration-300"></div>
                            </div>
                            <span id="strengthText" class="text-xs font-medium"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Tambahan</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('phone') border-red-300 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan</label>
                        <input type="number" name="angkatan" id="angkatan" value="{{ old('angkatan', $user->angkatan) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('angkatan') border-red-300 @enderror" min="2000" max="{{ date('Y') + 1 }}">
                        @error('angkatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio/Deskripsi</label>
                    <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('bio') border-red-300 @enderror" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Account Status -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Status Akun</h4>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <span class="text-sm font-medium text-gray-900">Bergabung</span>
                        <p class="text-xs text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span class="text-sm text-gray-600">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Activity Summary -->
            @if(isset($user->contents_count) || isset($user->briefs_count) || isset($user->designs_count))
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Ringkasan Aktivitas</h4>
                
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

                @if(($user->contents_count ?? 0) > 0 || ($user->briefs_count ?? 0) > 0 || ($user->designs_count ?? 0) > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi Aktivitas</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>User ini memiliki aktivitas yang terkait dengan sistem. Perubahan role dapat mempengaruhi akses ke konten yang sudah dibuat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t">
                <a href="{{ route('koordinator-jurnalistik.users.show', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleDescription = document.getElementById('roleDescription');
    const roleDescriptionText = document.getElementById('roleDescriptionText');
    const roleChangeWarning = document.getElementById('roleChangeWarning');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    const originalRole = '{{ $user->role }}';

    // Role descriptions
    const roleDescriptions = {
        'koordinator_jurnalistik': 'Memiliki akses penuh untuk mengelola seluruh aktivitas UKM Jurnalistik, termasuk manajemen user, program kerja, dan koordinasi antar divisi.',
        'koordinator_redaksi': 'Bertanggung jawab mengelola konten redaksi, mengkoordinir anggota redaksi, dan memastikan kualitas tulisan.',
        'koordinator_litbang': 'Mengelola brief dan riset, mengkoordinir anggota litbang, dan bertanggung jawab atas pengembangan ide konten.',
        'koordinator_humas': 'Mengelola hubungan masyarakat, publikasi, dan koordinasi dengan pihak eksternal.',
        'koordinator_media_kreatif': 'Bertanggung jawab atas desain dan konten visual, mengkoordinir tim kreatif.',
        'anggota_redaksi': 'Menulis dan mengedit artikel, berita, dan konten editorial lainnya.',
        'anggota_litbang': 'Melakukan riset, membuat brief, dan mengembangkan ide konten.',
        'anggota_humas': 'Membantu aktivitas publikasi dan hubungan masyarakat.',
        'anggota_media_kreatif': 'Membuat desain, ilustrasi, dan konten visual.',
        'sekretaris': 'Mengelola administrasi, dokumentasi, dan koordinasi kegiatan organisasi.',
        'bendahara': 'Mengelola keuangan organisasi dan pelaporan keuangan.'
    };

    // Show role description and warning
    roleSelect.addEventListener('change', function() {
        const selectedRole = this.value;
        
        // Show description
        if (selectedRole && roleDescriptions[selectedRole]) {
            roleDescriptionText.textContent = roleDescriptions[selectedRole];
            roleDescription.classList.remove('hidden');
        } else {
            roleDescription.classList.add('hidden');
        }
        
        // Show warning if role changed
        if (selectedRole && selectedRole !== originalRole) {
            roleChangeWarning.classList.remove('hidden');
        } else {
            roleChangeWarning.classList.add('hidden');
        }
    });

    // Trigger change event on page load to show current role description
    if (roleSelect.value) {
        roleSelect.dispatchEvent(new Event('change'));
    }

    // Password visibility toggle
    function setupPasswordToggle(inputId, toggleId, iconId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        const icon = document.getElementById(iconId);

        toggle.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    }

    setupPasswordToggle('password', 'togglePassword', 'eyeIcon');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation', 'eyeIconConfirmation');

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = '';

        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]/)) strength += 1;
        if (password.match(/[A-Z]/)) strength += 1;
        if (password.match(/[0-9]/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

        switch (strength) {
            case 0:
            case 1:
                feedback = 'Sangat Lemah';
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
                strengthBar.style.width = '20%';
                strengthText.className = 'text-xs font-medium text-red-600';
                break;
            case 2:
                feedback = 'Lemah';
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-orange-500';
                strengthBar.style.width = '40%';
                strengthText.className = 'text-xs font-medium text-orange-600';
                break;
            case 3:
                feedback = 'Sedang';
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
                strengthBar.style.width = '60%';
                strengthText.className = 'text-xs font-medium text-yellow-600';
                break;
            case 4:
                feedback = 'Kuat';
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-blue-500';
                strengthBar.style.width = '80%';
                strengthText.className = 'text-xs font-medium text-blue-600';
                break;
            case 5:
                feedback = 'Sangat Kuat';
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
                strengthBar.style.width = '100%';
                strengthText.className = 'text-xs font-medium text-green-600';
                break;
        }

        strengthText.textContent = feedback;
    }

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        if (password.length > 0) {
            passwordStrength.classList.remove('hidden');
            checkPasswordStrength(password);
        } else {
            passwordStrength.classList.add('hidden');
        }
    });

    // Auto-resize textarea
    const bioTextarea = document.getElementById('bio');
    bioTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const passwordConfirmation = passwordConfirmationInput.value;

        // Only validate password if it's being changed
        if (password || passwordConfirmation) {
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                passwordConfirmationInput.focus();
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                passwordInput.focus();
                return false;
            }
        }

        // Confirm role change
        if (roleSelect.value !== originalRole) {
            if (!confirm('Anda akan mengubah role user ini. Apakah Anda yakin? Perubahan ini akan mempengaruhi hak akses user.')) {
                e.preventDefault();
                return false;
            }
        }
    });

    // NIM validation
    const nimInput = document.getElementById('nim');
    nimInput.addEventListener('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to reasonable length
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Phone validation
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        // Remove non-numeric characters except +
        this.value = this.value.replace(/[^0-9+]/g, '');
        
        // Ensure it starts with 0 or +62 for Indonesian numbers
        if (this.value.length > 0 && !this.value.startsWith('0') && !this.value.startsWith('+62')) {
            this.value = '0' + this.value.replace(/^[^0]/g, '');
        }
    });
    
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
@endsection
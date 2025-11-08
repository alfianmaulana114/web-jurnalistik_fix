@extends('layouts.koordinator-jurnalistik')

@section('title', 'Tambah User')
@section('header', 'Tambah User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Tambah User Baru</h3>
                <a href="{{ route('koordinator-jurnalistik.users.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <form action="{{ route('koordinator-jurnalistik.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Dasar</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('name') border-red-300 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM <span class="text-red-500">*</span></label>
                        <input type="number" name="nim" id="nim" value="{{ old('nim') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('nim') border-red-300 @enderror" required>
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('email') border-red-300 @enderror" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                            <option value="koordinator_jurnalistik" {{ old('role') === 'koordinator_jurnalistik' ? 'selected' : '' }}>Koordinator Jurnalistik</option>
                            <option value="koordinator_redaksi" {{ old('role') === 'koordinator_redaksi' ? 'selected' : '' }}>Koordinator Redaksi</option>
                            <option value="koordinator_litbang" {{ old('role') === 'koordinator_litbang' ? 'selected' : '' }}>Koordinator Litbang</option>
                            <option value="koordinator_humas" {{ old('role') === 'koordinator_humas' ? 'selected' : '' }}>Koordinator Humas</option>
                            <option value="koordinator_media_kreatif" {{ old('role') === 'koordinator_media_kreatif' ? 'selected' : '' }}>Koordinator Media Kreatif</option>
                        </optgroup>
                        
                        <optgroup label="Anggota Divisi">
                            <option value="anggota_redaksi" {{ old('role') === 'anggota_redaksi' ? 'selected' : '' }}>Anggota Redaksi</option>
                            <option value="anggota_litbang" {{ old('role') === 'anggota_litbang' ? 'selected' : '' }}>Anggota Litbang</option>
                            <option value="anggota_humas" {{ old('role') === 'anggota_humas' ? 'selected' : '' }}>Anggota Humas</option>
                            <option value="anggota_media_kreatif" {{ old('role') === 'anggota_media_kreatif' ? 'selected' : '' }}>Anggota Media Kreatif</option>
                        </optgroup>
                        
                        <optgroup label="Pengurus">
                            <option value="sekretaris" {{ old('role') === 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="bendahara" {{ old('role') === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                        </optgroup>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Description -->
                <div id="roleDescription" class="hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h5 class="text-sm font-medium text-blue-800 mb-2">Deskripsi Role:</h5>
                        <p id="roleDescriptionText" class="text-sm text-blue-700"></p>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Password</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10 py-2 pl-3 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                placeholder="Masukkan password" 
                                required
                            >
                            <button 
                                type="button" 
                                id="togglePassword" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200"
                                aria-label="Toggle password visibility"
                            >
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeIconSlash" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0L3 3m3.29 3.29L12 12m-5.71-5.71L12 12" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10 py-2 pl-3" 
                                placeholder="Konfirmasi password" 
                                required
                            >
                            <button 
                                type="button" 
                                id="togglePasswordConfirmation" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200"
                                aria-label="Toggle password confirmation visibility"
                            >
                                <svg id="eyeIconConfirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeIconSlashConfirmation" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0L3 3m3.29 3.29L12 12m-5.71-5.71L12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div id="passwordStrength" class="hidden">
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div id="strengthBar" class="h-2 rounded-full transition-all duration-300"></div>
                        </div>
                        <span id="strengthText" class="text-xs font-medium"></span>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Tambahan</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('phone') border-red-300 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan</label>
                        <input type="number" name="angkatan" id="angkatan" value="{{ old('angkatan', date('Y')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('angkatan') border-red-300 @enderror" min="2000" max="{{ date('Y') + 1 }}">
                        @error('angkatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio/Deskripsi</label>
                    <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm @error('bio') border-red-300 @enderror" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Permissions Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Informasi Akses</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>User yang dibuat akan mendapatkan akses sesuai dengan role yang dipilih. Pastikan role sudah sesuai dengan tanggung jawab yang akan diberikan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t">
                <a href="{{ route('koordinator-jurnalistik.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
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
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

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

    // Show role description
    roleSelect.addEventListener('change', function() {
        const selectedRole = this.value;
        if (selectedRole && roleDescriptions[selectedRole]) {
            roleDescriptionText.textContent = roleDescriptions[selectedRole];
            roleDescription.classList.remove('hidden');
        } else {
            roleDescription.classList.add('hidden');
        }
    });

    // Password visibility toggle - Using Tailwind SVG icons (only one icon visible at a time)
    function setupPasswordToggle(inputId, toggleId, eyeIconId, eyeSlashIconId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        const eyeIcon = document.getElementById(eyeIconId);
        const eyeSlashIcon = document.getElementById(eyeSlashIconId);

        if (!input || !toggle || !eyeIcon || !eyeSlashIcon) {
            console.error('Password toggle elements not found for:', inputId);
            return;
        }

        // Ensure only one icon is visible initially (eye icon when password is hidden)
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');

        // Function to update icon based on current password state
        function updateIcon() {
            if (input.type === 'password') {
                // Password is hidden - show eye icon (to show password), hide eye-slash
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('text-blue-500');
            } else {
                // Password is visible - hide eye icon, show eye-slash (to hide password)
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('text-blue-500');
            }
        }

        // Prevent form submission when clicking toggle
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle password visibility
            input.type = input.type === 'password' ? 'text' : 'password';
            
            // Update icon based on new state
            updateIcon();
        });
    }

    // Initialize password toggles
    setupPasswordToggle('password', 'togglePassword', 'eyeIcon', 'eyeIconSlash');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation', 'eyeIconConfirmation', 'eyeIconSlashConfirmation');

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
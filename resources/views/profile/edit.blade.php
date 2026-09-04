<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight tracking-tight">
            Profil Pengguna
        </h2>
    </x-slot>

    @php
        // QR AUTO FIX
        $qrFile = $employee->qr_code ?? null;
        if ($qrFile && str_contains($qrFile, '/')) {
            $qrFile = basename($qrFile);
        }
        $qrPath = null;
        $folders = ['qr_codes', 'qrcodes', 'qr', 'employee_qr'];
        if ($qrFile) {
            foreach ($folders as $f) {
                if (file_exists(storage_path("app/public/$f/$qrFile"))) {
                    $qrPath = asset("storage/$f/$qrFile");
                    break;
                }
            }
        }
    @endphp

    <div class="py-6 px-4 max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm"
            class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 space-y-8">
            @csrf
            @method('PATCH')

            {{-- ===================== FOTO PROFIL (UI ASLI) ===================== --}}
            <div class="flex flex-col items-center">
                <div class="relative group w-44 h-44 rounded-full overflow-hidden cursor-pointer ring-4 ring-indigo-500/20 hover:ring-indigo-500/40 transition duration-300"
                    title="Klik foto untuk ganti foto">

                    <img src="{{ $user->photo ? asset('storage/photos/' . $user->photo) : asset('images/default-avatar.png') }}"
                        class="w-full h-full object-cover rounded-full">

                    <button type="button"
                        class="absolute inset-0 bg-slate-950/60 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition duration-300 focus:outline-none"
                        onclick="document.getElementById('photoInput').click()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <input type="file" name="photo" id="photoInput" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="document.getElementById('profileForm').submit()">
                </div>

                <p class="text-slate-400 text-xs mt-3 select-none">Klik foto profil untuk ganti foto</p>
            </div>

            {{-- ===================== INFORMASI PRIBADI (UI ASLI) ===================== --}}
            <div class="space-y-4 pt-4 border-t border-slate-800">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-200 uppercase tracking-wider">Informasi Pribadi</h3>
                    <p class="text-xs text-slate-400 mt-1">Perbarui detail profil dasar Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- ID Karyawan --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">ID Karyawan</label>
                        <input type="text" value="{{ $employee->employee_id ?? '-' }}" readonly
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950/60 border border-slate-850 text-slate-500 cursor-not-allowed select-none text-sm" />
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name ?? $user->name) }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ $employee->birth_place ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $employee->birth_date ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Jenis Kelamin</label>
                        <select name="gender"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm">
                            <option value="L" {{ ($employee->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ ($employee->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Jabatan</label>
                        <input type="text" name="position" value="{{ $employee->position ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">No HP</label>
                        <input type="text" name="phone" value="{{ $employee->phone ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" value="{{ $employee->email ?? $user->email }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    {{-- QR CODE BUTTON --}}
                    <div class="flex items-end">
                        <button type="button"
                            onclick="document.getElementById('qrModal').classList.remove('hidden')"
                            class="w-full sm:w-auto px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-1.5 h-[44px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Lihat QR Code Absensi</span>
                        </button>
                    </div>

                </div>
            </div>

            {{-- ===================== GANTI PASSWORD (UI ASLI) ===================== --}}
            <div class="space-y-4 pt-6 border-t border-slate-800">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-200 uppercase tracking-wider">Ganti Password</h3>
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika Anda tidak ingin mengganti password akun.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Password Lama</label>
                        <input type="password" name="current_password"
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Password Baru</label>
                        <input type="password" name="new_password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                    </div>
                </div>

                <div class="max-w-md">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                </div>
            </div>

            {{-- ===================== SUBMIT ===================== --}}
            <div class="pt-6 border-t border-slate-800 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200">
                    Simpan Perubahan
                </button>
            </div>

            {{-- ===================== NOTIF SUKSES ===================== --}}
            @if(session('status'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="fixed inset-0 flex items-center justify-center z-50 p-4">

                    <div class="bg-emerald-600 text-white px-6 py-3.5 rounded-xl shadow-2xl text-base font-semibold border border-emerald-500/20 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
            @endif

        </form>
    </div>

    {{-- ===================== QR MODAL ===================== --}}
    <div id="qrModal" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl relative w-full max-w-sm">
            <button class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors"
                onclick="document.getElementById('qrModal').classList.add('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h2 class="text-slate-200 text-lg font-bold text-center mb-5">QR Code Absensi</h2>

            <div class="bg-white p-4 rounded-xl shadow-inner flex items-center justify-center">
                @if ($qrPath)
                    <img src="{{ $qrPath }}" class="w-full h-auto object-contain max-h-64 rounded-lg">
                @else
                    <p class="text-slate-550 text-center py-8 text-sm italic">QR Code tidak ditemukan.</p>
                @endif
            </div>
            
            <p class="text-slate-400 text-xs text-center mt-4">Tunjukkan QR Code ini pada scanner absensi ketika masuk atau keluar.</p>
        </div>
    </div>

</x-app-layout>

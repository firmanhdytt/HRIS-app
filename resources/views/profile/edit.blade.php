<x-app-layout>
    <x-slot name="header">
        <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Profil Pengguna</h1>
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
            class="bg-white border border-slate-200/80 rounded-2xl shadow-xs p-6 sm:p-8 space-y-8"
            data-confirm="Apakah Anda yakin ingin menyimpan perubahan profil ini?"
            data-confirm-title="Konfirmasi Update Profil" data-confirm-btn="Ya, Simpan">
            @csrf
            @method('PATCH')

            <!-- FOTO PROFIL -->
            <div class="flex flex-col items-center">
                <div class="relative group w-36 h-36 rounded-full overflow-hidden cursor-pointer ring-4 ring-indigo-500/20 hover:ring-indigo-500/40 transition duration-300 shadow-md"
                    title="Klik foto untuk ganti foto">

                    <img src="{{ $user->photo ? asset('storage/photos/' . $user->photo) : asset('images/default-avatar.png') }}"
                        class="w-full h-full object-cover rounded-full">

                    <button type="button"
                        class="absolute inset-0 bg-slate-900/60 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition duration-300 focus:outline-none"
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

                <p class="text-slate-500 text-xs mt-3 select-none">Klik foto profil untuk mengganti foto</p>
            </div>

            <!-- INFORMASI PRIBADI -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <div class="mb-4">
                    <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Informasi Pribadi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui detail profil dasar Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- ID Karyawan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">ID Karyawan</label>
                        <input type="text" value="{{ $employee->employee_id ?? '-' }}" readonly
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 cursor-not-allowed text-xs font-bold" />
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name ?? $user->name) }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ $employee->birth_place ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $employee->birth_date ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Jenis Kelamin</label>
                        <select name="gender"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                            <option value="L" {{ ($employee->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ ($employee->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Jabatan</label>
                        <input type="text" name="position" value="{{ $employee->position ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">No HP</label>
                        <input type="text" name="phone" value="{{ $employee->phone ?? '' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" value="{{ $employee->email ?? $user->email }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    {{-- QR CODE BUTTON --}}
                    <div class="flex items-end">
                        <button type="button"
                            onclick="document.getElementById('qrModal').classList.remove('hidden')"
                            class="w-full sm:w-auto px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5 h-[42px]">
                            <span>Lihat QR Code Absensi</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- GANTI PASSWORD -->
            <div class="space-y-4 pt-6 border-t border-slate-200">
                <div class="mb-4">
                    <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Ganti Password</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kosongkan jika Anda tidak ingin mengganti password akun.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Password Lama</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Password Baru</label>
                        <input type="password" name="new_password" placeholder="Minimal 8 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                    </div>
                </div>

                <div class="max-w-md">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="pt-6 border-t border-slate-200 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- QR MODAL -->
    <div id="qrModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-2xl relative w-full max-w-sm">
            <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition text-xl font-bold"
                onclick="document.getElementById('qrModal').classList.add('hidden')">
                &times;
            </button>

            <h2 class="text-slate-900 text-base font-bold text-center mb-4">QR Code Absensi</h2>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-center">
                @if ($qrPath)
                    <img src="{{ $qrPath }}" class="w-full h-auto object-contain max-h-64 rounded-lg">
                @else
                    <p class="text-slate-400 text-center py-8 text-xs italic">QR Code tidak ditemukan.</p>
                @endif
            </div>
            
            <p class="text-slate-500 text-xs text-center mt-3">Tunjukkan QR Code ini pada scanner absensi ketika masuk atau keluar.</p>
        </div>
    </div>
</x-app-layout>

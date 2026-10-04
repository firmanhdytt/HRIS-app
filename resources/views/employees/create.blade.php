<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">
                {{ isset($employee) ? 'Edit Data Karyawan - ' . $employee->name : 'Tambah Karyawan Baru' }}
            </h1>
            <a href="{{ route('employees.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-semibold">
                ← Kembali ke List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto my-6 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-10 shadow-xs">
        <form method="POST"
            action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}"
            enctype="multipart/form-data" class="space-y-6"
            data-confirm="Apakah Anda yakin data karyawan sudah benar dan ingin disimpan?"
            data-confirm-title="Konfirmasi Simpan Karyawan" data-confirm-btn="Ya, Simpan">
            @csrf
            @if(isset($employee))
                @method('PUT')
            @endif

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}" required
                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- RFID UID -->
            <div>
                <label for="rfid_uid" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">RFID UID</label>
                <div class="flex items-center gap-3">
                    <input type="text" name="rfid_uid" id="rfid_uid"
                        value="{{ old('rfid_uid', $employee->rfid_uid ?? '') }}"
                        placeholder="Tap kartu untuk mengisi UID otomatis"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />

                    <button type="button" id="scanRfidBtn"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition active:scale-[0.98] shadow-sm whitespace-nowrap">
                        Scan RFID
                    </button>
                </div>
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="birth_place" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tempat Lahir</label>
                    <input type="text" name="birth_place" id="birth_place"
                        value="{{ old('birth_place', $employee->birth_place ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                    @error('birth_place') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="birth_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ old('birth_date', $employee->birth_date ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                    @error('birth_date') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Jenis Kelamin, Kontak, Email -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jenis Kelamin</label>
                    <div class="mt-3 flex items-center gap-6 text-slate-700 text-sm">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="radio" name="gender" value="L" class="text-indigo-600 focus:ring-indigo-500 border-slate-300"
                                {{ old('gender', $employee->gender ?? '') == 'L' ? 'checked' : '' }}> 
                            <span>Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="radio" name="gender" value="P" class="text-indigo-600 focus:ring-indigo-500 border-slate-300"
                                {{ old('gender', $employee->gender ?? '') == 'P' ? 'checked' : '' }}> 
                            <span>Perempuan</span>
                        </label>
                    </div>
                    @error('gender') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">No. HP / Kontak</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                    @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                    @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="3" required
                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">{{ old('address', $employee->address ?? '') }}</textarea>
                @error('address') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Jabatan, Departemen, Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="position" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jabatan</label>
                    <select name="position" id="position" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        <option value="">Pilih Jabatan</option>
                        <option value="Lead" {{ old('position', $employee->position ?? '') == 'Lead' ? 'selected' : '' }}>Lead</option>
                        <option value="Manager" {{ old('position', $employee->position ?? '') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Staff" {{ old('position', $employee->position ?? '') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div>
                    <label for="department" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Departemen</label>
                    <select name="department" id="department" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        <option value="">Pilih Departemen</option>
                        <option value="Karyawan" {{ old('department', $employee->department ?? '') == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="Marketing" {{ old('department', $employee->department ?? '') == 'Marketing' ? 'selected' : '' }}>Marketing & Branding</option>
                        <option value="Digital" {{ old('department', $employee->department ?? '') == 'Digital' ? 'selected' : '' }}>Digital Marketing</option>
                        <option value="Customer Service" {{ old('department', $employee->department ?? '') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                        <option value="Project Manager" {{ old('department', $employee->department ?? '') == 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                        <option value="School Officer" {{ old('department', $employee->department ?? '') == 'School Officer' ? 'selected' : '' }}>School Officer</option>
                    </select>
                </div>

                <div>
                    <label for="employment_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Status Kerja</label>
                    <select name="employment_status" id="employment_status" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        <option value="">Pilih Status</option>
                        <option value="Tetap" {{ old('employment_status', $employee->employment_status ?? '') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="Kontrak" {{ old('employment_status', $employee->employment_status ?? '') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="Magang" {{ old('employment_status', $employee->employment_status ?? '') == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal Masuk / Keluar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="join_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Masuk</label>
                    <input type="date" name="join_date" id="join_date"
                        value="{{ old('join_date', $employee->join_date ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                    @error('join_date') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="resign_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Keluar (Opsional)</label>
                    <input type="date" name="resign_date" id="resign_date"
                        value="{{ old('resign_date', $employee->resign_date ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition" />
                    @error('resign_date') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Foto -->
            <div>
                <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Foto Karyawan (Opsional)</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="block w-full text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition" />

                @if(isset($employee) && $employee->photo)
                    <p class="mt-2 text-slate-500 text-xs">Foto saat ini:</p>
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto"
                        class="w-20 h-20 object-cover rounded-xl mt-1 border border-slate-200" />
                @endif

                @error('photo') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan / Keterangan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">{{ old('notes', $employee->notes ?? '') }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('employees.index') }}"
                    class="px-6 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl px-6 py-2.5 text-xs shadow-sm transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("scanRfidBtn").addEventListener("click", function () {
            alert("Tempelkan kartu RFID ke alat...");

            let interval = setInterval(() => {
                fetch("{{ url('/api/rfid/last') }}")
                    .then(res => res.json())
                    .then(data => {
                        if (data.uid) {
                            document.getElementById("rfid_uid").value = data.uid;
                            clearInterval(interval);
                            alert("RFID UID berhasil terbaca:\n" + data.uid);
                        }
                    });
            }, 1000);
        });
    </script>
</x-app-layout>

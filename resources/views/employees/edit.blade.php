<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">
                Edit Data Karyawan - {{ $employee->name }}
            </h1>
            <a href="{{ route('employees.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-semibold">
                ← Kembali ke List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto my-6 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-10 shadow-xs">
        <form method="POST"
            action="{{ route('employees.update', $employee) }}"
            enctype="multipart/form-data" class="space-y-6"
            data-confirm="Apakah Anda yakin ingin memperbarui data karyawan {{ $employee->name }}?"
            data-confirm-title="Konfirmasi Update Karyawan" data-confirm-btn="Ya, Perbarui">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required
                    value="{{ old('name', $employee->name ?? '') }}"
                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- RFID UID -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">RFID UID</label>
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
                @error('rfid_uid') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tempat Lahir</label>
                    <input type="text" name="birth_place" required
                        value="{{ old('birth_place', $employee->birth_place ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" required
                        value="{{ old('birth_date', $employee->birth_date ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                </div>
            </div>

            <!-- Gender, Phone, Email -->
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
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">No. HP / Kontak</label>
                    <input type="text" name="phone" required
                        value="{{ old('phone', $employee->phone ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" 
                        value="{{ old('email', $employee->email ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Lengkap</label>
                <textarea name="address" rows="3" required
                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">{{ old('address', $employee->address ?? '') }}</textarea>
            </div>

            <!-- Jabatan - Departemen - Status Kerja -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jabatan</label>
                    <select name="position" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        <option value="">Pilih Jabatan</option>
                        <option value="Lead"    {{ old('position', $employee->position ?? '') == 'Lead' ? 'selected' : '' }}>Lead</option>
                        <option value="Manager" {{ old('position', $employee->position ?? '') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Staff"   {{ old('position', $employee->position ?? '') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Departemen</label>
                    <select name="department" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        <option value="">Pilih Departemen</option>
                        <option value="Karyawan"        {{ old('department', $employee->department ?? '') == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="Marketing"        {{ old('department', $employee->department ?? '') == 'Marketing' ? 'selected' : '' }}>Marketing & Branding</option>
                        <option value="Digital"          {{ old('department', $employee->department ?? '') == 'Digital' ? 'selected' : '' }}>Digital Marketing</option>
                        <option value="Customer Service" {{ old('department', $employee->department ?? '') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                        <option value="Project Manager"  {{ old('department', $employee->department ?? '') == 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                        <option value="School Officer"   {{ old('department', $employee->department ?? '') == 'School Officer' ? 'selected' : '' }}>School Officer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Status Kerja</label>
                    <select name="employment_status" required
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        <option value="">Pilih Status</option>
                        <option value="Tetap"   {{ old('employment_status', $employee->employment_status ?? '') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="Kontrak" {{ old('employment_status', $employee->employment_status ?? '') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="Magang"  {{ old('employment_status', $employee->employment_status ?? '') == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal Masuk & Keluar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Masuk</label>
                    <input type="date" name="join_date" required
                        value="{{ old('join_date', $employee->join_date ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Keluar (Opsional)</label>
                    <input type="date" name="resign_date"
                        value="{{ old('resign_date', $employee->resign_date ?? '') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 text-slate-800 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition">
                </div>
            </div>

            <!-- Upload Foto -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Foto Karyawan (Opsional)</label>
                <input type="file" name="photo" accept="image/*"
                    class="block w-full text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition">

                @if(isset($employee) && $employee->photo)
                    <p class="mt-2 text-slate-500 text-xs">Foto saat ini:</p>
                    <img src="{{ asset('storage/' . $employee->photo) }}"
                        class="w-20 h-20 rounded-xl object-cover mt-1 border border-slate-200">
                @endif
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan / Keterangan</label>
                <textarea name="notes" rows="3"
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Scan RFID JS --}}
    <script>
        document.getElementById("scanRfidBtn").addEventListener("click", function () {
            alert("Tempelkan kartu RFID ke alat...");

            let interval = setInterval(() => {
                fetch("/api/rfid/last")
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

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ isset($employee) ? 'Edit Karyawan - ' . $employee->name : 'Tambah Karyawan Baru' }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto my-6 bg-slate-900 border border-slate-800/80 rounded-2xl p-6 sm:p-10 shadow-xl">
        <form method="POST"
            action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($employee))
                @method('PUT')
            @endif

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}" required
                    class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- RFID UID -->
            <div>
                <label for="rfid_uid" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">RFID UID</label>
                <div class="flex items-center gap-3">
                    <input type="text" name="rfid_uid" id="rfid_uid"
                        value="{{ old('rfid_uid', $employee->rfid_uid ?? '') }}"
                        placeholder="Tap kartu untuk mengisi UID otomatis"
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />

                    <button type="button" id="scanRfidBtn"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition-all duration-200 active:scale-[0.98] shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 whitespace-nowrap">
                        Scan RFID
                    </button>
                </div>
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="birth_place" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Tempat Lahir</label>
                    <input type="text" name="birth_place" id="birth_place"
                        value="{{ old('birth_place', $employee->birth_place ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                    @error('birth_place') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="birth_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ old('birth_date', $employee->birth_date ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                    @error('birth_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Jenis Kelamin, Kontak, Email -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Jenis Kelamin</label>
                    <div class="mt-3 flex items-center gap-6 text-slate-300">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="radio" name="gender" value="L" class="text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 bg-slate-950 border-slate-800"
                                {{ old('gender', $employee->gender ?? '') == 'L' ? 'checked' : '' }}> 
                            <span>Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="radio" name="gender" value="P" class="text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 bg-slate-950 border-slate-800"
                                {{ old('gender', $employee->gender ?? '') == 'P' ? 'checked' : '' }}> 
                            <span>Perempuan</span>
                        </label>
                    </div>
                    @error('gender') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">No. HP / Kontak Darurat</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone ?? '') }}"
                        required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                    @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email ?? '') }}"
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                    @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="4" required
                    class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">{{ old('address', $employee->address ?? '') }}</textarea>
                @error('address') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Jabatan, Departemen, Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="position" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Jabatan</label>
                    <select name="position" id="position" required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                        <option value="">Pilih Jabatan</option>
                        <option value="Lead" {{ old('position', $employee->position ?? '') == 'Lead' ? 'selected' : '' }}>Lead</option>
                        <option value="Manager" {{ old('position', $employee->position ?? '') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Staff" {{ old('position', $employee->position ?? '') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div>
                    <label for="department" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Departemen</label>
                    <select name="department" id="department" required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
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
                    <label for="employment_status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Status Kerja</label>
                    <select name="employment_status" id="employment_status" required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
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
                    <label for="join_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Masuk</label>
                    <input type="date" name="join_date" id="join_date"
                        value="{{ old('join_date', $employee->join_date ?? '') }}" required
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                    @error('join_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="resign_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Keluar (Opsional)</label>
                    <input type="date" name="resign_date" id="resign_date"
                        value="{{ old('resign_date', $employee->resign_date ?? '') }}"
                        class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200" />
                    @error('resign_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Foto -->
            <div>
                <label for="photo" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Foto Karyawan (Opsional)</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="block w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 transition" />

                @if(isset($employee) && $employee->photo)
                    <p class="mt-2 text-slate-400 text-xs">Foto saat ini:</p>
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto"
                        class="w-24 h-24 object-cover rounded-xl mt-1 border border-slate-800" />
                @endif

                @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Catatan / Keterangan (Opsional)</label>
                <textarea name="notes" id="notes" rows="4"
                    class="block w-full rounded-xl border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-650 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">{{ old('notes', $employee->notes ?? '') }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('employees.index') }}"
                    class="px-6 py-2.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 hover:text-slate-200 hover:bg-slate-900 transition-all duration-200 active:scale-[0.98]">
                    Batal
                </a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-2.5 shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-200 active:scale-[0.98]">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    @if(session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
            <div class="bg-green-600 text-white px-6 py-3 rounded shadow-lg pointer-events-auto">
                {{ session('status') }}
            </div>
        </div>
    @endif

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

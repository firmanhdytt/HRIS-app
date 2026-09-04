<x-app-layout>
    <x-slot name="header" class="bg-gray-700">
        <div class="text-white text-xl font-semibold leading-tight">
            <!-- Judul -->
            <h2 class="font-semibold text-xl text-white leading-tight">
                Data Karyawan
            </h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto space-y-6">

        {{-- FILTER FORM --}}
        <form method="GET" class="bg-slate-900 border border-slate-800/80 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            
            <!-- Left Side (Search & Button) -->
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <input type="text" name="search" placeholder="Cari nama atau ID..." value="{{ request('search') }}"
                    class="rounded-xl px-4 py-2.5 bg-slate-950 text-slate-100 border border-slate-850 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 flex-1 min-w-[200px]" />

                <button type="submit" class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-lg shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all">
                    Cari 🔍
                </button>
            </div>

            <!-- Right Side (Dept & Gender Filters) -->
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="department" class="rounded-xl px-4 py-2.5 bg-slate-950 text-slate-200 border border-slate-850 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 min-w-[160px]">
                    <option value="">Semua Departemen</option>
                    @foreach(\App\Models\Employee::distinct('department')->pluck('department') as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>

                <select name="gender" class="rounded-xl px-4 py-2.5 bg-slate-950 text-slate-200 border border-slate-850 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 min-w-[140px]">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </form>

        {{-- TABLE CARD --}}
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-800 text-slate-200 text-xs">
                    <thead class="bg-slate-950 text-slate-400">
                        <tr>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">No</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">ID Karyawan</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Jabatan</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Departemen</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider md:table-cell" style="display: none;" id="catatan-header">Catatan</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider md:table-cell" style="display: none;" id="foto-header">Foto</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-4 py-3.5 text-center font-semibold uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($employees as $index => $employee)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-slate-400">{{ $employees->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-300">{{ $employee->employee_id }}</td>
                                <td class="px-4 py-3 font-medium text-slate-100">{{ $employee->name }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $employee->position }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $employee->department ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-md font-semibold text-[10px] inline-block
                                        {{ $employee->employment_status === 'tetap' || $employee->employment_status === 'Permanent' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                        {{ $employee->employment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate text-slate-400 md:table-cell" style="display: none;" title="{{ $employee->notes }}">
                                    {{ $employee->notes ?: '-' }}
                                </td>
                                <td class="px-4 py-3 md:table-cell" style="display: none;">
                                    @if($employee->photo && Storage::disk('public')->exists($employee->photo))
                                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto"
                                            class="photo-thumb w-10 h-10 object-cover rounded-lg cursor-pointer hover:opacity-85 transition-opacity border border-slate-800"
                                            data-name="{{ $employee->name }}">
                                    @else
                                        <span class="text-slate-500">Tidak ada</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('employees.edit', $employee) }}"
                                                class="px-3 py-1.5 rounded-lg border border-slate-800 bg-slate-900 text-slate-300 hover:bg-slate-800 hover:text-slate-100 active:scale-[0.97] transition-all">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin hapus karyawan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg border border-slate-800 bg-slate-900 text-rose-400 hover:bg-rose-950/30 hover:border-rose-500/25 active:scale-[0.97] transition-all">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 9 : 8 }}" class="px-4 py-8 text-center text-slate-500">
                                    Data karyawan tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
    </div>

    <div class=""> <!-- // Pagination -->
        {{ $employees->withQueryString()->links() }} <!-- // Pertahankan filter saat pindah halaman -->
    </div>

    <!-- Modal untuk Foto -->
    <div id="photoModal"
        class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center hidden z-50 transition-opacity duration-300">
        <!-- // Modal foto -->
        <div class="relative"> <!-- // Container modal -->
            <button id="closeModal" class="absolute top-2 right-2 text-white text-2xl font-bold z-10">&times;</button>
            <!-- // Tombol X -->

            <img id="modalImage" src="" alt="Foto Karyawan" class="max-w-full max-h-80 object-contain rounded-md">
            <!-- // Foto besar -->
        </div>
    </div>

    <!-- JS untuk Modal & Kolom Responsif -->
    <script>
        (function() {
            function initPhotoModal() {
                const modal = document.getElementById('photoModal');
                const modalImage = document.getElementById('modalImage');
                const closeModal = document.getElementById('closeModal');

                if (!modal || !closeModal) return;

                document.querySelectorAll('.photo-thumb').forEach(img => {
                    img.addEventListener('click', function () {
                        modalImage.src = this.src;
                        modal.classList.remove('hidden');
                    });
                });

                closeModal.onclick = () => modal.classList.add('hidden');
                modal.onclick = (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                };
            }

            function toggleColumns() {
                const isMobile = window.innerWidth < 768;
                const catatanHeaders = document.querySelectorAll('#catatan-header, td:nth-child(7)');
                const fotoHeaders = document.querySelectorAll('#foto-header, td:nth-child(8)');
                [catatanHeaders, fotoHeaders].forEach(group => {
                    group.forEach(el => {
                        el.style.display = isMobile ? 'none' : 'table-cell';
                    });
                });
            }

            // Run once
            initPhotoModal();
            toggleColumns();

            // Run on AJAX update
            document.addEventListener('ajax-container:updated', () => {
                initPhotoModal();
                toggleColumns();
            });

            // Avoid adding multiple resize listeners
            window.removeEventListener('resize', toggleColumns);
            window.addEventListener('resize', toggleColumns);
        })();
    </script>

</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Data Karyawan</h1>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    <span>+ Tambah Karyawan</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto space-y-6">

        {{-- FILTER FORM --}}
        <form method="GET" class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            
            <!-- Left Side (Search & Button) -->
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <input type="text" name="search" placeholder="Cari nama atau ID..." value="{{ request('search') }}"
                    class="rounded-xl px-4 py-2 bg-slate-50 text-slate-800 border border-slate-300 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-xs transition flex-1 min-w-[200px]" />

                <button type="submit" class="rounded-xl px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    Cari 🔍
                </button>
            </div>

            <!-- Right Side (Dept & Gender Filters) -->
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="department" class="rounded-xl px-4 py-2 bg-slate-50 text-slate-800 border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-xs transition min-w-[160px]">
                    <option value="">Semua Departemen</option>
                    @foreach(\App\Models\Employee::distinct('department')->pluck('department') as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>

                <select name="gender" class="rounded-xl px-4 py-2 bg-slate-50 text-slate-800 border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-xs transition min-w-[140px]">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </form>

        {{-- TABLE CARD --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-slate-700 text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">ID Karyawan</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Jabatan</th>
                            <th class="px-4 py-3 text-left">Departemen</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left md:table-cell" style="display: none;" id="catatan-header">Catatan</th>
                            <th class="px-4 py-3 text-left md:table-cell" style="display: none;" id="foto-header">Foto</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($employees as $index => $employee)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-slate-500 font-medium">{{ $employees->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $employee->employee_id }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $employee->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $employee->position }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $employee->department ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-0.5 rounded-lg font-bold text-[10px] inline-block
                                        {{ $employee->employment_status === 'tetap' || $employee->employment_status === 'Permanent' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $employee->employment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate text-slate-500 md:table-cell" style="display: none;" title="{{ $employee->notes }}">
                                    {{ $employee->notes ?: '-' }}
                                </td>
                                <td class="px-4 py-3 md:table-cell" style="display: none;">
                                    @if($employee->photo && Storage::disk('public')->exists($employee->photo))
                                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto"
                                            class="photo-thumb w-9 h-9 object-cover rounded-lg cursor-pointer hover:opacity-80 transition border border-slate-200"
                                            data-name="{{ $employee->name }}">
                                    @else
                                        <span class="text-slate-400 text-[11px]">-</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('employees.edit', $employee) }}"
                                                class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 font-semibold transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline"
                                                data-confirm="Apakah Anda yakin ingin menghapus data karyawan {{ $employee->name }}?"
                                                data-confirm-title="Konfirmasi Hapus Karyawan" data-confirm-btn="Ya, Hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 9 : 8 }}" class="px-4 py-8 text-center text-slate-400">
                                    <p class="text-2xl mb-1">📋</p>
                                    <p>Data karyawan tidak ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $employees->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Modal untuk Foto -->
    <div id="photoModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4 transition-opacity">
        <div class="relative bg-white border border-slate-200 p-3 rounded-2xl shadow-2xl">
            <button id="closeModal" class="absolute top-2 right-2 bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center font-bold text-lg hover:bg-slate-200 transition">&times;</button>
            <img id="modalImage" src="" alt="Foto Karyawan" class="max-w-full max-h-80 object-contain rounded-xl">
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

            initPhotoModal();
            toggleColumns();

            document.addEventListener('ajax-container:updated', () => {
                initPhotoModal();
                toggleColumns();
            });

            window.removeEventListener('resize', toggleColumns);
            window.addEventListener('resize', toggleColumns);
        })();
    </script>
</x-app-layout>
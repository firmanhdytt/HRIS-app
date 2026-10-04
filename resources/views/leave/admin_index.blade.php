<x-app-layout>
    <x-slot name="header">
        <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Kelola Pengajuan Izin & Cuti</h1>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- CARD WRAPPER -->
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden p-5 sm:p-6">
            
            <!-- HEADER INFO -->
            <div class="mb-5">
                <h2 class="text-base font-bold text-slate-900">Daftar Pengajuan Karyawan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Gunakan tabel di bawah ini untuk meninjau dan memberikan persetujuan pengajuan izin atau cuti.</p>
            </div>

            <!-- WRAPPER RESPONSIVE TABLE -->
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-slate-700 text-xs whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Karyawan</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Bukti</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($leaves as $izin)
                            <tr class="hover:bg-slate-50 transition-colors">
                                {{-- KARYAWAN --}}
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-900">{{ $izin->employee->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $izin->employee->position ?? 'Karyawan' }}</div>
                                </td>

                                {{-- JENIS IZIN --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ ucfirst($izin->jenis_izin) }}
                                    </span>
                                </td>

                                {{-- TANGGAL --}}
                                <td class="px-4 py-3">
                                    <div class="text-xs font-semibold text-slate-800">
                                        {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d F Y') }}
                                    </div>
                                    @if ($izin->tanggal_selesai)
                                        <div class="flex items-center gap-1 text-[10px] text-slate-500 mt-0.5">
                                            <span>s/d</span>
                                            <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d F Y') }}</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3">
                                    @if($izin->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @elseif($izin->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- BUKTI --}}
                                <td class="px-4 py-3">
                                    @if ($izin->bukti_path)
                                        <button onclick="openModal('{{ asset('storage/' . $izin->bukti_path) }}')"
                                            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-bold text-xs transition-colors">
                                            Lihat Bukti 📎
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-xs italic">-</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-3 text-center align-middle">
                                    @if ($izin->status === 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openReasonModal('approve', {{ $izin->id }})" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-xs transition">
                                                Terima
                                            </button>

                                            <button onclick="openReasonModal('reject', {{ $izin->id }})" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-xs transition">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                    <p class="text-2xl mb-1">📝</p>
                                    <p>Belum ada pengajuan izin atau cuti.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL BUKTI -->
    <div id="modalBukti" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-xl relative border border-slate-200 shadow-2xl">
            <button id="closeModalBukti" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors text-xl font-bold">&times;</button>
            <h3 class="text-slate-900 font-bold text-base mb-4">Lampiran Bukti Izin</h3>
            <div id="modalBuktiContent" class="text-center text-slate-500 text-xs overflow-hidden flex items-center justify-center min-h-[200px]">
                Memuat bukti...
            </div>
        </div>
    </div>

    <!-- MODAL ALASAN -->
    <div id="modalReason" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md relative border border-slate-200 shadow-2xl">
            <button id="closeModalReason" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors text-xl font-bold">&times;</button>
            <h3 id="modalReasonTitle" class="text-slate-900 font-bold text-base mb-1">Tulis Catatan</h3>
            <p class="text-slate-500 text-xs mb-4">Berikan catatan atau alasan persetujuan ini (opsional).</p>

            <form id="reasonForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <textarea name="alasan" rows="3"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                        placeholder="Tuliskan catatan..."></placeholder>
                </div>

                <button type="submit" id="reasonSubmitBtn"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 py-2.5 rounded-xl text-xs font-bold text-white shadow-md transition duration-200">
                    Kirim Konfirmasi
                </button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            function initLeaveModals() {
                const modalBukti = document.getElementById('modalBukti');
                const closeModalBukti = document.getElementById('closeModalBukti');
                const modalContent = document.getElementById('modalBuktiContent');

                if (modalBukti && closeModalBukti) {
                    window.openModal = function (fileUrl) {
                        modalContent.innerHTML = "Memuat bukti...";
                        const ext = fileUrl.split('.').pop().toLowerCase();

                        if (ext === "pdf") {
                            modalContent.innerHTML = `<embed src="${fileUrl}" type="application/pdf" class="w-full h-96 rounded-xl border border-slate-200" />`;
                        } else if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
                            modalContent.innerHTML = `<img src="${fileUrl}" class="max-h-96 mx-auto rounded-xl border border-slate-200 object-contain shadow-sm" />`;
                        } else {
                            modalContent.innerHTML = `
                                <div class="flex flex-col items-center justify-center p-4 space-y-3">
                                    <span class="text-slate-500 text-xs">File ini tidak dapat ditampilkan langsung.</span>
                                    <a href="${fileUrl}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-4 py-2 rounded-xl font-bold transition">Unduh Lampiran</a>
                                </div>
                            `;
                        }

                        modalBukti.classList.remove("hidden");
                        modalBukti.classList.add("flex");
                    };

                    closeModalBukti.onclick = () => {
                        modalBukti.classList.add("hidden");
                        modalBukti.classList.remove("flex");
                    };

                    modalBukti.onclick = (e) => {
                        if (e.target === modalBukti) {
                            modalBukti.classList.add("hidden");
                            modalBukti.classList.remove("flex");
                        }
                    };
                }

                const modalReason = document.getElementById('modalReason');
                const closeModalReason = document.getElementById('closeModalReason');
                const reasonForm = document.getElementById('reasonForm');
                const modalReasonTitle = document.getElementById('modalReasonTitle');
                const reasonSubmitBtn = document.getElementById('reasonSubmitBtn');

                if (modalReason && closeModalReason) {
                    window.openReasonModal = function (type, id) {
                        if (type === "approve") {
                            reasonForm.action = `/izin/${id}/approve`;
                            modalReasonTitle.textContent = "Setujui Pengajuan Izin";
                            reasonSubmitBtn.className = "w-full bg-emerald-600 hover:bg-emerald-700 py-2.5 rounded-xl text-xs font-bold text-white shadow-sm transition";
                            reasonSubmitBtn.textContent = "Setujui Pengajuan";
                        } else {
                            reasonForm.action = `/izin/${id}/reject`;
                            modalReasonTitle.textContent = "Tolak Pengajuan Izin";
                            reasonSubmitBtn.className = "w-full bg-rose-600 hover:bg-rose-700 py-2.5 rounded-xl text-xs font-bold text-white shadow-sm transition";
                            reasonSubmitBtn.textContent = "Tolak Pengajuan";
                        }

                        modalReason.classList.remove("hidden");
                        modalReason.classList.add("flex");
                    };

                    closeModalReason.onclick = () => {
                        modalReason.classList.add("hidden");
                        modalReason.classList.remove("flex");
                    };

                    modalReason.onclick = (e) => {
                        if (e.target === modalReason) {
                            modalReason.classList.add("hidden");
                            modalReason.classList.remove("flex");
                        }
                    };
                }
            }

            initLeaveModals();
            document.addEventListener('ajax-container:updated', initLeaveModals);
        })();
    </script>
</x-app-layout>
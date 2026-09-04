<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white font-semibold text-xl tracking-tight">Semua Pengajuan Izin</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- CARD WRAPPER -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden p-5 sm:p-6">
            
            <!-- HEADER INFO -->
            <div class="mb-5">
                <h3 class="text-lg font-medium text-slate-200">Daftar Pengajuan Cuti & Izin</h3>
                <p class="text-xs text-slate-400 mt-1">Gunakan tabel di bawah ini untuk melihat dan memproses pengajuan izin atau cuti karyawan.</p>
            </div>

            <!-- WRAPPER RESPONSIVE TABLE -->
            <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950">
                <table class="min-w-full divide-y divide-slate-800 text-slate-300 text-sm whitespace-nowrap">
                    <thead class="bg-slate-900/80">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Karyawan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Jenis</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Bukti</th>
                            <th class="px-5 py-3.5 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                        @forelse ($leaves as $izin)
                            <tr class="hover:bg-slate-900/40 transition-colors">
                                {{-- KARYAWAN --}}
                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-200">{{ $izin->employee->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $izin->employee->jabatan ?? 'Karyawan' }}</div>
                                </td>

                                {{-- JENIS IZIN --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ ucfirst($izin->jenis_izin) }}
                                    </span>
                                </td>

                                {{-- TANGGAL --}}
                                <td class="px-5 py-4">
                                    <div class="text-sm font-medium text-slate-300">
                                        {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d F Y') }}
                                    </div>
                                    @if ($izin->tanggal_selesai)
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 my-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            <span>s/d</span>
                                            <span class="font-medium text-slate-400">{{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d F Y') }}</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-5 py-4">
                                    @if($izin->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @elseif($izin->status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span>
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- BUKTI --}}
                                <td class="px-5 py-4">
                                    @if ($izin->bukti_path)
                                        <button onclick="openModal('{{ asset('storage/' . $izin->bukti_path) }}')"
                                            class="inline-flex items-center text-indigo-400 hover:text-indigo-300 font-medium text-xs transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-slate-600 text-xs font-medium italic">Tidak Ada</span>
                                    @endif
                                </td>

                                {{-- ================= AKSI ================= --}}
                                <td class="px-5 py-4 text-center align-middle">
                                    @if ($izin->status === 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openReasonModal('approve', {{ $izin->id }})" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-lg shadow-emerald-950/20 active:scale-[0.98] transition-all duration-200">
                                                Terima
                                            </button>

                                            <button onclick="openReasonModal('reject', {{ $izin->id }})" class="bg-rose-600 hover:bg-rose-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-lg shadow-rose-950/20 active:scale-[0.98] transition-all duration-200">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-600 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500 italic">
                                    Belum ada pengajuan izin atau cuti.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="mt-5">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>

    {{-- ================================
    MODAL BUKTI
    ================================ --}}
    <div id="modalBukti" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 rounded-2xl p-6 w-full max-w-xl relative border border-slate-800 shadow-2xl">
            <button id="closeModalBukti" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-slate-200 font-semibold text-lg mb-4">Lampiran Bukti Izin</h3>

            <div id="modalBuktiContent" class="text-center text-slate-400 text-sm overflow-hidden flex items-center justify-center min-h-[200px]">
                Memuat bukti...
            </div>
        </div>
    </div>

    {{-- ================================
    MODAL ALASAN
    ================================ --}}
    <div id="modalReason" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 rounded-2xl p-6 w-full max-w-md relative border border-slate-800 shadow-2xl">
            <button id="closeModalReason" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 id="modalReasonTitle" class="text-slate-200 font-semibold text-lg mb-3">Tulis Alasan</h3>
            <p class="text-slate-400 text-xs mb-4">Berikan catatan atau alasan untuk tindakan persetujuan ini (opsional).</p>

            <form id="reasonForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <textarea name="alasan" rows="3"
                        class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100 placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200"
                        placeholder="Tuliskan alasan (boleh kosong)"></textarea>
                </div>

                <button type="submit" id="reasonSubmitBtn"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg shadow-indigo-950/25 active:scale-[0.98] transition-all duration-200">
                    Kirim Konfirmasi
                </button>
            </form>
        </div>
    </div>

    {{-- ======================================================
    SCRIPT MODAL
    ====================================================== --}}
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
                            modalContent.innerHTML = `<embed src="${fileUrl}" type="application/pdf" class="w-full h-96 rounded-xl border border-slate-800" />`;
                        } else if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
                            modalContent.innerHTML = `<img src="${fileUrl}" class="max-h-96 mx-auto rounded-xl border border-slate-800 object-contain shadow-lg" />`;
                        } else {
                            modalContent.innerHTML = `
                                <div class="flex flex-col items-center justify-center p-4 space-y-3">
                                    <span class="text-slate-500 text-sm">Tidak dapat menampilkan langsung jenis file ini.</span>
                                    <a href="${fileUrl}" target="_blank" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs px-4 py-2 rounded-lg font-medium transition-colors">Unduh File Lampiran</a>
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
                            modalReasonTitle.textContent = "Terima Pengajuan Izin";
                            reasonSubmitBtn.className = "w-full bg-emerald-600 hover:bg-emerald-500 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg shadow-emerald-950/25 active:scale-[0.98] transition-all duration-200";
                            reasonSubmitBtn.textContent = "Setujui Pengajuan";
                        } else {
                            reasonForm.action = `/izin/${id}/reject`;
                            modalReasonTitle.textContent = "Tolak Pengajuan Izin";
                            reasonSubmitBtn.className = "w-full bg-rose-600 hover:bg-rose-500 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg shadow-rose-950/25 active:scale-[0.98] transition-all duration-200";
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

            // Run once
            initLeaveModals();

            // Run on AJAX update
            document.addEventListener('ajax-container:updated', initLeaveModals);
        })();
    </script>
</x-app-layout>
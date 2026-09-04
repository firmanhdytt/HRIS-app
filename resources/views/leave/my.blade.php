<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white font-semibold text-xl">Izin Saya</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4">

        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-800 text-slate-200 text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-950 text-slate-400">
                        <tr class="text-left font-semibold uppercase tracking-wider">
                            <th class="px-4 py-3.5">Jenis</th>
                            <th class="px-4 py-3.5">Tanggal</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5">Keterangan Admin</th>
                            <th class="px-4 py-3.5">Bukti</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-800/60">
                        @foreach ($izin as $row)
                            <tr class="hover:bg-slate-800/30 transition-colors">

                                <td class="px-4 py-3.5 font-medium text-slate-300">{{ $row->jenis_izin }}</td>

                                <td class="px-4 py-3.5 text-slate-400">
                                    {{ $row->tanggal_mulai }}
                                    @if($row->tanggal_selesai)
                                        s/d {{ $row->tanggal_selesai }}
                                    @endif
                                </td>

                                <td class="px-4 py-3.5">
                                    @if($row->status == 'pending')
                                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded-md font-semibold text-[10px] inline-block">
                                            Pending
                                        </span>
                                    @elseif($row->status == 'approved')
                                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-md font-semibold text-[10px] inline-block">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded-md font-semibold text-[10px] inline-block">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5 text-slate-400">
                                    {{ $row->keterangan_admin ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    @if($row->bukti_path)
                                        <button onclick="openModal('{{ asset('storage/' . $row->bukti_path) }}')"
                                            class="text-indigo-400 hover:text-indigo-300 font-semibold text-xs flex items-center gap-1 transition-colors">
                                            Lihat Bukti →
                                        </button>
                                    @else
                                        <span class="text-slate-600">-</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ================================
    MODAL BUKTI IZIN
    ================================ --}}
    <div id="modalBukti" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800
                w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden relative p-4 animate-pop">

            <!-- TOMBOL CLOSE -->
            <button id="closeModalBukti" class="absolute top-4 right-4 text-slate-400 hover:text-slate-200 text-2xl font-bold leading-none p-1 transition-colors">
                &times;
            </button>

            <!-- ISI MODAL -->
            <div class="p-4 overflow-y-auto mt-6" id="modalBuktiContent" style="max-height: 70vh;">
                <p class="text-slate-450 text-sm">Memuat bukti...</p>
            </div>

        </div>
    </div>


    {{-- ======================================================
    SCRIPT MODAL
    ======================================================= --}}
    <script>
        (function() {
            function initMyLeaveModals() {
                const modal = document.getElementById('modalBukti');
                const closeModalBtn = document.getElementById('closeModalBukti');
                const content = document.getElementById('modalBuktiContent');

                if (modal && closeModalBtn) {
                    window.openModal = function (fileUrl) {
                        content.innerHTML = "Memuat bukti...";
                        const ext = fileUrl.split('.').pop().toLowerCase();

                        if (ext === "pdf") {
                            content.innerHTML = `
                                <embed src="${fileUrl}" type="application/pdf"
                                       class="w-full h-[70vh] rounded border border-gray-700" />
                            `;
                        }
                        else if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
                            content.innerHTML = `
                                <img src="${fileUrl}"
                                     class="max-h-[70vh] mx-auto rounded border border-gray-700" />
                            `;
                        }
                        else {
                            content.innerHTML = `
                                <p class="text-slate-400 text-sm">Tidak dapat menampilkan file.</p>
                                <a href="${fileUrl}" target="_blank" class="text-blue-400 underline text-xs">
                                    Download File
                                </a>
                            `;
                        }

                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeModal() {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = 'auto';
                    }

                    closeModalBtn.onclick = closeModal;
                    modal.onclick = e => {
                        if (e.target === modal) closeModal();
                    };
                }
            }

            // Run once
            initMyLeaveModals();

            // Run on AJAX update
            document.addEventListener('ajax-container:updated', initMyLeaveModals);
        })();
    </script>


</x-app-layout>
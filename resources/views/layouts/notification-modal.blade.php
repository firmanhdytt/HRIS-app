<!-- ===========================
        MODAL NOTIFIKASI
=========================== -->

<!-- OVERLAY -->
<div id="notifModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">

    <!-- MODAL WRAPPER -->
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200
                w-full max-w-lg h-[480px] flex flex-col overflow-hidden animate-slideUp">

        <!-- HEADER -->
        <div class="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h2 class="text-slate-800 font-bold text-base">Semua Notifikasi</h2>

            <div class="flex items-center gap-3">
                <!-- Hapus Semua -->
                <button type="button" class="bg-rose-600 hover:bg-rose-700 px-3 py-1.5 rounded-xl text-white text-xs font-semibold shadow-xs transition-colors openConfirm"
                    data-action="{{ route('notifications.clearAll') }}">
                    Hapus Semua
                </button>

                <!-- Tombol Close -->
                <button id="closeNotifModal" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none p-1 transition-colors">&times;</button>
            </div>
        </div>

        <!-- LIST NOTIFIKASI (SCROLL) -->
        <div class="p-4 space-y-3 overflow-y-auto flex-1 bg-slate-50/50">

            @if(isset($notifAll) && count($notifAll) > 0)
                @foreach ($notifAll as $notif)
                    @php
                        $userId = auth()->id();
                        $readBy = is_array($notif->read_by)
                            ? $notif->read_by
                            : json_decode($notif->read_by, true);

                        $alreadyRead = is_array($readBy) && in_array($userId, $readBy);
                    @endphp

                    <div class="notif-item p-4 rounded-xl border flex justify-between items-start gap-4 transition-all duration-300
                                {{ $alreadyRead ? 'bg-white border-slate-200 text-slate-500' : 'bg-indigo-50/40 border-indigo-200 text-slate-800 shadow-2xs' }}">

                        <div class="flex-1">
                            <p class="text-sm font-medium leading-relaxed">{{ $notif->description }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                <span class="inline-block w-1.5 h-1.5 rounded-full {{ $alreadyRead ? 'bg-slate-400' : 'bg-indigo-600' }}"></span>
                                {{ $notif->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 border border-slate-200 rounded-lg text-slate-600 text-xs font-medium transition-all openConfirm"
                            data-action="{{ route('notifications.delete', $notif->id) }}">
                            Hapus
                        </button>

                    </div>
                @endforeach
            @else
                <div class="text-center py-12 text-slate-400">
                    <p class="text-3xl mb-2">🔔</p>
                    <p class="text-sm">Belum ada notifikasi saat ini.</p>
                </div>
            @endif

        </div>

    </div>

    <!-- MODAL KONFIRMASI NOTIFIKASI -->
    <div id="confirmModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">

        <div class="bg-white p-6 rounded-2xl shadow-2xl border border-slate-200 w-full max-w-sm">
            <h2 class="text-slate-900 text-center font-bold mb-2 text-lg">Konfirmasi</h2>

            <p class="text-slate-600 text-sm text-center mb-5" id="confirmMessage">
                Yakin ingin menghapus notifikasi ini?
            </p>

            <div class="flex justify-center gap-3">
                <button id="cancelConfirm"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </button>
                <button id="yesConfirm" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md transition-all">
                    Hapus
                </button>
            </div>
        </div>

    </div>

</div>

<script>
(function() {
    function initNotifications() {
        const notifModal = document.getElementById('notifModal');
        const closeNotifModal = document.getElementById('closeNotifModal');

        const bellDesktop = document.getElementById('notifBell');
        const bellMobile  = document.getElementById('notifBellMobile');

        const confirmModal = document.getElementById('confirmModal');
        const cancelConfirm = document.getElementById('cancelConfirm');
        const yesConfirm = document.getElementById('yesConfirm');
        let currentAction = null;
        let notifSeenOnce = false;

        if (!notifModal) return;

        function markNotificationsAsRead() {
            fetch("{{ route('notifications.markRead') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            }).then(() => {
                const bd = document.getElementById('notifCountDesktop');
                const bm = document.getElementById('notifCountMobile');
                if (bd) bd.remove();
                if (bm) bm.remove();
                notifSeenOnce = true;
            });
        }

        function openNotifModal() {
            notifModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            markNotificationsAsRead();
        }

        if (bellDesktop) {
            bellDesktop.onclick = openNotifModal;
        }
        if (bellMobile) {
            bellMobile.onclick = openNotifModal;
        }

        closeNotifModal?.addEventListener('click', () => {
            notifModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        notifModal.addEventListener('click', e => {
            if (e.target === notifModal) {
                notifModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        document.querySelectorAll('.openConfirm').forEach(btn => {
            btn.onclick = () => {
                currentAction = btn.getAttribute('data-action');
                confirmModal.classList.remove('hidden');
            };
        });

        cancelConfirm?.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
            currentAction = null;
        });

        if (yesConfirm) {
            yesConfirm.onclick = () => {
                if (currentAction) {
                    fetch(currentAction, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    }).then(() => {
                        confirmModal.classList.add('hidden');
                        if (window.navigateTo) {
                            window.navigateTo(window.location.href);
                        } else {
                            window.location.reload();
                        }
                    }).catch(err => {
                        console.error("Failed to process notification action:", err);
                        window.location.reload();
                    });
                }
            };
        }
    }

    initNotifications();
    document.addEventListener('ajax-container:updated', initNotifications);
})();
</script>

<!-- ===========================
        MODAL NOTIFIKASI
=========================== -->

<!-- OVERLAY -->
<div id="notifModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">

    <!-- MODAL WRAPPER -->
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800
                w-full max-w-lg h-[480px] flex flex-col overflow-hidden animate-slideUp">

        <!-- HEADER -->
        <div class="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50 backdrop-blur-md">
            <h2 class="text-slate-200 font-semibold text-lg">Semua Notifikasi</h2>

            <div class="flex items-center gap-3">
                <!-- Hapus Semua -->
                <button type="button" class="bg-rose-600/90 hover:bg-rose-600 px-3 py-1.5 rounded-lg text-white text-xs font-semibold shadow-md transition-colors openConfirm"
                    data-action="{{ route('notifications.clearAll') }}">
                    Hapus Semua
                </button>

                <!-- Tombol Close -->
                <button id="closeNotifModal" class="text-slate-400 hover:text-slate-200 text-2xl font-bold leading-none p-1 transition-colors">&times;</button>
            </div>
        </div>

        <!-- LIST NOTIFIKASI (SCROLL) -->
        <div class="p-4 space-y-3 overflow-y-auto flex-1 bg-slate-950/30">

            @foreach ($notifAll as $notif)

                @php
                    $userId = auth()->id();
                    $readBy = is_array($notif->read_by)
                        ? $notif->read_by
                        : json_decode($notif->read_by, true);

                    $alreadyRead = is_array($readBy) && in_array($userId, $readBy);
                @endphp

                <div class="notif-item p-4 rounded-xl border flex justify-between items-start gap-4 transition-all duration-300
                            {{ $alreadyRead ? 'bg-slate-800/40 border-slate-800/80 text-slate-400' : 'bg-slate-850 border-indigo-500/20 text-slate-200 shadow-sm shadow-indigo-500/5' }}">

                    <div class="flex-1">
                        <p class="text-sm font-medium leading-relaxed">{{ $notif->description }}</p>
                        <p class="text-[10px] text-slate-500 mt-1 flex items-center gap-1">
                            <span class="inline-block w-1.5 h-1.5 rounded-full {{ $alreadyRead ? 'bg-slate-600' : 'bg-indigo-500' }}"></span>
                            {{ $notif->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <button type="button" class="px-3 py-1.5 bg-slate-800 hover:bg-rose-950/40 hover:text-rose-400 border border-slate-700 hover:border-rose-500/30 rounded-lg text-slate-300 text-xs font-medium transition-all openConfirm"
                        data-action="{{ route('notifications.delete', $notif->id) }}">
                        Hapus
                    </button>

                </div>

            @endforeach

        </div>

    </div>

    <!-- ===========================
        MODAL KONFIRMASI
    =========================== -->
    <div id="confirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">

        <div class="bg-slate-900 p-6 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-sm animate-pop">
            <h2 class="text-slate-100 text-center font-bold mb-2 text-lg">Konfirmasi</h2>

            <p class="text-slate-400 text-sm text-center mb-5" id="confirmMessage">
                Yakin ingin menghapus notifikasi ini?
            </p>

            <div class="flex justify-center gap-3">
                <button id="cancelConfirm"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-lg text-sm font-semibold transition-all">
                    Batal
                </button>
                <button id="yesConfirm" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-sm font-semibold shadow-md transition-all">
                    Hapus
                </button>
            </div>
        </div>

    </div>

</div>

<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-slideUp {
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<!-- ===========================
        JAVASCRIPT FINAL
=========================== -->
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

        function updateNotifBackground() {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.style.transition = "background-color .10s ease";
                item.classList.remove('bg-gray-800');
                item.classList.add('bg-gray-700');
            });
        }

        function openNotifModal() {
            notifModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            markNotificationsAsRead();
            if (notifSeenOnce) {
                updateNotifBackground();
            }
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

        // Use event delegation for openConfirm
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

    // Run once
    initNotifications();

    // Re-run on AJAX update so that buttons get bound correctly
    document.addEventListener('ajax-container:updated', initNotifications);
})();
</script>


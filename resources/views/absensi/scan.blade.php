<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Scan QR Absensi</h1>
            <a href="{{ route('absensi.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-semibold">
                ← Kembali ke Option Absensi
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto my-8 px-4">
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs text-center relative">
            <h2 class="text-base font-bold text-slate-900 mb-1">Arahkan Kode QR ke Kamera</h2>
            <p class="text-slate-500 text-xs mb-6 leading-relaxed">Kamera akan aktif secara otomatis untuk memindai kartu QR Code presensi karyawan.</p>

            <div id="reader" class="mx-auto w-full max-w-[380px] overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"></div>
        </div>

        <!-- NOTIFICATION POPUP -->
        <div id="notif" class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden z-[9999]">
            <div id="notif-content" class="p-4 bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-200 text-center"></div>
        </div>

        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

        <script>
            const notif = document.getElementById('notif');
            const notifContent = document.getElementById('notif-content');
            let last = null;
            let notifTimeout;
            let scanner;

            function showNotif(message, employeeName = '', type = 'success') {
                clearTimeout(notifTimeout);

                const styles = {
                    success: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    error: 'bg-rose-50 text-rose-800 border-rose-200',
                    info: 'bg-amber-50 text-amber-800 border-amber-200'
                };

                notifContent.className =
                    `inline-block px-8 py-5 rounded-2xl shadow-2xl border text-center ${styles[type] || styles.success}`;

                notifContent.innerHTML = `
                    <div class="font-extrabold text-base mb-1">${message}</div>
                    ${employeeName ? `<div class="text-xs font-medium opacity-90">Karyawan: <span class="font-bold">${employeeName}</span></div>` : ''}
                `;

                notif.classList.remove('hidden');

                notifTimeout = setTimeout(() => {
                    notif.classList.add('hidden');
                    scanner.render(onScanSuccess);
                }, 2000);
            }

            scanner = new Html5QrcodeScanner("reader", {
                fps: 20,
                qrbox: { width: 280, height: 280 }
            });

            function onScanSuccess(decodedText) {
                if (decodedText === last) return;
                last = decodedText;

                let matches = decodedText.match(/^employee ID:\s*(.+?)\s*-\s*(.+)$/i);
                if (!matches) {
                    showNotif("Format QR tidak dikenali", "", "error");
                    setTimeout(() => last = null, 1500);
                    return;
                }

                let employeeId = matches[1].trim();
                let employeeName = matches[2].trim();

                scanner.clear().then(() => {
                    fetch("{{ route('absensi.scan.process') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({ qr_code: employeeId })
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const error = await res.json().catch(() => null);
                            showNotif(error?.message ?? "Gagal memproses absensi", "", "error");
                            return;
                        }

                        const data = await res.json();
                        showNotif(data.message, employeeName, data.status);
                    })
                    .catch(() => {
                        showNotif("Kesalahan jaringan atau server", "", "error");
                    });

                }).catch(err => console.error("Gagal stop scanner:", err));
            }

            scanner.render(onScanSuccess);
        </script>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Scan QR Absensi</h2>
    </x-slot>

    <div class="max-w-xl mx-auto my-10 px-4">

        <div class="bg-slate-900 p-8 rounded-2xl border border-slate-800 shadow-2xl text-center relative">
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">Arahkan QR Code ke kamera. Kamera akan aktif secara otomatis.</p>

            <div id="reader" class="mx-auto w-full max-w-[380px] overflow-hidden rounded-xl border border-slate-800/80 bg-slate-950"></div>
        </div>

        <!-- =======================
             NOTIFICATION POPUP
        ======================== -->
        <div id="notif"
             class="fixed inset-0 flex items-center justify-center  bg-opacity-40 hidden z-[9999]">
            <div id="notif-content"
                 class="p-4 bg-gray-800 text-white rounded-lg shadow-lg text-center"></div>
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

                const colors = {
                    success: 'bg-green-600',
                    error: 'bg-red-600',
                    info: 'bg-yellow-500'
                };

                notifContent.className =
                    `inline-block px-6 py-3 rounded-lg shadow-lg text-white text-center ${colors[type]}`;

                notifContent.innerHTML = `
                    <div class="font-bold text-lg mb-2">${message}</div>
                    ${employeeName ? `<div class="text-sm opacity-80">Karyawan: <span class="font-semibold">${employeeName}</span></div>` : ''}
                `;

                notif.classList.remove('hidden');

                // Setelah 4 detik → tutup popup → aktifkan kamera lagi
                notifTimeout = setTimeout(() => {
                    notif.classList.add('hidden');
                    scanner.render(onScanSuccess);
                }, 2000);
            }

            // ===========================
            // SCANNER SETUP
            // ===========================
            scanner = new Html5QrcodeScanner("reader", {
                fps: 20,
                qrbox: { width: 300, height: 300 }
            });

            function onScanSuccess(decodedText) {
                if (decodedText === last) return;
                last = decodedText;

                console.log("QR Scanned:", decodedText);

                // Format QR: employee ID: EMP002 - John Doe
                let matches = decodedText.match(/^employee ID:\s*(.+?)\s*-\s*(.+)$/i);
                if (!matches) {
                    showNotif("Format QR tidak dikenali", "", "error");
                    setTimeout(() => last = null, 1500);
                    return;
                }

                let employeeId = matches[1].trim();
                let employeeName = matches[2].trim();

                // Hentikan kamera sebelum request
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

                        // Jika server balas selain 200
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

            // Mulai scanner
            scanner.render(onScanSuccess);
        </script>

    </div>
</x-app-layout>

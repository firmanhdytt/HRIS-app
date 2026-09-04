<x-app-layout>
    <x-slot name="header">
        <h1 class="text-slate-100 text-xl font-semibold leading-tight">Dashboard HRIS</h1>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto space-y-6">

        @php
            $currentUser = Auth::user();
            $myEmployee = $currentUser->employee;
            $myAbsensi = null;
            $myIzin = null;
            
            if ($myEmployee) {
                $todayStr = \Carbon\Carbon::today()->toDateString();
                $myAbsensi = \App\Models\Absensi::where('id_karyawan', $myEmployee->employee_id)
                    ->where('tanggal', $todayStr)
                    ->first();
                    
                $myIzin = \App\Models\LeaveRequest::where('employee_id', $myEmployee->employee_id)
                    ->where('status', 'approved')
                    ->where(function ($q) use ($todayStr) {
                        $q->whereDate('tanggal_mulai', '<=', $todayStr)
                          ->whereDate('tanggal_selesai', '>=', $todayStr);
                    })
                    ->first();
            }
        @endphp

        @if($myEmployee)
            <!-- ========================= QUICK ATTENDANCE WIDGET ========================= -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950/40 border border-slate-800/80 rounded-2xl p-5 shadow-xl relative overflow-hidden transition-all duration-300 hover:border-slate-700/80">
                <!-- Background ambient glow -->
                <div class="absolute -right-16 -top-16 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-4">
                        <!-- User Profile Photo / Initial -->
                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-700/50 bg-slate-800 flex-shrink-0 flex items-center justify-center shadow-md">
                            @if($currentUser->photo)
                                <img src="{{ asset('storage/' . $currentUser->photo) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg font-bold text-indigo-400">{{ substr($currentUser->name, 0, 2) }}</span>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-[10px] font-semibold text-indigo-400 uppercase tracking-wider">Absensi Mandiri</p>
                            <h2 class="text-base font-bold text-slate-100 mt-0.5">{{ $myEmployee->name }}</h2>
                            <p class="text-[11px] text-slate-400 mt-0.5">ID: {{ $myEmployee->employee_id }} | {{ $myEmployee->department ?? 'Staff' }}</p>
                        </div>
                    </div>
                    
                    <!-- Status & Times & Buttons -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <div class="bg-slate-950/60 border border-slate-850 px-3.5 py-1.5 rounded-xl flex flex-col items-center min-w-[70px]">
                            <p class="text-[9px] text-slate-400 uppercase font-semibold">Masuk</p>
                            <p class="text-xs font-bold text-slate-200 mt-0.5">
                                {{ $myAbsensi && $myAbsensi->jam_masuk ? \Carbon\Carbon::parse($myAbsensi->jam_masuk)->format('H:i') : '--:--' }}
                            </p>
                        </div>
                        
                        <div class="bg-slate-950/60 border border-slate-850 px-3.5 py-1.5 rounded-xl flex flex-col items-center min-w-[70px]">
                            <p class="text-[9px] text-slate-400 uppercase font-semibold">Keluar</p>
                            <p class="text-xs font-bold text-slate-200 mt-0.5">
                                {{ $myAbsensi && $myAbsensi->jam_keluar ? \Carbon\Carbon::parse($myAbsensi->jam_keluar)->format('H:i') : '--:--' }}
                            </p>
                        </div>
                        
                        <!-- Quick Actions Button -->
                        <div class="flex items-center">
                            @if($myIzin)
                                <span class="px-4 py-2 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-xl text-xs font-bold shadow-sm">
                                    Izin Approved
                                </span>
                            @elseif(!$myAbsensi || !$myAbsensi->jam_masuk)
                                <form method="POST" action="{{ route('absensi.quick-store') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $myEmployee->employee_id }}">
                                    <input type="hidden" name="tipe" value="masuk">
                                    <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-emerald-600/10 transition active:scale-[0.97] flex items-center gap-1.5">
                                        Absen Masuk
                                    </button>
                                </form>
                            @elseif(!$myAbsensi->jam_keluar)
                                <form method="POST" action="{{ route('absensi.quick-store') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $myEmployee->employee_id }}">
                                    <input type="hidden" name="tipe" value="keluar">
                                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-600/10 transition active:scale-[0.97] flex items-center gap-1.5">
                                        Absen Keluar
                                    </button>
                                </form>
                            @else
                                <span class="px-4 py-2 bg-slate-800/80 text-slate-400 border border-slate-700/50 rounded-xl text-xs font-semibold shadow-sm">
                                    Absensi Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- ========================= KPI CARDS ========================= -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

            <!-- Masuk Hari Ini -->
            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800/80 shadow-lg flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:border-slate-700/80">
                <div>
                    <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Masuk</p>
                    <p class="text-2xl font-bold text-slate-100 mt-1">{{ $masukToday }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-lg text-emerald-400 border border-emerald-500/20">
                    🟢
                </div>
            </div>

            <!-- Keluar Hari Ini -->
            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800/80 shadow-lg flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:border-slate-700/80">
                <div>
                    <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Keluar</p>
                    <p class="text-2xl font-bold text-slate-100 mt-1">{{ $keluarToday }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-lg text-blue-400 border border-blue-500/20">
                    🔵
                </div>
            </div>

            <!-- Telat Hari Ini -->
            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800/80 shadow-lg flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:border-slate-700/80">
                <div>
                    <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Telat</p>
                    <p class="text-2xl font-bold text-slate-100 mt-1">{{ $lateToday }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-lg text-amber-400 border border-amber-500/20">
                    ⏰
                </div>
            </div>

            <!-- Izin Hari Ini -->
            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800/80 shadow-lg flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:border-slate-700/80">
                <div>
                    <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Izin</p>
                    <p class="text-2xl font-bold text-slate-100 mt-1">{{ $izinToday }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-lg text-indigo-400 border border-indigo-500/20">
                    📄
                </div>
            </div>

            <!-- Absen Hari Ini -->
            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800/80 shadow-lg flex justify-between items-center transition-all duration-300 hover:scale-[1.02] hover:border-slate-700/80">
                <div>
                    <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider font-semibold tracking-wider">Absen</p>
                    <p class="text-2xl font-bold text-slate-100 mt-1">{{ $absenToday }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-lg text-rose-400 border border-rose-500/20">
                    ❌
                </div>
            </div>

            <!-- Pengajuan Izin -->
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-5 flex flex-col justify-between transition-all duration-300 hover:scale-[1.02] hover:border-slate-700/80 col-span-2 md:col-span-1">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-xs font-semibold text-slate-300">Pengajuan Izin</h2>
                    @if($pendingCount > 0)
                        <span class="bg-amber-500 text-slate-950 px-2 py-0.5 rounded-full text-[9px] font-bold">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </div>

                <div class="flex-1">
                    @if ($pendingCount == 0)
                        <p class="text-slate-500 text-[11px]">
                            Tidak ada pengajuan.
                        </p>
                    @else
                        <p class="text-slate-400 text-[11px]">
                            Ada <span class="font-bold text-slate-200">{{ $pendingCount }}</span> pengajuan izin.
                        </p>
                    @endif
                </div>

                <div class="flex justify-end mt-2">
                    <a href="{{ route('leave.admin') }}" class="text-indigo-400 text-[11px] font-semibold hover:text-indigo-300 transition-colors">
                        Lihat →
                    </a>
                </div>
            </div>

        </div>

        <!-- ========================= GRID (LEFT + RIGHT) ========================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT SIDE (CHART) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- ========================== CHART CARD ========================== -->
                <div id="grafikSection" class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-semibold text-slate-200">Grafik Kehadiran</h2>

                        {{-- FILTER FORM --}}
                        <form id="filterForm" method="GET" class="w-32">
                            <select name="filter" id="filterSelect"
                                class="rounded-xl px-3 py-1.5 bg-slate-950 text-slate-200 border border-slate-800 hover:border-slate-700 text-xs font-semibold w-full focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-colors">
                                <option value="daily" @selected($filter == 'daily')>Harian</option>
                                <option value="weekly" @selected($filter == 'weekly')>Mingguan</option>
                                <option value="monthly" @selected($filter == 'monthly')>Bulanan</option>
                            </select>
                        </form>
                    </div>

                    <div class="w-full" style="height: 240px;">
                        <canvas id="attendanceChart" style="height: 100%!important;"></canvas>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE (STATUS KEHADIRAN) -->
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg">
                <h2 class="text-sm font-semibold text-slate-200 mb-4">Kehadiran Hari Ini</h2>

                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="min-w-full divide-y divide-slate-800 text-slate-200 text-xs whitespace-nowrap">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-800 text-left">
                                <th class="pb-3 pr-3 font-semibold">Nama</th>
                                <th class="pb-3 px-3 font-semibold">Status</th>
                                <th class="pb-3 px-3 font-semibold">Masuk</th>
                                <th class="pb-3 px-3 font-semibold">Keluar</th>
                                <th class="pb-3 pl-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800/60">
                            @foreach ($kehadiranList as $row)
                                @php
                                    $today = \Carbon\Carbon::today()->toDateString();
                                    $abs = $row->absensi->first();
                                    $jam_masuk = $abs->jam_masuk ?? null;
                                    $jam_keluar = $abs->jam_keluar ?? null;

                                    $izin = \App\Models\LeaveRequest::where('employee_id', $row->employee_id)
                                        ->where('status', 'approved')
                                        ->where(function ($q) use ($today) {
                                            $q->whereDate('tanggal_mulai', '<=', $today)
                                              ->whereDate('tanggal_selesai', '>=', $today);
                                        })
                                        ->orWhere(function($q) use ($today, $row) {
                                            $q->where('employee_id', $row->employee_id)
                                              ->whereNull('tanggal_selesai')
                                              ->whereDate('tanggal_mulai', $today)
                                              ->where('status', 'approved');
                                        })
                                        ->first();

                                    if ($izin) {
                                        $status = 'Izin';
                                        $style = 'bg-amber-500/10 text-amber-400 border border-amber-500/20';
                                    } elseif (!$abs) {
                                        $status = 'Absen';
                                        $style = 'bg-rose-500/10 text-rose-400 border border-rose-500/20';
                                    } elseif ($jam_masuk > "08:15:00") {
                                        $status = 'Telat';
                                        $style = 'bg-blue-500/10 text-blue-400 border border-blue-500/20';
                                    } else {
                                        $status = 'Hadir';
                                        $style = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                                    }

                                    $showAction = false;
                                    if (Auth::user()->isAdmin()) {
                                        $showAction = true;
                                    } elseif ($myEmployee && $myEmployee->employee_id === $row->employee_id) {
                                        $showAction = true;
                                    }
                                @endphp

                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 pr-3 font-medium text-slate-300">{{ $row->name }}</td>

                                    <td class="py-3 px-3">
                                        <span class="px-2.5 py-1 text-[10px] rounded-lg font-semibold inline-block {{ $style }}">
                                            {{ $status }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-3 text-slate-400">
                                        {{ $jam_masuk ? \Carbon\Carbon::parse($jam_masuk)->format('H:i') : '-' }}
                                    </td>

                                    <td class="py-3 px-3 text-slate-400">
                                        {{ $jam_keluar ? \Carbon\Carbon::parse($jam_keluar)->format('H:i') : '-' }}
                                    </td>

                                    <td class="py-3 pl-3 text-right">
                                        @if ($showAction)
                                            @if ($izin)
                                                <span class="text-slate-500 text-[10px]">Izin</span>
                                            @elseif (!$jam_masuk)
                                                <form method="POST" action="{{ route('absensi.quick-store') }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="employee_id" value="{{ $row->employee_id }}">
                                                    <input type="hidden" name="tipe" value="masuk">
                                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-2 py-1 rounded-lg text-[9px] shadow-sm transition active:scale-[0.97]">
                                                        Masuk
                                                    </button>
                                                </form>
                                            @elseif (!$jam_keluar)
                                                <form method="POST" action="{{ route('absensi.quick-store') }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="employee_id" value="{{ $row->employee_id }}">
                                                    <input type="hidden" name="tipe" value="keluar">
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-2 py-1 rounded-lg text-[9px] shadow-sm transition active:scale-[0.97]">
                                                        Keluar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-slate-500 text-[10px]">Selesai</span>
                                            @endif
                                        @else
                                            <span class="text-slate-600 text-[10px]">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ============================== JS CHART ============================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- ================== SCRIPT AGAR TIDAK BALIK KE ATAS ================== --}}
    <script>
        document.getElementById('filterSelect').addEventListener('change', function () {
            const form = document.getElementById('filterForm');
            localStorage.setItem('scrollPosition', document.getElementById('grafikSection').offsetTop);
            form.submit();
        });

        window.addEventListener('load', function () {
            const saved = localStorage.getItem('scrollPosition');
            if (saved) {
                window.scrollTo({ top: saved - 20, behavior: "smooth" });
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>

    {{-- ============================== GENERATE CHART ============================== --}}
    <script>
        const ctx = document.getElementById('attendanceChart');

        @if ($filter == 'daily')
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Hadir', 'Telat', 'Izin', 'Absen'],
                    datasets: [{
                        data: [
                            {{ $chartData['hadir'] }},
                            {{ $chartData['telat'] }},
                            {{ $chartData['izin'] }},
                            {{ $chartData['absen'] }},
                        ],
                        backgroundColor: ['#22c55e', '#3b82f6', '#fbbf24', '#ef4444'],
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { color: '#e2e8f0' } } }
                }
            });
        @else
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Hadir',
                            data: @json($hadirLine),
                            borderColor: '#22c55e',
                            backgroundColor: '#22c55e33',
                            tension: 0.4
                        },
                        {
                            label: 'Telat',
                            data: @json($telatLine),
                            borderColor: '#3b82f6',
                            backgroundColor: '#3b82f633',
                            tension: 0.4
                        },
                        {
                            label: 'Izin',
                            data: @json($izinLine),
                            borderColor: '#fbbf24',
                            backgroundColor: '#fbbf2433',
                            tension: 0.4
                        },
                        {
                            label: 'Absen',
                            data: @json($absenLine),
                            borderColor: '#ef4444',
                            backgroundColor: '#ef444433',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { color: '#e2e8f0' } } },
                    scales: {
                        x: { ticks: { color: '#e2e8f0' }},
                        y: { ticks: { color: '#e2e8f0' }}
                    }
                }
            });
        @endif
    </script>

    <!-- ==========================================
          MODAL KONFIRMASI ABSENSI CEPAT
    ========================================== -->
    <div id="quickAbsenConfirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div class="bg-slate-900 p-6 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-sm transform scale-95 transition-all duration-300 opacity-0" id="quickAbsenModalContent">
            <h2 class="text-slate-100 text-center font-bold mb-2 text-lg" id="quickAbsenTitle">Konfirmasi Absensi</h2>
            
            <p class="text-slate-400 text-sm text-center mb-5" id="quickAbsenMessage">
                Apakah Anda yakin ingin melakukan absensi?
            </p>
            
            <div class="flex justify-center gap-3">
                <button id="closeQuickAbsenModal" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-lg text-sm font-semibold transition-all">
                    Batal
                </button>
                <button id="confirmQuickAbsenSubmit" class="px-4 py-2 rounded-lg text-sm font-semibold shadow-md transition-all text-white">
                    Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            function initQuickAbsenForms() {
                const modal = document.getElementById('quickAbsenConfirmModal');
                const modalContent = document.getElementById('quickAbsenModalContent');
                const titleEl = document.getElementById('quickAbsenTitle');
                const messageEl = document.getElementById('quickAbsenMessage');
                const cancelBtn = document.getElementById('closeQuickAbsenModal');
                const confirmBtn = document.getElementById('confirmQuickAbsenSubmit');
                
                let activeForm = null;

                if (!modal) return;

                const forms = document.querySelectorAll('form[action*="quick-store"]');
                forms.forEach(form => {
                    form.onsubmit = (e) => {
                        if (form.dataset.confirmed === 'true') {
                            delete form.dataset.confirmed;
                            return;
                        }
                        
                        e.preventDefault();
                        activeForm = form;
                        
                        const tipeInput = form.querySelector('input[name="tipe"]');
                        const tipe = tipeInput ? tipeInput.value : 'masuk';
                        const nameCell = form.closest('tr') ? form.closest('tr').querySelector('td').textContent.trim() : null;
                        
                        if (tipe === 'masuk') {
                            titleEl.textContent = 'Konfirmasi Absen Masuk';
                            messageEl.textContent = nameCell 
                                ? `Apakah Anda yakin ingin mencatat Absen Masuk untuk ${nameCell}?` 
                                : 'Apakah Anda yakin ingin melakukan Absen Masuk hari ini?';
                            confirmBtn.className = 'px-4 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-lg text-sm font-semibold shadow-md transition-all text-white';
                        } else {
                            titleEl.textContent = 'Konfirmasi Absen Keluar';
                            messageEl.textContent = nameCell 
                                ? `Apakah Anda yakin ingin mencatat Absen Keluar untuk ${nameCell}?` 
                                : 'Apakah Anda yakin ingin melakukan Absen Keluar hari ini?';
                            confirmBtn.className = 'px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-sm font-semibold shadow-md transition-all text-white';
                        }
                        
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        setTimeout(() => {
                            modalContent.classList.remove('opacity-0', 'scale-95');
                            modalContent.classList.add('opacity-100', 'scale-100');
                        }, 50);
                    };
                });

                function closeModal() {
                    modalContent.classList.remove('opacity-100', 'scale-100');
                    modalContent.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        activeForm = null;
                    }, 200);
                }

                cancelBtn.onclick = closeModal;
                modal.onclick = (e) => {
                    if (e.target === modal) closeModal();
                };

                confirmBtn.onclick = () => {
                    if (activeForm) {
                        const formToSubmit = activeForm;
                        closeModal();
                        
                        formToSubmit.dataset.confirmed = 'true';
                        if (typeof formToSubmit.requestSubmit === 'function') {
                            formToSubmit.requestSubmit();
                        } else {
                            formToSubmit.submit();
                        }
                    }
                };
            }

            // Init on load
            initQuickAbsenForms();

            // Re-init on AJAX page updates
            document.addEventListener('ajax-container:updated', initQuickAbsenForms);
        })();
    </script>

</x-app-layout>

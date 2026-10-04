<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS System - Solusi Manajemen Karyawan & Payroll</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS & Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="h-screen bg-slate-50 text-slate-800 flex flex-col justify-between selection:bg-indigo-500 selection:text-white relative overflow-hidden">
    
    <!-- Ambient Color Glowing Gradients -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-[350px] h-[350px] bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-[350px] h-[350px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- HEADER / NAVIGATION (Top Bar) -->
    <header class="w-full max-w-6xl mx-auto px-6 py-4 flex items-center justify-between z-10 shrink-0">
        <!-- Logo Brand -->
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-600/25">
                H
            </div>
            <div class="flex items-center gap-2">
                <span class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900">HRIS<span class="text-indigo-600">System</span></span>
                <span class="px-2 py-0.5 text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full">v1.0</span>
            </div>
        </div>

        <!-- Tombol Login di Atas Kanan -->
        <div>
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition-all duration-200">
                    <span>Ke Dashboard</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition-all duration-200">
                    <span>Login</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
            @endauth
        </div>
    </header>

    <!-- MAIN HERO SECTION (FIXED FIT VIEWPORT 100VH - TANPA SCROLL) -->
    <main class="w-full max-w-5xl mx-auto px-6 flex-1 flex flex-col justify-center items-center text-center z-10 space-y-4 sm:space-y-5 my-auto">
        
        <!-- Tagline Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-ping"></span>
            Platform Terpadu Manajemen SDM & Payroll
        </div>

        <!-- Headline Utama -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight max-w-3xl mx-auto">
            Kelola SDM & Payroll Perusahaan <span class="text-indigo-600">Lebih Cerdas & Efisien</span>
        </h1>

        <!-- Pengenalan Sistem Ringkas -->
        <p class="text-slate-600 text-xs sm:text-sm md:text-base leading-relaxed max-w-xl mx-auto">
            Sistem HRIS modern untuk menyederhanakan absensi digital, pengajuan izin & cuti, jadwal piket, hingga kalkulasi gaji otomatis secara akurat.
        </p>

        <!-- GRID FITUR (6 FITUR RINGKAS & KOMPAK) -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3 max-w-3xl mx-auto text-left w-full pt-1">
            <!-- Fitur 1: Absensi -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-slate-200 shadow-2xs hover:border-indigo-300 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-base">⏱️</span>
                    <h3 class="text-slate-900 font-bold text-xs sm:text-sm">Absensi Digital</h3>
                </div>
                <p class="text-slate-500 text-[11px] leading-tight">Presensi harian realtime & akurat.</p>
            </div>

            <!-- Fitur 2: Payroll -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-slate-200 shadow-2xs hover:border-emerald-300 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-base">💰</span>
                    <h3 class="text-slate-900 font-bold text-xs sm:text-sm">Payroll & Gaji</h3>
                </div>
                <p class="text-slate-500 text-[11px] leading-tight">Hitung gaji & slip PDF publik.</p>
            </div>

            <!-- Fitur 3: Cuti & Izin -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-slate-200 shadow-2xs hover:border-blue-300 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-base">📝</span>
                    <h3 class="text-slate-900 font-bold text-xs sm:text-sm">Izin & Cuti</h3>
                </div>
                <p class="text-slate-500 text-[11px] leading-tight">Form pengajuan & approval online.</p>
            </div>

            <!-- Fitur 4: Master Data -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-slate-200 shadow-2xs hover:border-indigo-300 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-base">📋</span>
                    <h3 class="text-slate-900 font-bold text-xs sm:text-sm">Data Karyawan</h3>
                </div>
                <p class="text-slate-500 text-[11px] leading-tight">Direktori karyawan & export Excel.</p>
            </div>

            <!-- Fitur 5: Jadwal Piket -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-slate-200 shadow-2xs hover:border-emerald-300 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-base">📅</span>
                    <h3 class="text-slate-900 font-bold text-xs sm:text-sm">Jadwal Piket</h3>
                </div>
                <p class="text-slate-500 text-[11px] leading-tight">Penjadwalan tugas operasional.</p>
            </div>

            <!-- Fitur 6: Aturan Kerja -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-slate-200 shadow-2xs hover:border-blue-300 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-base">📢</span>
                    <h3 class="text-slate-900 font-bold text-xs sm:text-sm">Aturan & Notif</h3>
                </div>
                <p class="text-slate-500 text-[11px] leading-tight">Pengumuman & notifikasi sistem.</p>
            </div>
        </div>

        <!-- Tombol Aksi Utama -->
        <div class="pt-1">
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition duration-200">
                    <span>Masuk ke Dashboard Utama</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition duration-200">
                    <span>Masuk / Login Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @endauth
        </div>
    </main>

    <!-- FOOTER (RINGKAS) -->
    <footer class="w-full max-w-6xl mx-auto px-6 py-3 text-center text-[11px] text-slate-500 z-10 border-t border-slate-200 shrink-0">
        <p>&copy; {{ date('Y') }} HRIS System. All rights reserved.</p>
    </footer>

</body>
</html>
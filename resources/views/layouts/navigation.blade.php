<nav x-data="{ open: false, activeDropdown: null }" class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs select-none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Logo & Main Navigation -->
            <div class="flex items-center gap-8">
                
                <!-- Logo & Brand Name -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="h-10 w-auto flex items-center justify-center shrink-0">
                            <x-application-logo class="h-9 w-auto object-contain group-hover:scale-105 transition-transform duration-200" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-extrabold text-slate-900 tracking-tight leading-none">HRIS<span class="text-indigo-600">System</span></span>
                            <span class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-0.5">Enterprise Portal</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links (Text size text-sm for clean readability) -->
                <div class="hidden sm:flex sm:items-center sm:gap-2 h-16">
                    
                    {{-- Dashboard Link --}}
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <!-- Master Data Dropdown -->
                    <div class="relative h-16 flex items-center" @click.away="if(activeDropdown === 'master') activeDropdown = null">
                        <button @click="activeDropdown = activeDropdown === 'master' ? null : 'master'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold focus:outline-none transition duration-150 cursor-pointer select-none {{ request()->routeIs('employees.*') ? 'bg-indigo-50 text-indigo-600 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100/80' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('employees.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>{{ __('Master Data') }}</span>
                            <svg :class="activeDropdown === 'master' ? 'rotate-180 text-indigo-600' : ''" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="activeDropdown === 'master'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute left-0 top-full mt-1 min-w-[240px] whitespace-nowrap bg-white border border-slate-200 rounded-2xl shadow-2xl z-[100] p-2 space-y-1" style="display:none;">
                            
                            <a href="{{ route('employees.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('employees.index') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <span>Data Karyawan</span>
                            </a>

                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('employees.create') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('employees.create') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    <span>Tambah Karyawan</span>
                                </a>
                            @endif

                            <a href="{{ route('employees.export') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Export ke Excel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Absensi Dropdown -->
                    <div class="relative h-16 flex items-center" @click.away="if(activeDropdown === 'absensi') activeDropdown = null">
                        <button @click="activeDropdown = activeDropdown === 'absensi' ? null : 'absensi'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold focus:outline-none transition duration-150 cursor-pointer select-none {{ request()->routeIs('absensi.*') ? 'bg-indigo-50 text-indigo-600 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100/80' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('absensi.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('Absensi') }}</span>
                            <svg :class="activeDropdown === 'absensi' ? 'rotate-180 text-indigo-600' : ''" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="activeDropdown === 'absensi'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute left-0 top-full mt-1 min-w-[240px] whitespace-nowrap bg-white border border-slate-200 rounded-2xl shadow-2xl z-[100] p-2 space-y-1" style="display:none;">
                            
                            <a href="{{ route('absensi.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('absensi.index') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span>Form Absensi</span>
                            </a>

                            <a href="{{ route('absensi.scan') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('absensi.scan') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <span>Scan RFID / QR</span>
                            </a>

                            <a href="{{ route('absensi.laporan') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('absensi.laporan') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Laporan Absensi</span>
                            </a>
                        </div>
                    </div>

                    <!-- Payroll Dropdown (Admin Only) -->
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <div class="relative h-16 flex items-center" @click.away="if(activeDropdown === 'payroll') activeDropdown = null">
                        <button @click="activeDropdown = activeDropdown === 'payroll' ? null : 'payroll'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold focus:outline-none transition duration-150 cursor-pointer select-none {{ request()->routeIs('payroll.*') ? 'bg-indigo-50 text-indigo-600 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100/80' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('payroll.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('Payroll') }}</span>
                            <svg :class="activeDropdown === 'payroll' ? 'rotate-180 text-indigo-600' : ''" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="activeDropdown === 'payroll'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute left-0 top-full mt-1 min-w-[240px] whitespace-nowrap bg-white border border-slate-200 rounded-2xl shadow-2xl z-[100] p-2 space-y-1" style="display:none;">
                            
                            <a href="{{ route('payroll.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('payroll.index') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Data Gaji</span>
                            </a>

                            <a href="{{ route('payroll.history') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('payroll.history') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Riwayat Payroll</span>
                            </a>

                            <a href="{{ route('payroll.settings') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('payroll.settings') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Setting Data Gaji</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Information Dropdown -->
                    <div class="relative h-16 flex items-center" @click.away="if(activeDropdown === 'information') activeDropdown = null">
                        <button @click="activeDropdown = activeDropdown === 'information' ? null : 'information'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold focus:outline-none transition duration-150 cursor-pointer select-none {{ (request()->routeIs('leave.*') || request()->routeIs('piket.*') || request()->routeIs('rules.*') || request()->routeIs('users.*')) ? 'bg-indigo-50 text-indigo-600 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100/80' }}">
                            <svg class="w-4 h-4 {{ (request()->routeIs('leave.*') || request()->routeIs('piket.*') || request()->routeIs('rules.*') || request()->routeIs('users.*')) ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('Information') }}</span>
                            <svg :class="activeDropdown === 'information' ? 'rotate-180 text-indigo-600' : ''" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="activeDropdown === 'information'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute left-0 top-full mt-1 min-w-[240px] whitespace-nowrap bg-white border border-slate-200 rounded-2xl shadow-2xl z-[100] p-2 space-y-1" style="display:none;">
                            
                            <a href="{{ route('leave.create') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('leave.create') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Ajukan Izin</span>
                            </a>

                            <a href="{{ route('leave.my') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('leave.my') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                </svg>
                                <span>Izin Saya</span>
                            </a>

                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('leave.admin') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('leave.admin') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Kelola Izin (Admin)</span>
                                </a>
                            @endif

                            <a href="{{ route('piket.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('piket.*') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Jadwal Piket</span>
                            </a>

                            <a href="{{ route('rules.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('rules.*') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                <span>Aturan Kerja</span>
                            </a>

                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('users.index') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors whitespace-nowrap {{ request()->routeIs('users.*') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>Master Users</span>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Side: Notification Bell & Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- NOTIFICATION ICON -->
                <button id="notifBell" class="relative text-slate-500 hover:text-indigo-600 hover:bg-slate-100/80 transition-colors p-2 rounded-xl flex items-center">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(isset($notifCount) && $notifCount > 0)
                        <span id="notifCountDesktop" class="absolute -top-0.5 -right-0.5 bg-rose-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-md animate-pulse">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>

                <!-- User Profile Menu -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-slate-200 text-xs leading-4 font-bold 
                            rounded-xl text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900
                            focus:outline-none transition ease-in-out duration-150 shadow-2xs cursor-pointer">
                            @auth
                                <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-extrabold flex items-center justify-center text-xs shadow-2xs shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="font-bold text-xs text-slate-800 max-w-[120px] truncate">{{ Auth::user()->name }}</div>
                            @else
                                <div>Guest</div>
                            @endauth
                            <div class="text-slate-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-xs font-semibold">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <!-- Logout Link -->
                        <form method="POST" action="{{ route('logout') }}" data-confirm="Apakah Anda yakin ingin keluar dari aplikasi?" data-confirm-title="Konfirmasi Logout" data-confirm-btn="Ya, Logout">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50" onclick="event.preventDefault(); this.closest('form').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="-me-2 flex items-center sm:hidden gap-1">
                <button id="notifBellMobile" class="relative text-slate-500 hover:text-indigo-600 p-2 rounded-xl flex items-center mr-1">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(isset($notifCount) && $notifCount > 0)
                        <span id="notifCountMobile" class="absolute -top-0.5 -right-0.5 bg-rose-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-md">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>

                <button @click.stop="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out"
                    aria-label="Toggle menu">
                    <svg :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg :class="{'hidden': ! open, 'inline-flex': open }" class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================
         RESPONSIVE MOBILE NAVIGATION DRAWER (SOLID HIGH CONTRAST)
         ============================================================ -->
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="sm:hidden fixed inset-x-0 top-16 bg-white border-b border-slate-200 z-[9999] shadow-2xl overflow-y-auto max-h-[calc(100vh-4rem)] p-4 space-y-3"
        @click.away="open = false" style="display: none;">
        
        <div class="space-y-2">
            
            {{-- Mobile Dashboard --}}
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Mobile Master Data -->
            <div x-data="{submenuMasterOpen: {{ request()->routeIs('employees.*') ? 'true' : 'false' }}}" class="rounded-xl border border-slate-100 bg-slate-50/50 overflow-hidden">
                <button @click="submenuMasterOpen = !submenuMasterOpen"
                    class="w-full flex justify-between items-center px-4 py-3 text-left text-sm font-bold text-slate-800 hover:text-indigo-600 transition-colors focus:outline-none">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Master Data</span>
                    </span>
                    <svg :class="{'rotate-180 text-indigo-600': submenuMasterOpen}" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="submenuMasterOpen" x-transition class="bg-white px-3 py-2 space-y-1 border-t border-slate-100" style="display:none;">
                    <a href="{{ route('employees.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('employees.index') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Data Karyawan
                    </a>
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('employees.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('employees.create') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Tambah Karyawan
                        </a>
                    @endif
                    <a href="{{ route('employees.export') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Export ke Excel
                    </a>
                </div>
            </div>

            <!-- Mobile Absensi -->
            <div x-data="{submenuAbsensiOpen: {{ request()->routeIs('absensi.*') ? 'true' : 'false' }}}" class="rounded-xl border border-slate-100 bg-slate-50/50 overflow-hidden">
                <button @click="submenuAbsensiOpen = !submenuAbsensiOpen"
                    class="w-full flex justify-between items-center px-4 py-3 text-left text-sm font-bold text-slate-800 hover:text-indigo-600 transition-colors focus:outline-none">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Absensi</span>
                    </span>
                    <svg :class="{'rotate-180 text-indigo-600': submenuAbsensiOpen}" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="submenuAbsensiOpen" x-transition class="bg-white px-3 py-2 space-y-1 border-t border-slate-100" style="display:none;">
                    <a href="{{ route('absensi.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('absensi.index') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Form Absensi
                    </a>
                    <a href="{{ route('absensi.scan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('absensi.scan') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Scan RFID / QR
                    </a>
                    <a href="{{ route('absensi.laporan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('absensi.laporan') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Laporan Absensi
                    </a>
                </div>
            </div>

            <!-- Mobile Payroll -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            <div x-data="{submenuPayrollOpen: {{ request()->routeIs('payroll.*') ? 'true' : 'false' }}}" class="rounded-xl border border-slate-100 bg-slate-50/50 overflow-hidden">
                <button @click="submenuPayrollOpen = !submenuPayrollOpen"
                    class="w-full flex justify-between items-center px-4 py-3 text-left text-sm font-bold text-slate-800 hover:text-indigo-600 transition-colors focus:outline-none">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Payroll</span>
                    </span>
                    <svg :class="{'rotate-180 text-indigo-600': submenuPayrollOpen}" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="submenuPayrollOpen" x-transition class="bg-white px-3 py-2 space-y-1 border-t border-slate-100" style="display:none;">
                    <a href="{{ route('payroll.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('payroll.index') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Data Gaji
                    </a>
                    <a href="{{ route('payroll.history') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('payroll.history') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Riwayat Payroll
                    </a>
                    <a href="{{ route('payroll.settings') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('payroll.settings') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Setting Data Gaji
                    </a>
                </div>
            </div>
            @endif

            <!-- Mobile Information -->
            <div x-data="{submenuIzinOpen: {{ (request()->routeIs('leave.*') || request()->routeIs('piket.*') || request()->routeIs('rules.*') || request()->routeIs('users.*')) ? 'true' : 'false' }}}" class="rounded-xl border border-slate-100 bg-slate-50/50 overflow-hidden">
                <button @click="submenuIzinOpen = !submenuIzinOpen"
                    class="w-full flex justify-between items-center px-4 py-3 text-left text-sm font-bold text-slate-800 hover:text-indigo-600 transition-colors focus:outline-none">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Information</span>
                    </span>
                    <svg :class="{'rotate-180 text-indigo-600': submenuIzinOpen}" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="submenuIzinOpen" x-transition class="bg-white px-3 py-2 space-y-1 border-t border-slate-100" style="display:none;">
                    <a href="{{ route('leave.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('leave.create') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Ajukan Izin
                    </a>
                    <a href="{{ route('leave.my') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('leave.my') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Izin Saya
                    </a>
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('leave.admin') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('leave.admin') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Kelola Izin (Admin)
                        </a>
                    @endif
                    <a href="{{ route('piket.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('piket.*') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Jadwal Piket
                    </a>
                    <a href="{{ route('rules.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('rules.*') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        Aturan Kerja
                    </a>
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition {{ request()->routeIs('users.*') ? 'text-indigo-600 bg-indigo-50 font-bold' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Master Users
                        </a>
                    @endif
                </div>
            </div>

            <!-- Mobile User Profile & Logout Section -->
            @auth
                <div class="pt-4 pb-2 border-t border-slate-200">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-9 h-9 rounded-lg bg-indigo-600 text-white font-extrabold flex items-center justify-center text-sm shadow-2xs shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="overflow-hidden">
                                <div class="font-bold text-xs text-slate-900 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil Saya</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" data-confirm="Apakah Anda yakin ingin keluar dari akun?" data-confirm-title="Konfirmasi Logout">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition text-left">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

@include('layouts.notification-modal')
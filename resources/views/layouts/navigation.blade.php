<nav x-data="{ open: false }"
    class="bg-slate-900 border-b border-slate-800 relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Master Data with submenu -->
                    <div x-data="{ submenuMasterOpen: false }" class="relative h-16">
                        <x-nav-link href="#" @click.prevent="submenuMasterOpen = !submenuMasterOpen"
                            :active="request()->routeIs('employees.*')"
                            class="flex items-center h-16 cursor-pointer select-none">
                            {{ __('Master Data') }}
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </x-nav-link>

                        <ul x-show="submenuMasterOpen" @click.away="submenuMasterOpen = false" x-transition
                            class="absolute left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-10">
                            <li>
                                <a href="{{ route('employees.index') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 
                                           rounded-t-xl transition-colors {{ request()->routeIs('employees.index') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    📋 Data Karyawan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('employees.create') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 transition-colors {{ request()->routeIs('employees.create') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    ➕ Tambah Karyawan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('employees.export') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-b-xl transition-colors">
                                    📤 Export ke Excel
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Absensi with submenu -->
                    <div x-data="{ submenuOpen: false }" class="relative h-16">
                        <x-nav-link href="#" @click="submenuOpen = !submenuOpen"
                            :active="request()->routeIs('absensi.*')"
                            class="flex items-center h-16 cursor-pointer select-none">
                            {{ __('Absensi') }}
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </x-nav-link>
                        <ul x-show="submenuOpen" @click.away="submenuOpen = false" x-transition
                            class="absolute left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-10">
                            <li>
                                <a href="{{ route('absensi.index') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-t-xl transition-colors {{ request()->routeIs('absensi.index') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    {{ __('📋 Form Absensi') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('absensi.laporan') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-b-xl transition-colors {{ request()->routeIs('absensi.laporan') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    {{ __('📄 Laporan Absensi') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div x-data="{ submenuOpen: false }" class="relative h-16">
                        <x-nav-link href="#" @click.prevent="submenuOpen = !submenuOpen"
                            :active="request()->routeIs('payroll.*')"
                            class="flex items-center h-16 cursor-pointer select-none">
                            {{ __('Payroll') }}
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </x-nav-link>

                        <ul x-show="submenuOpen" @click.away="submenuOpen = false" x-transition
                            class="absolute left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-10">
                            <li>
                                <a href="{{ route('payroll.index') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-t-xl transition-colors {{ request()->routeIs('payroll.index') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    {{ __('📋 Data Gaji') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('payroll.history') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 transition-colors {{ request()->routeIs('payroll.history') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    {{ __('📊 Riwayat Payroll') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('payroll.settings') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-b-xl transition-colors {{ request()->routeIs('payroll.settings') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    {{ __('⚙️ Setting Data') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Izin with submenu -->
                    <div x-data="{ submenuIzinOpen: false }" class="relative h-16">

                        <x-nav-link href="#" @click.prevent="submenuIzinOpen = !submenuIzinOpen"
                            :active="request()->routeIs('leave.*') || request()->routeIs('piket.*') || request()->routeIs('rules.*')" class="flex items-center h-16 cursor-pointer select-none">

                            {{ __('Information') }}

                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </x-nav-link>

                        <ul x-show="submenuIzinOpen" @click.away="submenuIzinOpen = false" x-transition class="absolute left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-10">

                            <!-- Ajukan Izin -->
                            <li>
                                <a href="{{ route('leave.create') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-t-xl transition-colors
                {{ request()->routeIs('leave.create') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    ✏️ Ajukan Izin
                                </a>
                            </li>

                            <!-- Izin Saya -->
                            <li>
                                <a href="{{ route('leave.my') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 transition-colors
                {{ request()->routeIs('leave.my') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    📄 Izin Saya
                                </a>
                            </li>

                            <!-- Jadwal Piket (NEW) -->
                            <li>
                                <a href="{{ route('piket.index') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 transition-colors
                {{ request()->routeIs('piket.*') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    📅 Jadwal Piket
                                </a>
                            </li>

                            <!-- Aturan Kerja / Pemberitahuan (NEW) -->
                            <li>
                                <a href="{{ route('rules.index') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-slate-100 rounded-b-xl transition-colors
                {{ request()->routeIs('rules.*') ? 'font-semibold text-white bg-slate-700/50' : '' }}">
                                    📢 Aturan Kerja
                                </a>
                            </li>

                        </ul>

                    </div>



                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-8 gap-4">
                <!-- NOTIFICATION ICON -->
                <button id="notifBell" class="relative text-slate-400 hover:text-slate-200 transition-colors p-2 rounded-lg flex items-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($notifCount > 0)
                        <span id="notifCountDesktop" class="absolute -top-0.5 -right-0.5 bg-rose-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-md">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-medium 
                            rounded-lg text-slate-300 bg-slate-800 hover:text-slate-100 hover:bg-slate-700
                            focus:outline-none transition ease-in-out duration-150">
                            @auth
                                <div>{{ Auth::user()->name }}</div>
                            @else
                                <div>Guest</div>
                            @endauth
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('👨🏼‍💼 Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden gap-1">
                <!-- Mobile Notification Icon -->
                <button id="notifBellMobile" class="relative text-slate-400 hover:text-slate-200 transition-colors p-2 rounded-lg flex items-center mr-1">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($notifCount > 0)
                        <span id="notifCountMobile" class="absolute -top-0.5 -right-0.5 bg-rose-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-md">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>

                <button @click.stop="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800 focus:outline-none focus:bg-slate-800 transition duration-150 ease-in-out"
                    aria-label="Toggle menu" aria-expanded="false">
                    <svg :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex h-6 w-6"
                        stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg :class="{'hidden': ! open, 'inline-flex': open }" class="hidden h-6 w-6" stroke="currentColor"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="sm:hidden absolute top-full left-0 right-0 bg-slate-900 border-t border-slate-800 z-40 shadow-xl"
        @click.away="open = false" style="display: none;">
        <div class="pt-2 pb-3 space-y-1 px-4">

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Responsive Master Data submenu -->
            <div x-data="{submenuMasterOpen: false}" class="space-y-1">
                <button @click="submenuMasterOpen = !submenuMasterOpen"
                    class="w-full flex justify-between items-center px-3 py-2 rounded-lg text-left text-slate-300 hover:text-slate-100 hover:bg-slate-800 transition-colors focus:outline-none"
                    aria-haspopup="true" :aria-expanded="submenuMasterOpen.toString()">
                    <span>{{ __('Master Data') }}</span>
                    <svg :class="{'rotate-180': submenuMasterOpen}" class="h-4 w-4 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="submenuMasterOpen" x-transition class="space-y-1 pl-4">
                    <x-responsive-nav-link href="{{ route('employees.index') }}"
                        :active="request()->routeIs('employees.index')">
                        {{ __('Data Karyawan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('employees.create') }}"
                        :active="request()->routeIs('employees.create')">
                        {{ __('Tambah Karyawan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('employees.export') }}">
                        {{ __('Export ke Excel') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Responsive Absensi submenu -->
            <div x-data="{submenuAbsensiOpen: false}" class="space-y-1">
                <button @click="submenuAbsensiOpen = !submenuAbsensiOpen"
                    class="w-full flex justify-between items-center px-3 py-2 rounded-lg text-left text-slate-300 hover:text-slate-100 hover:bg-slate-800 transition-colors focus:outline-none"
                    aria-haspopup="true" :aria-expanded="submenuAbsensiOpen.toString()">
                    <span>{{ __('Absensi') }}</span>
                    <svg :class="{'rotate-180': submenuAbsensiOpen}" class="h-4 w-4 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="submenuAbsensiOpen" x-transition class="space-y-1 pl-4">
                    <x-responsive-nav-link href="{{ route('absensi.index') }}"
                        :active="request()->routeIs('absensi.index')">
                        {{ __('Form Absensi') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('absensi.laporan') }}"
                        :active="request()->routeIs('absensi.laporan')">
                        {{ __('Laporan Absensi') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            <!-- Responsive Payroll submenu -->
            <div x-data="{submenuPayrollOpen: false}" class="space-y-1">

                <!-- Tombol utama Payroll -->
                <button @click="submenuPayrollOpen = !submenuPayrollOpen" class="w-full flex justify-between items-center px-3 py-2 rounded-lg text-left
               text-slate-300 hover:text-slate-100 hover:bg-slate-800 transition-colors
               focus:outline-none" :aria-expanded="submenuPayrollOpen.toString()">

                    <span>Payroll</span>

                    <!-- Ikon panah -->
                    <svg :class="{'rotate-180': submenuPayrollOpen}" class="h-4 w-4 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Submenu Payroll -->
                <div x-show="submenuPayrollOpen" x-transition class="space-y-1 pl-4">
                    <x-responsive-nav-link href="{{ route('payroll.index') }}"
                        :active="request()->routeIs('payroll.index')">
                        {{ __('Data Gaji') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('payroll.history') }}"
                        :active="request()->routeIs('payroll.history')">
                        {{ __('Riwayat Payroll') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('payroll.settings') }}"
                        :active="request()->routeIs('payroll.settings')">
                        {{ __('Setting Data') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!--Izin submenu -->
            <div x-data="{submenuIzinOpen: false}" class="space-y-1">

                <button @click="submenuIzinOpen = !submenuIzinOpen" class="w-full flex justify-between items-center px-3 py-2 rounded-lg text-left 
               text-slate-300 hover:text-slate-100 hover:bg-slate-800 transition-colors 
               focus:outline-none" aria-haspopup="true" :aria-expanded="submenuIzinOpen.toString()">

                    <span>Information</span>

                    <svg :class="{'rotate-180': submenuIzinOpen}" class="h-4 w-4 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="submenuIzinOpen" x-transition class="space-y-1 pl-4">

                    <x-responsive-nav-link href="{{ route('leave.create') }}"
                        :active="request()->routeIs('leave.create')">
                        ✏️ Ajukan Izin
                    </x-responsive-nav-link>

                    <x-responsive-nav-link href="{{ route('leave.my') }}" :active="request()->routeIs('leave.my')">
                        📄 Izin Saya
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ route('piket.index') }}" :active="request()->routeIs('piket.*')">
                        📅 Jadwal Piket
                    </x-responsive-nav-link>

                    <x-responsive-nav-link href="{{ route('rules.index') }}" :active="request()->routeIs('rules.*')">
                        📢 Aturan Kerja
                    </x-responsive-nav-link>

                </div>
            </div>

            <!-- Responsive Settings -->
            <div class="pt-4 pb-1 border-t border-slate-800">
                <div class="px-4 font-medium text-base text-slate-200">{{ Auth::user()->name }}</div>
                <div class="px-4 font-medium text-sm text-slate-450">{{ Auth::user()->email }}</div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

@include('layouts.notification-modal')
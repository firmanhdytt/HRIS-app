<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-xl font-extrabold text-slate-900">Registrasi Akun Baru</h2>
        <p class="text-xs text-slate-500 mt-1">Buat akun untuk mengakses HRIS System</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-semibold text-slate-700" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-xs" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-xs" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@perusahaan.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-slate-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-xs" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-xs font-semibold text-slate-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-xs" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition duration-200 flex items-center justify-center gap-2">
                <span>{{ __('Daftar Sekarang') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

        <div class="pt-4 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
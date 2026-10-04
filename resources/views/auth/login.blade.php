<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-xl font-extrabold text-slate-900">Selamat Datang Kembali</h2>
        <p class="text-xs text-slate-500 mt-1">Silakan masuk ke akun HRIS System Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Perusahaan')" class="text-xs font-semibold text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-xs" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@perusahaan.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-slate-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-xs" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-xs focus:ring-indigo-500" name="remember" checked>
                <span class="ms-2 text-slate-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-indigo-600 hover:text-indigo-800 font-semibold transition" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition duration-200 flex items-center justify-center gap-2">
                <span>{{ __('Masuk ke Sistem') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

        <div class="pt-4 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
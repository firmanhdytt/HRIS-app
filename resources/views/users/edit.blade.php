<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            Edit Data User
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Edit Akun Pengguna</h3>

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4" data-confirm="Apakah Anda yakin ingin memperbarui data akun ini?">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-xl bg-white text-slate-900 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 text-sm">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-xl bg-white text-slate-900 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 text-sm">
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Role</label>
                    <select name="role" required
                            class="w-full rounded-xl bg-white text-slate-900 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 text-sm">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex gap-3">
                    <a href="{{ route('users.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 text-center text-sm font-semibold active:scale-[0.98] transition-all flex-1">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-all duration-200 active:scale-[0.98] flex-[2]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

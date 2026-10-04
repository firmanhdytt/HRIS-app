<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight tracking-tight">
            Master Data Users
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden p-5 sm:p-6">
            
            <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Manajemen Pengguna</h3>
                    <p class="text-xs text-slate-500 mt-1">Daftar akun pengguna yang terdaftar di dalam sistem.</p>
                </div>

                <!-- Form Search -->
                <form method="GET" action="{{ route('users.index') }}" class="flex gap-3 w-full sm:w-auto max-w-md">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari nama atau email..."
                        value="{{ request('search') }}"
                        class="w-full sm:w-64 rounded-xl px-3.5 py-2 bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm"
                    />
                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <span>Cari</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Table Users -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                <table class="min-w-full divide-y divide-slate-200 text-slate-700 text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-5 py-3.5 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-5 py-3.5 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-3.5 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3.5 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                            @if($currentUserRole === 'admin')
                                <th class="px-5 py-3.5 border-b border-slate-200 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors text-slate-800">
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">#{{ $user->id }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                @if(($user->role ?? 'user') === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        User
                                    </span>
                                @endif
                            </td>
                            @if($currentUserRole === 'admin')
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-center">
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="inline-flex items-center px-3 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-indigo-600 hover:text-indigo-700 text-xs font-semibold shadow-sm active:scale-[0.98] transition-all duration-200">
                                        Edit
                                    </a>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $currentUserRole === 'admin' ? 5 : 4 }}" class="px-5 py-8 text-center text-slate-400 italic">Data user tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $users->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
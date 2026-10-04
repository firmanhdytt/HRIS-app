<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HRIS System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-full flex flex-col selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col bg-slate-50">

        <!-- TOP NAVIGATION BAR -->
        @include('layouts.navigation')

        <!-- PAGE HEADING -->
        @isset($header)
            <header class="bg-white border-b border-slate-200/80 shadow-2xs">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- PAGE CONTENT -->
        <main id="main-content" data-ajax-container="true" class="flex-1 py-6">
            {{-- GLOBAL PREMIUM FLOATING TOAST NOTIFICATION --}}
            @if(session('success') || session('error') || session('warning') || session('info'))
                <div id="global-toast" class="fixed top-5 right-5 z-[9999] max-w-sm w-full bg-white border border-slate-200 p-4 rounded-xl shadow-xl flex items-start gap-3 pointer-events-auto transform translate-y-0 transition-all duration-300 animate-slideIn">
                    
                    {{-- ICON BADGE --}}
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center border font-semibold text-sm
                        @if(session('success')) text-emerald-600 border-emerald-200 bg-emerald-50 @endif
                        @if(session('error')) text-rose-600 border-rose-200 bg-rose-50 @endif
                        @if(session('warning')) text-amber-600 border-amber-200 bg-amber-50 @endif
                        @if(session('info')) text-indigo-600 border-indigo-200 bg-indigo-50 @endif">
                        @if(session('success'))
                            ✓
                        @elseif(session('error'))
                            ✕
                        @elseif(session('warning'))
                            !
                        @else
                            i
                        @endif
                    </div>

                    {{-- TEXT CONTENT --}}
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-slate-500">Pemberitahuan</p>
                        <p class="text-sm font-medium text-slate-800 mt-0.5">
                            {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
                        </p>
                    </div>

                    {{-- CLOSE BUTTON --}}
                    <button onclick="closeGlobalToast()" class="text-slate-400 hover:text-slate-600 text-lg font-bold leading-none">&times;</button>
                </div>

                <script>
                    function closeGlobalToast() {
                        const toast = document.getElementById('global-toast');
                        if (toast) {
                            toast.classList.add('opacity-0', 'translate-x-5', 'scale-95');
                            setTimeout(() => toast.remove(), 300);
                        }
                    }
                    setTimeout(closeGlobalToast, 4000);
                </script>

                <style>
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-20px) scale(0.95);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                    }
                    .animate-slideIn {
                        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                    }
                </style>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- GLOBAL ACTION CONFIRMATION MODAL -->
    <div id="globalConfirmModal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4 transition-opacity">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-md w-full p-6 text-center transform scale-95 transition-transform duration-200" id="globalConfirmCard">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                ⚠️
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2" id="globalConfirmTitle">Konfirmasi Aksi</h3>
            <p class="text-sm text-slate-600 mb-6" id="globalConfirmMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            <div class="flex items-center justify-end gap-3">
                <button type="button" id="globalConfirmCancelBtn" onclick="closeConfirmModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">
                    Batal
                </button>
                <button type="button" id="globalConfirmOkBtn" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold shadow-md transition">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        let pendingConfirmCallback = null;

        function showConfirmModal({ title = 'Konfirmasi Aksi', message = 'Apakah Anda yakin ingin melanjutkan?', btnText = 'Ya, Lanjutkan', btnClass = 'bg-rose-600 hover:bg-rose-700 text-white', onConfirm = null }) {
            document.getElementById('globalConfirmTitle').textContent = title;
            document.getElementById('globalConfirmMessage').textContent = message;
            
            const okBtn = document.getElementById('globalConfirmOkBtn');
            okBtn.textContent = btnText;
            okBtn.className = `px-5 py-2 rounded-xl text-sm font-semibold shadow-md transition ${btnClass}`;
            
            pendingConfirmCallback = onConfirm;

            const modal = document.getElementById('globalConfirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmModal() {
            const modal = document.getElementById('globalConfirmModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingConfirmCallback = null;
        }

        document.getElementById('globalConfirmOkBtn').addEventListener('click', () => {
            if (typeof pendingConfirmCallback === 'function') {
                const cb = pendingConfirmCallback;
                closeConfirmModal();
                cb();
            } else {
                closeConfirmModal();
            }
        });

        // Intercept form submissions that have data-confirm
        document.addEventListener('submit', (e) => {
            const form = e.target.closest('form');
            if (form && form.hasAttribute('data-confirm') && !form.dataset.confirmed) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const confirmMsg = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melakukan aksi ini?';
                const confirmTitle = form.getAttribute('data-confirm-title') || 'Konfirmasi Aksi';
                const confirmBtn = form.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan';
                
                showConfirmModal({
                    title: confirmTitle,
                    message: confirmMsg,
                    btnText: confirmBtn,
                    onConfirm: () => {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            }
        }, true);
    </script>

    <!-- Global AJAX Navigation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function executeScripts(container) {
                const scripts = container.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    if (oldScript.src) {
                        newScript.src = oldScript.src;
                    } else {
                        newScript.textContent = oldScript.textContent;
                    }
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }

            document.addEventListener('click', async (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                const url = link.href;
                if (!url) return;
                if (link.target === '_blank' || link.hasAttribute('download') || link.getAttribute('data-no-ajax') === 'true') return;
                if (url.startsWith('javascript:') || url.includes('#') || url === '') return;

                try {
                    const linkUrl = new URL(url);
                    if (linkUrl.origin !== window.location.origin) return;
                    if (url.includes('/export/') || url.includes('/excel') || url.includes('/pdf') || url.includes('/logout')) return;

                    e.preventDefault();
                    await navigateTo(url);
                } catch (err) {}
            });

            async function navigateTo(url) {
                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const html = await response.text();
                    updateMainContent(html);
                    history.pushState(null, '', url);
                } catch (error) {
                    window.location.href = url;
                }
            }

            function updateMainContent(html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMain = doc.querySelector('[data-ajax-container="true"]');
                const currentMain = document.querySelector('[data-ajax-container="true"]');

                if (newMain && currentMain) {
                    currentMain.innerHTML = newMain.innerHTML;
                    executeScripts(currentMain);
                    if (window.Alpine) {
                        try { Alpine.initTree(currentMain); } catch (e) {}
                    }
                    document.dispatchEvent(new Event('ajax-container:updated'));
                }
            }

            window.navigateTo = navigateTo;
            window.addEventListener('popstate', async () => {
                await navigateTo(window.location.href);
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-slate-950 text-slate-100">

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-slate-900/50 backdrop-blur-md border-b border-slate-800/80 shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main id="main-content" data-ajax-container="true">
            {{-- GLOBAL PREMIUM FLOATING TOAST NOTIFICATION --}}
            @if(session('success') || session('error') || session('warning') || session('info'))
                <div id="global-toast" class="fixed top-5 right-5 z-[9999] max-w-sm w-full bg-slate-900/95 backdrop-blur-md border border-slate-800 p-4 rounded-xl shadow-2xl flex items-start gap-3 pointer-events-auto transform translate-y-0 transition-all duration-300 animate-slideIn">
                    
                    {{-- ICON BADGE --}}
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center border font-semibold text-sm
                        @if(session('success')) text-emerald-400 border-emerald-500/30 bg-emerald-500/10 @endif
                        @if(session('error')) text-rose-400 border-rose-500/30 bg-rose-500/10 @endif
                        @if(session('warning')) text-amber-400 border-amber-500/30 bg-amber-500/10 @endif
                        @if(session('info')) text-indigo-400 border-indigo-500/30 bg-indigo-500/10 @endif">
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
                        <p class="text-xs font-semibold text-slate-300">Pemberitahuan</p>
                        <p class="text-sm text-slate-100 mt-0.5">
                            {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
                        </p>
                    </div>

                    {{-- CLOSE BUTTON --}}
                    <button onclick="closeGlobalToast()" class="text-slate-400 hover:text-slate-200 text-lg font-bold leading-none">&times;</button>
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

    <!-- Script untuk Toggle Submenu (Ditambahkan) -->
    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id + '-submenu');
            submenu.classList.toggle('hidden');
        }
    </script>
    <!-- Akhir Script -->

    <!-- Global AJAX Router (SPA-like navigation, search, pagination, and actions) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Helper to execute script tags inside an element
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

            // Intercept navigation links and clicks
            document.addEventListener('click', async (e) => {
                // Find closest anchor tag
                const link = e.target.closest('a');
                if (!link) return;

                // Skip absolute external links, target="_blank", javascript:, download links, or PDF/Excel exports
                const url = link.href;
                if (!url) return;
                if (link.target === '_blank' || link.hasAttribute('download') || link.getAttribute('data-no-ajax') === 'true') return;
                if (url.startsWith('javascript:') || url.includes('#') || url === '') return;

                // Check origin
                try {
                    const linkUrl = new URL(url);
                    if (linkUrl.origin !== window.location.origin) return;

                    // Skip specific routes like exports, logout, etc.
                    if (url.includes('/export/') || url.includes('/excel') || url.includes('/pdf') || url.includes('/logout')) return;

                    e.preventDefault();
                    await navigateTo(url);
                } catch (err) {
                    // Ignore invalid URLs
                }
            });

            // Intercept form submissions
            document.addEventListener('submit', async (e) => {
                const form = e.target.closest('form');
                if (!form) return;
                if (form.getAttribute('data-no-ajax') === 'true') return;

                e.preventDefault();
                
                const method = form.method.toUpperCase();
                const action = form.action || window.location.href;
                const formData = new FormData(form);

                if (method === 'GET') {
                    const params = new URLSearchParams(formData).toString();
                    const targetUrl = action.split('?')[0] + '?' + params;
                    await navigateTo(targetUrl);
                } else {
                    // POST, PUT, DELETE
                    try {
                        const response = await fetch(action, {
                            method: 'POST', // Laravel uses _method field inside FormData for PUT/DELETE
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.redirected) {
                            await navigateTo(response.url);
                        } else {
                            const html = await response.text();
                            updateMainContent(html);
                            if (response.url && response.url !== window.location.href) {
                                history.pushState(null, '', response.url);
                            }
                        }
                        
                        // Hide any active modals
                        closeAllModals();
                    } catch (error) {
                        console.error('AJAX Submit Error:', error);
                    }
                }
            });

            // Intercept changes on filters (select, date inputs)
            document.addEventListener('change', (e) => {
                const target = e.target;
                if (target.closest('[data-no-ajax="true"]')) return;
                
                if (target.closest('main') && (target.tagName === 'SELECT' || target.type === 'date')) {
                    const form = target.closest('form');
                    if (form) {
                        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    }
                }
            });

            // Intercept inputs on search fields (debounced)
            let debounceTimer;
            document.addEventListener('input', (e) => {
                const target = e.target;
                if (target.closest('[data-no-ajax="true"]')) return;
                
                if (target.closest('main') && (target.tagName === 'INPUT' && (target.type === 'text' || target.type === 'search' || !target.type))) {
                    const form = target.closest('form');
                    if (form) {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                        }, 350);
                    }
                }
            });

            // Navigation function
            async function navigateTo(url) {
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const html = await response.text();
                    updateMainContent(html);
                    history.pushState(null, '', url);
                } catch (error) {
                    console.error('AJAX Navigation Error:', error);
                    window.location.href = url;
                }
            }

            // Replace content of [data-ajax-container] and run scripts
            function updateMainContent(html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMain = doc.querySelector('[data-ajax-container="true"]');
                const currentMain = document.querySelector('[data-ajax-container="true"]');

                if (newMain && currentMain) {
                    currentMain.innerHTML = newMain.innerHTML;
                    executeScripts(currentMain);
                    
                    // Re-initialize Alpine.js on the swapped content
                    if (window.Alpine) {
                        try {
                            Alpine.initTree(currentMain);
                        } catch (e) {
                            console.error('Alpine initTree error:', e);
                        }
                    }

                    document.dispatchEvent(new Event('ajax-container:updated'));
                    closeMobileMenu();
                }
            }

            // Helper to close mobile menu
            function closeMobileMenu() {
                const nav = document.querySelector('nav');
                if (nav && window.Alpine) {
                    try {
                        const data = Alpine.$data(nav);
                        if (data) {
                            data.open = false;
                        }
                    } catch (e) {
                        // ignore
                    }
                    
                    // Reset mobile submenus
                    try {
                        const submenus = nav.querySelectorAll('[x-data]');
                        submenus.forEach(el => {
                            const subData = Alpine.$data(el);
                            if (subData) {
                                if (subData.hasOwnProperty('submenuMasterOpen')) subData.submenuMasterOpen = false;
                                if (subData.hasOwnProperty('submenuAbsensiOpen')) subData.submenuAbsensiOpen = false;
                                if (subData.hasOwnProperty('submenuPayrollOpen')) subData.submenuPayrollOpen = false;
                                if (subData.hasOwnProperty('submenuIzinOpen')) subData.submenuIzinOpen = false;
                            }
                        });
                    } catch (e) {
                        // ignore
                    }
                }
            }

            // Helper to close all modals upon successful action
            function closeAllModals() {
                const modals = document.querySelectorAll('#photoModal, #modalReason, #modalBukti, #notifModal, #confirmModal, .modal');
                modals.forEach(modal => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
                document.body.style.overflow = 'auto';
            }

            // Expose globally
            window.navigateTo = navigateTo;

            // Handle back/forward history navigation
            window.addEventListener('popstate', async () => {
                await navigateTo(window.location.href);
            });
        });
    </script>
</body>
</html>
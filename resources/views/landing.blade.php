<!doctype html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS untuk UMKM - Solusi Manajemen Karyawan</title>
    <script src="/_sdk/element_sdk.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            box-sizing: border-box;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .delay-400 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .delay-600 {
            animation-delay: 0.6s;
            opacity: 0;
        }
    </style>
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
    <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
</head>

<body class="h-full">
    <!-- ============================= -->
    <!-- NAVIGATION BAR -->
    <!-- ============================= -->
    <nav class="w-full fixed top-0 left-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

            <!-- Logo -->
            <div class="text-2xl font-bold text-gray-800">
                Solusi<span class="text-blue-500">Bersama</span>
            </div>

            <!-- Navigation Menu -->
            <div class="hidden md:flex space-x-8 text-gray-700 font-medium">
                <a href="#hero-section" class="hover:text-blue-600 transition">Beranda</a>
                <a href="#problem-section" class="hover:text-blue-600 transition">Masalah</a>
                <a href="#features-section" class="hover:text-blue-600 transition">Fitur</a>
                <a href="#cta-section" class="hover:text-blue-600 transition">Kontak</a>
            </div>

            <!-- LOGIN BUTTON -->
            <a href="{{ route('login') }}"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 shadow transition">
                Login
            </a>
        </div>
    </nav>
    <div id="app" class="w-full h-full overflow-auto"><!-- Hero Section -->
        <section id="hero-section" class="w-full min-h-screen flex items-center justify-center px-6 py-20">
            <div class="max-w-6xl w-full mx-auto">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6 animate-fade-in-up">
                        <h1 id="hero-headline" class="text-5xl md:text-6xl font-bold leading-tight">Kelola HR UMKM Lebih
                            Mudah, Cepat, dan Akurat</h1>
                        <p id="hero-subheadline" class="text-xl opacity-90">Perkenalkan Aplikasi HRIS Custom Anda:
                            Efisienkan absensi, payroll, dan pantau kinerja karyawan dari satu dashboard intuitif.
                            Dibangun untuk kebutuhan bisnis Anda.</p><button id="hero-cta"
                            class="px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            Lihat Fitur Lengkapnya Sekarang </button>
                    </div>
                    <div class="animate-fade-in-up delay-200">
                        <svg viewbox="0 0 500 400" class="w-full h-auto">
                            <rect x="50" y="50" width="400" height="300" rx="10" fill="white" stroke="#E5E7EB"
                                stroke-width="2" />
                            <rect x="70" y="70" width="360" height="40" rx="5" fill="#F3F4F6" />
                            <circle cx="90" cy="90" r="8" fill="#10B981" />
                            <rect x="110" y="82" width="100" height="16" rx="3" fill="#D1D5DB" />
                            <rect x="350" y="82" width="60" height="16" rx="3" fill="#D1D5DB" />
                            <rect x="70" y="130" width="170" height="120" rx="8" fill="#EEF2FF" />
                            <circle cx="155" cy="165" r="15" fill="#4A90E2" /> <text x="155" y="172"
                                text-anchor="middle" fill="white" font-size="16" font-weight="bold">
                                📊
                            </text>
                            <rect x="110" y="195" width="90" height="8" rx="2" fill="#C7D2FE" />
                            <rect x="110" y="210" width="70" height="8" rx="2" fill="#C7D2FE" />
                            <rect x="260" y="130" width="170" height="120" rx="8" fill="#FEF3C7" />
                            <circle cx="345" cy="165" r="15" fill="#FFA726" /> <text x="345" y="172"
                                text-anchor="middle" fill="white" font-size="16" font-weight="bold">
                                ⏰
                            </text>
                            <rect x="300" y="195" width="90" height="8" rx="2" fill="#FDE68A" />
                            <rect x="300" y="210" width="70" height="8" rx="2" fill="#FDE68A" />
                            <rect x="70" y="270" width="360" height="50" rx="8" fill="#D1FAE5" />
                            <circle cx="100" cy="295" r="12" fill="#5CB85C" /> <text x="100" y="301"
                                text-anchor="middle" fill="white" font-size="14" font-weight="bold">
                                ✓
                            </text>
                            <rect x="130" y="287" width="120" height="10" rx="2" fill="#86EFAC" />
                            <rect x="130" y="302" width="80" height="6" rx="2" fill="#86EFAC" />
                        </svg>
                    </div>
                </div>
            </div>
        </section><!-- Problem Section -->
        <section id="problem-section" class="w-full py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <h2 id="problem-headline" class="text-4xl md:text-5xl font-bold text-center mb-16">Frustrasi dengan
                    Urusan HR yang Memakan Waktu?</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-8 rounded-xl transition-all duration-300 hover:shadow-xl">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Pencatatan Manual</h3>
                        <p class="opacity-80">Absensi manual yang rawan kesalahan dan membuang waktu berharga Anda</p>
                    </div>
                    <div class="text-center p-8 rounded-xl transition-all duration-300 hover:shadow-xl">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Perhitungan Rumit</h3>
                        <p class="opacity-80">Hitung gaji secara manual yang memusingkan dan berisiko salah hitung</p>
                    </div>
                    <div class="text-center p-8 rounded-xl transition-all duration-300 hover:shadow-xl">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Monitoring Terbatas</h3>
                        <p class="opacity-80">Sulit memantau kinerja dan produktivitas karyawan secara efektif</p>
                    </div>
                </div>
            </div>
        </section><!-- Features Section -->
        <section id="features-section" class="w-full py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <h2 id="features-headline" class="text-4xl md:text-5xl font-bold text-center mb-6">Solusi Lengkap dalam
                    Satu Platform</h2>
                <p class="text-xl text-center opacity-80 mb-16 max-w-3xl mx-auto">Semua yang Anda butuhkan untuk
                    mengelola HR dengan efisien dan profesional</p>
                <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center font-bold text-xl">
                                ✓
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-2">Absensi Digital</h3>
                                <p class="opacity-80">Sistem absensi otomatis yang akurat dengan GPS tracking dan foto
                                    verifikasi</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center font-bold text-xl">
                                ✓
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-2">Payroll Otomatis</h3>
                                <p class="opacity-80">Hitung gaji, tunjangan, dan potongan secara otomatis dengan
                                    akurasi 100%</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center font-bold text-xl">
                                ✓
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-2">Dashboard Analytics</h3>
                                <p class="opacity-80">Pantau kinerja tim dengan laporan visual yang mudah dipahami</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl p-8 shadow-xl">
                        <svg viewbox="0 0 400 300" class="w-full h-auto">
                            <rect x="0" y="0" width="400" height="300" rx="15" fill="#F9FAFB" />
                            <rect x="20" y="20" width="120" height="80" rx="8" fill="white" stroke="#E5E7EB"
                                stroke-width="2" /> <text x="80" y="50" text-anchor="middle" font-size="24">
                                📊
                            </text> <text x="80" y="75" text-anchor="middle" font-size="12" fill="#6B7280">
                                Analytics
                            </text>
                            <rect x="160" y="20" width="120" height="80" rx="8" fill="white" stroke="#E5E7EB"
                                stroke-width="2" /> <text x="220" y="50" text-anchor="middle" font-size="24">
                                👥
                            </text> <text x="220" y="75" text-anchor="middle" font-size="12" fill="#6B7280">
                                Karyawan
                            </text>
                            <rect x="300" y="20" width="80" height="80" rx="8" fill="white" stroke="#E5E7EB"
                                stroke-width="2" /> <text x="340" y="55" text-anchor="middle" font-size="24">
                                ⚙
                            </text>
                            <rect x="20" y="120" width="360" height="160" rx="8" fill="white" stroke="#E5E7EB"
                                stroke-width="2" />
                            <line x1="40" y1="250" x2="40" y2="140" stroke="#E5E7EB" stroke-width="2" />
                            <line x1="40" y1="250" x2="360" y2="250" stroke="#E5E7EB" stroke-width="2" />
                            <rect x="60" y="180" width="40" height="70" rx="4" fill="#4A90E2" />
                            <rect x="120" y="200" width="40" height="50" rx="4" fill="#4A90E2" />
                            <rect x="180" y="160" width="40" height="90" rx="4" fill="#FFA726" />
                            <rect x="240" y="190" width="40" height="60" rx="4" fill="#FFA726" />
                            <rect x="300" y="170" width="40" height="80" rx="4" fill="#5CB85C" />
                        </svg>
                    </div>
                </div>
            </div>
        </section><!-- Benefits Section -->
        <section class="w-full py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="rounded-3xl p-12 text-center">
                    <h2 class="text-4xl font-bold mb-12">Keuntungan untuk UMKM Anda</h2>
                    <div class="grid md:grid-cols-4 gap-8">
                        <div>
                            <div class="text-5xl font-bold mb-2">
                                70%
                            </div>
                            <p class="opacity-80">Hemat Waktu Administrasi</p>
                        </div>
                        <div>
                            <div class="text-5xl font-bold mb-2">
                                100%
                            </div>
                            <p class="opacity-80">Akurasi Perhitungan</p>
                        </div>
                        <div>
                            <div class="text-5xl font-bold mb-2">
                                50%
                            </div>
                            <p class="opacity-80">Kurangi Biaya Operasional</p>
                        </div>
                        <div>
                            <div class="text-5xl font-bold mb-2">
                                24/7
                            </div>
                            <p class="opacity-80">Akses Kapan Saja</p>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- CTA Section -->
        <section id="cta-section" class="w-full py-20 px-6">
            <div class="max-w-4xl mx-auto text-center rounded-3xl p-12 shadow-2xl">
                <h2 id="cta-headline" class="text-4xl md:text-5xl font-bold mb-6">Siap Tingkatkan Efisiensi HR Anda?
                </h2>
                <p class="text-xl opacity-90 mb-8">Bergabunglah dengan UMKM lainnya yang telah merasakan kemudahan
                    mengelola karyawan</p><button id="cta-button"
                    class="px-10 py-5 rounded-lg font-bold text-xl transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    Hubungi Kami Sekarang </button>
            </div>
        </section><!-- Footer -->
        <footer class="w-full py-8 px-6 text-center opacity-70">
            <p>© 2024 HRIS Solution. Solusi HR Terpercaya untuk UMKM Indonesia</p>
        </footer>
    </div>
    <script>
        const defaultConfig = {
            background_color: '#F0F4F8',
            surface_color: '#FFFFFF',
            text_color: '#1E293B',
            primary_action_color: '#4A90E2',
            secondary_action_color: '#5CB85C',
            font_family: 'system-ui',
            font_size: 16,
            hero_headline: 'Kelola HR UMKM Lebih Mudah, Cepat, dan Akurat',
            hero_subheadline: 'Perkenalkan Aplikasi HRIS Custom Anda: Efisienkan absensi, payroll, dan pantau kinerja karyawan dari satu dashboard intuitif. Dibangun untuk kebutuhan bisnis Anda.',
            hero_cta: 'Lihat Fitur Lengkapnya Sekarang',
            problem_headline: 'Frustrasi dengan Urusan HR yang Memakan Waktu?',
            features_headline: 'Solusi Lengkap dalam Satu Platform',
            cta_headline: 'Siap Tingkatkan Efisiensi HR Anda?',
            cta_button: 'Hubungi Kami Sekarang'
        };

        async function onConfigChange(config) {
            const customFont = config.font_family || defaultConfig.font_family;
            const baseFontStack = 'system-ui, -apple-system, sans-serif';
            const fontFamily = ${ customFont }, ${ baseFontStack };
            const baseSize = config.font_size || defaultConfig.font_size;

            const bgColor = config.background_color || defaultConfig.background_color;
            const surfaceColor = config.surface_color || defaultConfig.surface_color;
            const textColor = config.text_color || defaultConfig.text_color;
            const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
            const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;

            document.body.style.backgroundColor = bgColor;
            document.body.style.color = textColor;
            document.body.style.fontFamily = fontFamily;

            const app = document.getElementById('app');
            app.style.backgroundColor = bgColor;

            const heroSection = document.getElementById('hero-section');
            heroSection.style.backgroundColor = surfaceColor;
            heroSection.style.color = textColor;

            const problemSection = document.getElementById('problem-section');
            problemSection.style.backgroundColor = bgColor;
            problemSection.style.color = textColor;

            const featuresSection = document.getElementById('features-section');
            featuresSection.style.backgroundColor = surfaceColor;
            featuresSection.style.color = textColor;

            const ctaSection = document.getElementById('cta-section');
            ctaSection.style.backgroundColor = bgColor;
            ctaSection.style.color = textColor;

            const ctaSectionInner = ctaSection.querySelector('div > div');
            if (ctaSectionInner) {
                ctaSectionInner.style.backgroundColor = primaryColor;
                ctaSectionInner.style.color = '#FFFFFF';
            }

            const heroHeadline = document.getElementById('hero-headline');
            heroHeadline.style.fontFamily = fontFamily;
            heroHeadline.style.fontSize = ${ baseSize * 3 } px;
            heroHeadline.style.color = textColor;
            heroHeadline.textContent = config.hero_headline || defaultConfig.hero_headline;

            const heroSubheadline = document.getElementById('hero-subheadline');
            heroSubheadline.style.fontFamily = fontFamily;
            heroSubheadline.style.fontSize = ${ baseSize * 1.25 } px;
            heroSubheadline.style.color = textColor;
            heroSubheadline.textContent = config.hero_subheadline || defaultConfig.hero_subheadline;

            const heroCta = document.getElementById('hero-cta');
            heroCta.style.fontFamily = fontFamily;
            heroCta.style.fontSize = ${ baseSize * 1.125 } px;
            heroCta.style.backgroundColor = primaryColor;
            heroCta.style.color = '#FFFFFF';
            heroCta.textContent = config.hero_cta || defaultConfig.hero_cta;

            const problemHeadline = document.getElementById('problem-headline');
            problemHeadline.style.fontFamily = fontFamily;
            problemHeadline.style.fontSize = ${ baseSize * 2.5 } px;
            problemHeadline.style.color = textColor;
            problemHeadline.textContent = config.problem_headline || defaultConfig.problem_headline;

            const featuresHeadline = document.getElementById('features-headline');
            featuresHeadline.style.fontFamily = fontFamily;
            featuresHeadline.style.fontSize = ${ baseSize * 2.5 } px;
            featuresHeadline.style.color = textColor;
            featuresHeadline.textContent = config.features_headline || defaultConfig.features_headline;

            const ctaHeadline = document.getElementById('cta-headline');
            ctaHeadline.style.fontFamily = fontFamily;
            ctaHeadline.style.fontSize = ${ baseSize * 2.5 } px;
            ctaHeadline.style.color = '#FFFFFF';
            ctaHeadline.textContent = config.cta_headline || defaultConfig.cta_headline;

            const ctaButton = document.getElementById('cta-button');
            ctaButton.style.fontFamily = fontFamily;
            ctaButton.style.fontSize = ${ baseSize * 1.25 } px;
            ctaButton.style.backgroundColor = secondaryColor;
            ctaButton.style.color = '#FFFFFF';
            ctaButton.textContent = config.cta_button || defaultConfig.cta_button;

            const allHeadings = document.querySelectorAll('h3');
            allHeadings.forEach(h => {
                h.style.fontFamily = fontFamily;
                h.style.fontSize = ${ baseSize * 1.25 } px;
                h.style.color = textColor;
            });

            const allParagraphs = document.querySelectorAll('p');
            allParagraphs.forEach(p => {
                p.style.fontFamily = fontFamily;
                p.style.fontSize = ${ baseSize } px;
            });

            const problemCards = problemSection.querySelectorAll('.grid > div');
            problemCards.forEach(card => {
                card.style.backgroundColor = surfaceColor;
                const iconBg = card.querySelector('div > div');
                if (iconBg) {
                    iconBg.style.backgroundColor = ${ primaryColor } 20;
                    iconBg.style.color = primaryColor;
                }
            });

            const featureChecks = featuresSection.querySelectorAll('.flex-shrink-0');
            featureChecks.forEach(check => {
                check.style.backgroundColor = ${ secondaryColor } 20;
                check.style.color = secondaryColor;
            });
        }

        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities: (config) => ({
                    recolorables: [
                        {
                            get: () => config.background_color || defaultConfig.background_color,
                            set: (value) => {
                                config.background_color = value;
                                window.elementSdk.setConfig({ background_color: value });
                            }
                        },
                        {
                            get: () => config.surface_color || defaultConfig.surface_color,
                            set: (value) => {
                                config.surface_color = value;
                                window.elementSdk.setConfig({ surface_color: value });
                            }
                        },
                        {
                            get: () => config.text_color || defaultConfig.text_color,
                            set: (value) => {
                                config.text_color = value;
                                window.elementSdk.setConfig({ text_color: value });
                            }
                        },
                        {
                            get: () => config.primary_action_color || defaultConfig.primary_action_color,
                            set: (value) => {
                                config.primary_action_color = value;
                                window.elementSdk.setConfig({ primary_action_color: value });
                            }
                        },
                        {
                            get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
                            set: (value) => {
                                config.secondary_action_color = value;
                                window.elementSdk.setConfig({ secondary_action_color: value });
                            }
                        }
                    ],
                    borderables: [],
                    fontEditable: {
                        get: () => config.font_family || defaultConfig.font_family,
                        set: (value) => {
                            config.font_family = value;
                            window.elementSdk.setConfig({ font_family: value });
                        }
                    },
                    fontSizeable: {
                        get: () => config.font_size || defaultConfig.font_size,
                        set: (value) => {
                            config.font_size = value;
                            window.elementSdk.setConfig({ font_size: value });
                        }
                    }
                }),
                mapToEditPanelValues: (config) => new Map([
                    ['hero_headline', config.hero_headline || defaultConfig.hero_headline],
                    ['hero_subheadline', config.hero_subheadline || defaultConfig.hero_subheadline],
                    ['hero_cta', config.hero_cta || defaultConfig.hero_cta],
                    ['problem_headline', config.problem_headline || defaultConfig.problem_headline],
                    ['features_headline', config.features_headline || defaultConfig.features_headline],
                    ['cta_headline', config.cta_headline || defaultConfig.cta_headline],
                    ['cta_button', config.cta_button || defaultConfig.cta_button]
                ])
            });

            onConfigChange(window.elementSdk.config);
        }
    </script>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9abb21ace7e2fd8f',t:'MTc2NTM1MjgxOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>

</html>
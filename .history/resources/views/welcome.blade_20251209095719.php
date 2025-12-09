<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Muliadis App') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .hero-pattern {
        background-image: radial-gradient(#4f46e5 0.5px, transparent 0.5px), radial-gradient(#4f46e5 0.5px, #ffffff 0.5px);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
        opacity: 0.05;
    }

    /* Animasi Blob */
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }

    .animate-blob {
        animation: blob 7s infinite;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }
    </style>
</head>

<body class="antialiased bg-white text-slate-600 selection:bg-indigo-500 selection:text-white"
    x-data="{ mobileMenuOpen: false }">

    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-lg shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-none">Muliadis</h1>
                        <p class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">Distribution System</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200 hover:-translate-y-0.5">
                        Dashboard <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 text-sm font-bold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Daftar
                    </a>
                    @endif
                    @endauth
                    @endif
                </div>

                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                        class="text-slate-500 hover:text-indigo-600 focus:outline-none p-2">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-white border-b border-slate-100 shadow-xl absolute w-full left-0 z-40" x-cloak>
            <div class="px-4 pt-4 pb-6 space-y-3 flex flex-col">
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="block w-full px-5 py-3 text-center text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-md">
                    Ke Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="block w-full px-5 py-3 text-center text-sm font-bold text-indigo-600 bg-indigo-50 rounded-xl">
                    Masuk Akun
                </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="block w-full px-5 py-3 text-center text-sm font-bold text-white bg-slate-900 rounded-xl shadow-lg">
                    Daftar Sekarang
                </a>
                @endif
                @endauth
            </div>
        </div>
    </nav>

    <div class="relative pt-28 pb-16 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute inset-0 hero-pattern z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] sm:text-xs font-bold uppercase tracking-wide mb-6">
                    PT Mulia Anugerah Distribusindo
                </span>

                <h1
                    class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                    Solusi Cerdas Manajemen <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Distribusi
                        Terintegrasi</span>
                </h1>

                <p class="text-sm sm:text-lg text-slate-500 mb-8 sm:mb-10 leading-relaxed px-4 sm:px-0">
                    Tingkatkan efisiensi operasional, pantau kinerja sales secara real-time, dan kelola inventori gudang
                    dengan presisi tinggi dalam satu aplikasi.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-6 sm:px-0">
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-3.5 text-sm sm:text-base font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-1 text-center">
                        Mulai Sekarang
                    </a>
                    <a href="#about"
                        class="w-full sm:w-auto px-8 py-3.5 text-sm sm:text-base font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all hover:-translate-y-1 text-center">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <div class="mt-12 sm:mt-16 relative px-2 sm:px-0">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl blur opacity-20">
                </div>
                <div
                    class="relative bg-slate-900 rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden border border-slate-800">
                    <div class="h-6 sm:h-8 bg-slate-800 border-b border-slate-700 flex items-center px-4 gap-2">
                        <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-red-500"></div>
                        <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-green-500"></div>
                    </div>
                    <div class="p-1 sm:p-2 bg-slate-900">
                        <div
                            class="bg-slate-800 rounded-lg h-48 sm:h-64 md:h-96 flex flex-col items-center justify-center text-slate-600">
                            <i class="fas fa-chart-line text-4xl sm:text-6xl mb-3 sm:mb-4 opacity-50"></i>
                            <p class="text-xs sm:text-base">Dashboard Preview</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 sm:py-24 bg-slate-50 border-t border-slate-200" id="features">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Fitur Unggulan</h2>
                <p class="text-sm sm:text-base text-slate-500 mt-2">Teknologi yang kami gunakan untuk mendukung bisnis
                    Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div
                    class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-indigo-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-indigo-600 text-xl sm:text-2xl mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">Real-time Monitoring</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Pantau omzet penjualan, piutang, dan stok barang secara langsung melalui dashboard interaktif.
                    </p>
                </div>

                <div
                    class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-emerald-600 text-xl sm:text-2xl mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">Analisa Salesman</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Ukur KPI salesman mulai dari Target Omzet, Effective Call (EC), hingga Outlet Active (OA).
                    </p>
                </div>

                <div
                    class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-orange-600 text-xl sm:text-2xl mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">Manajemen Stok</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Kelola data produk, supplier, dan retur barang dengan sistem pencatatan yang terstruktur.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 sm:py-24 bg-white" id="about">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
                <div class="w-full lg:w-1/2 text-center lg:text-left order-2 lg:order-1">
                    <span class="text-indigo-600 font-bold tracking-wider uppercase text-xs sm:text-sm">Tentang
                        Perusahaan</span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2 sm:mt-3 mb-4 sm:mb-6">
                        PT. Mulia Anugerah Distribusindo
                    </h2>
                    <p class="text-slate-500 text-sm sm:text-lg leading-relaxed mb-4 sm:mb-6">
                        Kami adalah perusahaan distribusi yang berkomitmen untuk memberikan layanan terbaik dalam
                        pendistribusian produk ke seluruh pelosok negeri.
                    </p>
                    <p class="text-slate-500 text-sm sm:text-lg leading-relaxed mb-8 hidden sm:block">
                        Dengan dukungan armada logistik yang kuat dan sistem manajemen digital "Muliadis App", kami
                        memastikan setiap produk sampai ke tangan konsumen dengan cepat, tepat, dan efisien.
                    </p>

                    <div class="grid grid-cols-2 gap-4 sm:gap-6">
                        <div class="bg-slate-50 p-4 rounded-xl">
                            <h4 class="text-2xl sm:text-3xl font-bold text-slate-900">15+</h4>
                            <p class="text-xs sm:text-sm text-slate-500">Tahun Pengalaman</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl">
                            <h4 class="text-2xl sm:text-3xl font-bold text-slate-900">10k+</h4>
                            <p class="text-xs sm:text-sm text-slate-500">Outlet Terjangkau</p>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 relative order-1 lg:order-2">
                    <div
                        class="hidden sm:block absolute -top-4 -left-4 w-56 h-56 lg:w-72 lg:h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                    </div>
                    <div
                        class="hidden sm:block absolute -bottom-8 -right-4 w-56 h-56 lg:w-72 lg:h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                    </div>

                    <div
                        class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Gudang Distribusi" class="w-full h-auto object-cover">
                        <div
                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 sm:p-8">
                            <p class="text-white font-medium text-sm sm:text-base">Profesionalisme & Integritas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-slate-900 text-white py-8 sm:py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <span class="font-bold text-lg tracking-wide">Muliadis App</span>
                </div>
                <div class="text-slate-400 text-xs sm:text-sm text-center md:text-right">
                    <p>&copy; {{ date('Y') }} PT Mulia Anugerah Distribusindo. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .hero-pattern {
        background-color: #ffffff;
        background-image: radial-gradient(#4f46e5 0.5px, transparent 0.5px), radial-gradient(#4f46e5 0.5px, #ffffff 0.5px);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
        opacity: 0.05;
    }
    </style>
</head>

<body class="antialiased bg-white text-slate-600 selection:bg-indigo-500 selection:text-white">

    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight leading-none">Muliadis</h1>
                        <p class="text-[10px] font-medium text-slate-500 tracking-wider uppercase">Distribution System
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200">
                        Dashboard <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 text-sm font-bold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        Masuk
                    </a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="hidden sm:inline-flex px-5 py-2.5 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Daftar Akun
                    </a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 hero-pattern z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wide mb-6">
                    PT Mulia Anugerah Distribusindo
                </span>
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                    Solusi Cerdas Manajemen <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Distribusi
                        Terintegrasi</span>
                </h1>
                <p class="text-lg text-slate-500 mb-10 leading-relaxed">
                    Tingkatkan efisiensi operasional, pantau kinerja sales secara real-time, dan kelola inventori gudang
                    dengan presisi tinggi dalam satu aplikasi terpadu.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}"
                        class="px-8 py-4 text-base font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-1">
                        Mulai Sekarang
                    </a>
                    <a href="#about"
                        class="px-8 py-4 text-base font-bold text-slate-700 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-all hover:-translate-y-1">
                        Tentang Kami
                    </a>
                </div>
            </div>

            <div class="mt-16 relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl blur opacity-20">
                </div>
                <div class="relative bg-slate-900 rounded-2xl shadow-2xl overflow-hidden border border-slate-800">
                    <div class="h-8 bg-slate-800 border-b border-slate-700 flex items-center px-4 gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                    <div class="p-2">
                        <div
                            class="bg-slate-800 rounded-xl h-64 md:h-96 flex items-center justify-center text-slate-600">
                            <div class="text-center">
                                <i class="fas fa-chart-line text-6xl mb-4 opacity-50"></i>
                                <p>Dashboard Preview</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 bg-slate-50" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Fitur Unggulan</h2>
                <p class="text-slate-500 mt-4">Teknologi yang kami gunakan untuk mendukung bisnis Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div
                        class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-2xl mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Real-time Monitoring</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Pantau omzet penjualan, piutang, dan stok barang secara langsung melalui dashboard interaktif
                        yang responsif.
                    </p>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div
                        class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Analisa Salesman</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Ukur KPI salesman mulai dari Target Omzet, Effective Call (EC), hingga Outlet Active (OA) secara
                        akurat.
                    </p>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div
                        class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 text-2xl mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Stok</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Kelola data produk, supplier, dan retur barang dengan sistem pencatatan yang terstruktur dan
                        mudah diaudit.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 bg-white" id="about">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm">Tentang Perusahaan</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-6">
                        PT. Mulia Anugerah Distribusindo
                    </h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-6">
                        Kami adalah perusahaan distribusi yang berkomitmen untuk memberikan layanan terbaik dalam
                        pendistribusian produk ke seluruh pelosok negeri.
                    </p>
                    <p class="text-slate-500 text-lg leading-relaxed mb-8">
                        Dengan dukungan armada logistik yang kuat dan sistem manajemen digital "Muliadis App", kami
                        memastikan setiap produk sampai ke tangan konsumen dengan cepat, tepat, dan efisien.
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-3xl font-bold text-slate-900">15+</h4>
                            <p class="text-sm text-slate-500">Tahun Pengalaman</p>
                        </div>
                        <div>
                            <h4 class="text-3xl font-bold text-slate-900">10k+</h4>
                            <p class="text-sm text-slate-500">Outlet Terjangkau</p>
                        </div>
                        <div>
                            <h4 class="text-3xl font-bold text-slate-900">50+</h4>
                            <p class="text-sm text-slate-500">Principal Partner</p>
                        </div>
                        <div>
                            <h4 class="text-3xl font-bold text-slate-900">24/7</h4>
                            <p class="text-sm text-slate-500">Support System</p>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div
                        class="absolute -top-4 -left-4 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                    </div>
                    <div
                        class="absolute -bottom-8 -right-4 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                    </div>

                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Gudang Distribusi" class="w-full h-auto object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                            <p class="text-white font-medium">Profesionalisme & Integritas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-slate-900 text-white py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-3 mb-4 md:mb-0">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <span class="font-bold text-lg tracking-wide">Muliadis App</span>
                </div>
                <div class="text-slate-400 text-sm text-center md:text-right">
                    <p>&copy; {{ date('Y') }} PT Mulia Anugerah Distribusindo. All rights reserved.</p>
                    <p class="mt-1">Developed for Skripsi/Magang purpose.</p>
                </div>
            </div>
        </div>
    </footer>

    <style>
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
</body>

</html>
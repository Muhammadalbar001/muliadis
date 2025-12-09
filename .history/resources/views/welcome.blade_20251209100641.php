<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Muliadis App') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi Background Gradient Bergerak */
        .gradient-bg {
            background: linear-gradient(-45deg, #1e1b4b, #312e81, #4338ca, #3730a3);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animasi Floating (Melayang) */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-600" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <nav class="fixed w-full z-50 transition-all duration-300"
         :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-100 py-3' : 'bg-transparent py-5'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 bg-gradient-to-br from-indigo-500 to-purple-600">
                        <i class="fas fa-truck-fast text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight leading-none transition-colors"
                            :class="scrolled ? 'text-slate-900' : 'text-white'">
                            Muliadis
                        </h1>
                        <p class="text-[9px] font-bold tracking-wider uppercase transition-colors"
                           :class="scrolled ? 'text-slate-500' : 'text-indigo-200'">
                            Distribution System
                        </p>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5">
                                Dashboard <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-5 py-2.5 text-sm font-bold rounded-full transition-all"
                               :class="scrolled ? 'text-slate-600 hover:text-indigo-600' : 'text-white hover:text-indigo-200'">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm font-bold text-indigo-900 bg-white rounded-full hover:bg-slate-100 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="focus:outline-none p-2 rounded-lg transition-colors"
                        :class="scrolled ? 'text-slate-600 hover:bg-slate-100' : 'text-white hover:bg-white/10'">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-white border-b border-slate-100 shadow-xl absolute w-full left-0 z-40">
            <div class="px-4 pt-4 pb-6 space-y-3 flex flex-col">
                <a href="{{ route('login') }}" class="block w-full px-5 py-3 text-center text-sm font-bold text-slate-700 bg-slate-50 rounded-xl">Masuk Akun</a>
                <a href="{{ route('register') }}" class="block w-full px-5 py-3 text-center text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-md">Daftar Sekarang</a>
            </div>
        </div>
    </nav>

    <div class="relative gradient-bg pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden text-white">
        
        <div class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm mb-6">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-medium text-indigo-100 tracking-wide uppercase">Sistem Distribusi #1</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                        Kelola Bisnis <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-blue-300 to-purple-300">Tanpa Batas.</span>
                    </h1>
                    
                    <p class="text-lg text-indigo-100 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Platform manajemen distribusi modern untuk PT Mulia Anugerah Distribusindo. Pantau omzet, stok, dan kinerja sales dalam satu genggaman.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="px-8 py-4 text-base font-bold text-indigo-900 bg-white rounded-full hover:bg-slate-100 transition-all shadow-lg hover:shadow-white/20 hover:-translate-y-1">
                            Mulai Sekarang
                        </a>
                        <a href="#features" class="px-8 py-4 text-base font-bold text-white border border-white/30 rounded-full hover:bg-white/10 transition-all backdrop-blur-sm">
                            Lihat Fitur
                        </a>
                    </div>
                    
                    <div class="mt-10 pt-8 border-t border-white/10 grid grid-cols-3 gap-4">
                        <div>
                            <h4 class="text-2xl font-bold text-white">100%</h4>
                            <p class="text-xs text-indigo-200">Real-time</p>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-white">24/7</h4>
                            <p class="text-xs text-indigo-200">Akses</p>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-white">Secure</h4>
                            <p class="text-xs text-indigo-200">Data Aman</p>
                        </div>
                    </div>
                </div>

                <div class="relative lg:h-auto animate-float hidden md:block">
                    <div class="relative bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-2xl p-2 shadow-2xl shadow-indigo-500/20 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <div class="h-8 bg-slate-800 rounded-t-xl flex items-center px-4 gap-2 mb-1">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="bg-slate-800 rounded-xl h-64 sm:h-80 md:h-96 flex flex-col items-center justify-center text-slate-500 overflow-hidden relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10"></div>
                            <i class="fas fa-chart-line text-6xl mb-4 text-indigo-500 opacity-50 group-hover:scale-110 transition-transform duration-500"></i>
                            <p class="font-medium">Dashboard Interface</p>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl animate-bounce" style="animation-duration: 3s;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase">Omzet</p>
                                <p class="text-sm font-bold text-slate-800">+25% Naik</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="absolute bottom-0 w-full leading-none text-slate-50">
            <svg class="relative block w-full h-[50px] sm:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <div class="py-20 sm:py-32 bg-slate-50 relative" id="features">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-xs">Fitur Utama</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">Teknologi Terintegrasi</h2>
                <p class="text-slate-500 mt-4 text-lg">Semua yang Anda butuhkan untuk mengelola distribusi ada di sini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Monitoring Real-time</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Lihat pergerakan omzet, stok, dan piutang secara langsung tanpa menunggu laporan akhir bulan.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-purple-100 transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-purple-200 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kinerja Salesman</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Analisa KPI salesman mulai dari pencapaian target, kunjungan efektif (EC), hingga penyebaran toko (OA).
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-orange-100 transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-orange-200 group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kontrol Inventori</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Kelola stok masuk dan keluar, retur barang, serta supplier dengan sistem pencatatan yang akurat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 sm:py-24">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="bg-indigo-900 rounded-3xl p-8 sm:p-16 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-full opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-purple-600 rounded-full mix-blend-screen filter blur-3xl opacity-30"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-cyan-600 rounded-full mix-blend-screen filter blur-3xl opacity-30"></div>

                <div class="relative z-10">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-6">
                        Siap Mengoptimalkan Distribusi Anda?
                    </h2>
                    <p class="text-indigo-200 text-lg mb-10 max-w-2xl mx-auto">
                        Bergabunglah dengan sistem manajemen modern PT Mulia Anugerah Distribusindo sekarang juga.
                    </p>
                    <a href="{{ route('login') }}" class="inline-block px-10 py-4 text-base font-bold text-indigo-900 bg-white rounded-full hover:bg-slate-100 transition-all shadow-xl hover:-translate-y-1">
                        Masuk ke Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <span class="font-bold text-slate-900 text-lg">Muliadis App</span>
                </div>
                <div class="text-slate-500 text-sm text-center md:text-right">
                    &copy; {{ date('Y') }} PT Mulia Anugerah Distribusindo. <br class="sm:hidden"> All rights reserved.
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
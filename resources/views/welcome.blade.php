<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muliadis - Corporate Portal</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Corporate Pattern Background */
    .hero-pattern {
        background-color: #0f172a;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231e293b' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-900 text-white p-1.5 rounded-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-lg leading-tight tracking-tight">PT. Mulia Anugerah
                            Distribusindo</span>
                        <span class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold">Distribution
                            Management System</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-900 text-white text-sm font-semibold rounded-lg hover:bg-blue-800 transition-all shadow-md">
                        <i class="fas fa-columns"></i> Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-all shadow-sm">
                        <i class="fas fa-sign-in-alt"></i> Login Portal
                    </a>
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-pattern relative text-white py-20 lg:py-28 overflow-hidden">

        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-blue-900/40 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div
                        class="inline-block px-3 py-1 bg-blue-500/20 border border-blue-400/30 rounded-full text-blue-200 text-xs font-bold uppercase tracking-wider mb-6">
                        Internal System v2.0
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-6 leading-tight text-white">
                        Sistem Informasi <br> <span class="text-blue-400">Manajemen Distribusi</span>
                    </h1>
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed max-w-xl">
                        Platform terintegrasi untuk pengelolaan stok, penjualan, piutang, dan pelaporan PT. Mulia
                        Anugerah Distribusindo. Akses data <i>real-time</i> untuk pengambilan keputusan yang presisi.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('login') }}"
                            class="px-7 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg shadow-lg shadow-blue-900/50 transition-all flex items-center gap-2">
                            Akses Sistem <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <a href="#modul"
                            class="px-7 py-3.5 bg-white/5 border border-white/10 hover:bg-white/10 text-white font-semibold rounded-lg transition-all">
                            Lihat Modul
                        </a>
                    </div>

                    <div class="mt-10 flex items-center gap-6 text-sm text-slate-400 border-t border-white/10 pt-6">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Server Online
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-slate-500"></i> Secure Connection
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-database text-slate-500"></i> Last Backup: {{ date('d M Y') }}
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block relative">
                    <div
                        class="relative bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl p-6 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <div class="flex items-center gap-4 border-b border-slate-700 pb-4 mb-4">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="h-2 w-32 bg-slate-700 rounded-full"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-700/50 p-4 rounded-xl h-24 w-full animate-pulse"></div>
                            <div class="bg-slate-700/50 p-4 rounded-xl h-24 w-full animate-pulse"></div>
                            <div class="bg-slate-700/50 p-4 rounded-xl h-40 w-full col-span-2 mt-2"></div>
                        </div>
                        <div
                            class="absolute -bottom-6 -left-6 bg-white text-slate-900 p-4 rounded-xl shadow-xl border border-slate-200 flex items-center gap-3">
                            <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i
                                    class="fas fa-chart-line"></i></div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase">Performance</p>
                                <p class="text-lg font-bold">+24.5%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="modul" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-blue-600 font-bold text-xs uppercase tracking-widest">Modul Operasional</span>
                <h2 class="text-3xl font-bold text-slate-900 mt-2 mb-4">Fungsionalitas Utama Sistem</h2>
                <p class="text-slate-600">Sistem dirancang untuk efisiensi tinggi dalam menangani alur distribusi
                    barang, mulai dari pemesanan hingga pelaporan keuangan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div
                    class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-boxes-stacked text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Penjualan & Stok</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Pencatatan faktur penjualan real-time, manajemen
                        stok gudang, dan analisis pergerakan barang.</p>
                </div>

                <div
                    class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-exchange-alt text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Retur Barang</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Manajemen pengembalian barang (BS/Good), potong
                        nota otomatis, dan validasi retur.</p>
                </div>

                <div
                    class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Kontrol Piutang</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Monitoring umur piutang (Aging AR), limit kredit
                        pelanggan, dan status penagihan.</p>
                </div>

                <div
                    class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Laporan Eksekutif</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Rekapitulasi omzet, kinerja salesman,
                        profitabilitas, dan laporan collection harian.</p>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-200 py-8">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <span class="font-bold text-slate-800">PT. Mulia Anugerah Distribusindo</span>
                <p class="text-xs text-slate-500 mt-1">
                    &copy; {{ date('Y') }} Internal System. All rights reserved. <br>
                    Dikembangkan oleh Tim IT.
                </p>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Support</a>
                <a href="#" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Privacy Policy</a>
                <a href="#" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>

</html>
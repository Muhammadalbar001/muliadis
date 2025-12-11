<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muliadis - Sistem Distribusi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .gradient-bg {
        background: linear-gradient(-45deg, #0f172a, #1e1b4b, #312e81, #1e293b);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .text-glow {
        text-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
    }
    </style>
</head>

<body class="gradient-bg min-h-screen text-white selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <nav class="fixed w-full z-50 top-0 start-0 border-b border-white/5 bg-[#0f172a]/50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between px-6 py-4">
            <a href="#" class="flex items-center gap-3 rtl:space-x-reverse">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-cube text-white text-lg"></i>
                </div>
                <span class="self-center text-xl font-bold whitespace-nowrap tracking-tight">Muliadis</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-4 rtl:space-x-reverse">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-800 font-bold rounded-xl text-sm px-5 py-2.5 text-center transition-all shadow-lg shadow-indigo-500/30">Dashboard</a>
                @else
                <a href="{{ route('login') }}"
                    class="text-white hover:text-indigo-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">Log
                    in</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="text-white bg-white/10 hover:bg-white/20 border border-white/10 focus:ring-4 focus:outline-none focus:ring-gray-700 font-bold rounded-xl text-sm px-5 py-2.5 text-center transition-all backdrop-blur-sm">Daftar</a>
                @endif
                @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-6">
        <div class="max-w-7xl mx-auto text-center relative z-10">

            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-6 animate-pulse">
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span> Sistem Distribusi Terintegrasi
            </div>

            <h1
                class="mb-6 text-4xl font-extrabold tracking-tight leading-none md:text-6xl lg:text-7xl text-transparent bg-clip-text bg-gradient-to-r from-white via-indigo-100 to-slate-400 text-glow">
                Kelola Bisnis Distribusi <br> Lebih Cerdas & Efisien.
            </h1>
            <p class="mb-10 text-lg font-normal text-slate-400 lg:text-xl sm:px-16 lg:px-48 max-w-4xl mx-auto">
                Platform manajemen distribusi end-to-end. Pantau penjualan, kelola retur, monitoring piutang, hingga
                pelunasan dalam satu dashboard real-time.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}"
                    class="inline-flex justify-center items-center py-3.5 px-8 text-base font-bold text-white rounded-2xl bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-900 transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-1">
                    Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#fitur"
                    class="inline-flex justify-center items-center py-3.5 px-8 text-base font-bold text-white rounded-2xl border border-white/10 hover:bg-white/5 focus:ring-4 focus:ring-gray-700 transition-all">
                    Pelajari Fitur
                </a>
            </div>
        </div>

        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full max-w-5xl z-0 pointer-events-none opacity-40">
            <div
                class="absolute top-0 left-0 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
            </div>
            <div
                class="absolute top-0 right-0 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-0 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000">
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 bg-slate-900/50 relative border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Fitur Unggulan</h2>
                <p class="text-slate-400">Semua yang Anda butuhkan untuk mengelola operasional distribusi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="glass-card p-8 rounded-3xl hover:bg-white/5 transition-all group hover:-translate-y-2 duration-300">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shopping-cart text-emerald-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Penjualan</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Rekapitulasi faktur harian, analisis omzet per
                        salesman, dan monitoring performa produk terlaris.</p>
                </div>

                <div
                    class="glass-card p-8 rounded-3xl hover:bg-white/5 transition-all group hover:-translate-y-2 duration-300">
                    <div
                        class="w-14 h-14 rounded-2xl bg-rose-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-undo text-rose-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Retur Barang</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Kelola pengembalian barang dari toko dengan cepat,
                        potong nota otomatis, dan validasi stok.</p>
                </div>

                <div
                    class="glass-card p-8 rounded-3xl hover:bg-white/5 transition-all group hover:-translate-y-2 duration-300">
                    <div
                        class="w-14 h-14 rounded-2xl bg-orange-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice-dollar text-orange-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Piutang (AR)</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Monitoring tagihan outstanding, aging schedule
                        piutang macet, dan pengingat jatuh tempo.</p>
                </div>

                <div
                    class="glass-card p-8 rounded-3xl hover:bg-white/5 transition-all group hover:-translate-y-2 duration-300">
                    <div
                        class="w-14 h-14 rounded-2xl bg-cyan-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-wallet text-cyan-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Collection</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Pencatatan pelunasan faktur, cash flow harian, dan
                        rekap setoran penagih/collector.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-10 border-t border-white/5 text-center relative z-10 bg-[#0f172a]">
        <p class="text-slate-500 text-sm">
            &copy; {{ date('Y') }} PT. Mulia Anugerah Distribusindo. All rights reserved. <br>
            <span class="text-xs text-slate-600 mt-2 block">Built with Laravel & Livewire</span>
        </p>
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

    .animation-delay-4000 {
        animation-delay: 4s;
    }
    </style>
</body>

</html>
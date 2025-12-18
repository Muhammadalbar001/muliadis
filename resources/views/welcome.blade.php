<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT MULIA ANUGERAH DISTRIBUSINDO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #050505;
        color: #e2e8f0;
        scroll-behavior: smooth;
    }

    /* Latar Belakang Utama */
    .hero-bg {
        background: linear-gradient(rgba(10, 10, 10, 0.75), rgba(10, 10, 10, 0.85)),
            url('/images/bg-welcome.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* Efek Glassmorphism Navbar */
    .glass-nav {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Efek Glassmorphism Card */
    .glass-card {
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 2.5rem;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .glass-card:hover {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(59, 130, 246, 0.4);
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .text-blue-glow {
        color: #60a5fa;
        text-shadow: 0 0 15px rgba(96, 165, 250, 0.4);
    }

    .btn-glass {
        background: rgba(59, 130, 246, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .btn-glass:hover {
        background: rgba(59, 130, 246, 1);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        transform: scale(1.05);
    }
    </style>
</head>

<body class="antialiased hero-bg">

    <nav class="fixed top-0 w-full z-[100] glass-nav h-20">
        <div class="max-w-7xl mx-auto px-8 h-full flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-truck-fast text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-sm font-black text-white tracking-tighter uppercase leading-none">PT MULIA ANUGERAH
                    </h1>
                    <p class="text-[9px] font-bold text-blue-400 tracking-[0.3em] uppercase mt-1">Distribusindo</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="text-[10px] font-black text-slate-300 hover:text-white uppercase tracking-widest transition-colors">Dashboard</a>
                @else
                <a href="{{ route('login') }}"
                    class="btn-glass px-8 py-3 text-white rounded-2xl text-[10px] font-black tracking-widest uppercase">
                    Login Portal
                </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 px-6 max-w-7xl mx-auto space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-auto">

            <div
                class="md:col-span-8 md:row-span-2 glass-card p-12 flex flex-col justify-center space-y-8 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 opacity-[0.05] pointer-events-none">
                    <i class="fas fa-boxes-packing text-[20rem] -rotate-12"></i>
                </div>

                <div class="relative z-10 space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-500/10 text-blue-400 rounded-full text-[10px] font-black uppercase tracking-[0.3em] border border-blue-500/20 w-fit">
                        Regional Distribution Expert
                    </div>
                    <h2 class="text-5xl md:text-7xl font-black text-white leading-[0.95] tracking-tighter uppercase">
                        Reliable <br><span class="text-blue-glow">Logistic Hub.</span>
                    </h2>
                    <p class="text-slate-400 font-medium max-w-md text-lg leading-relaxed">
                        Manajemen distribusi yang presisi dan transparan untuk produk Consumer Goods & Farmasi di
                        seluruh wilayah Kalimantan.
                    </p>
                </div>
            </div>

            <div class="md:col-span-4 glass-card p-8 flex flex-col justify-between border-blue-500/20">
                <h3 class="text-blue-400 text-[10px] font-black uppercase tracking-[0.4em]">Our Vision</h3>
                <p class="text-xl font-bold text-white leading-tight uppercase italic mt-4">"Menjadi pilar distribusi
                    yang kokoh melalui integritas teknologi."</p>
                <div class="w-10 h-1 bg-blue-600 mt-6 rounded-full"></div>
            </div>

            <div class="md:col-span-4 glass-card p-2">
                <a href="{{ route('login') }}"
                    class="w-full h-full bg-white/5 rounded-[2rem] flex flex-col justify-between p-8 group transition-all hover:bg-white/10">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-right-to-bracket text-white"></i>
                        </div>
                        <i class="fas fa-chevron-right text-slate-500"></i>
                    </div>
                    <div>
                        <span class="text-xs font-black text-white uppercase tracking-widest block">Masuk Sistem</span>
                        <p class="text-[9px] text-slate-500 uppercase font-bold mt-1">Akses Database Pegawai</p>
                    </div>
                </a>
            </div>

            <div class="md:col-span-3 glass-card p-8 text-center flex flex-col justify-center">
                <span class="text-3xl font-black text-white italic">Live</span>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-2">Data Monitoring</p>
            </div>

            <div class="md:col-span-3 glass-card p-8 text-center flex flex-col justify-center">
                <span class="text-3xl font-black text-blue-400">100%</span>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-2">Accurate Reports</p>
            </div>

            <div class="md:col-span-6 glass-card p-10 flex items-center gap-8 relative overflow-hidden bg-blue-600/5">
                <div
                    class="w-16 h-16 bg-blue-600/20 rounded-2xl flex items-center justify-center text-blue-500 text-3xl">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-black text-white uppercase tracking-widest">Integrated Ecosystem</h4>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Sistem terpusat yang menghubungkan
                        armada, gudang, dan outlet secara langsung.</p>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-12 text-center">
        <p class="text-[9px] font-bold text-slate-700 uppercase tracking-[0.5em]">© 2025 PT MULIA ANUGERAH DISTRIBUSINDO
        </p>
    </footer>

</body>

</html>
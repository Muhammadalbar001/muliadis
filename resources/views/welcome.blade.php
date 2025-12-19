<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

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
        color: #cbd5e1;
    }

    .hero-bg {
        background: linear-gradient(rgba(5, 5, 5, 0.75), rgba(5, 5, 5, 0.9)),
            url('/images/bg-welcome.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    .glass-nav {
        background: rgba(5, 5, 5, 0.5);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .glass-card {
        background: rgba(20, 20, 20, 0.3);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1.5rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .glass-card:hover {
        background: rgba(30, 30, 30, 0.5);
        border-color: rgba(59, 130, 246, 0.3);
        transform: translateY(-4px);
    }

    /* Animasi saat Scroll Terdeteksi */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .text-blue-glow {
        color: #3b82f6;
        text-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    }

    .btn-portal {
        background: #3b82f6;
        transition: all 0.3s ease;
    }

    .btn-portal:hover {
        background: #2563eb;
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #050505;
    }

    ::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 10px;
    }
    </style>
</head>

<body class="antialiased hero-bg font-sans">

    <nav class="fixed top-0 w-full z-[100] glass-nav h-16">
        <div class="max-w-[1440px] mx-auto px-8 h-full flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-truck-fast text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xs font-black text-white tracking-tight uppercase leading-none">PT MULIA ANUGERAH
                    </h1>
                    <p class="text-[8px] font-bold text-blue-500 tracking-[0.2em] uppercase mt-0.5">Distribusindo</p>
                </div>
            </div>

            <div class="flex items-center gap-8 text-[10px] font-black uppercase tracking-widest">
                <a href="#tujuan" class="text-slate-400 hover:text-white transition-all hover:tracking-[0.2em]">Visi &
                    Tujuan</a>
                <a href="#fitur" class="text-slate-400 hover:text-white transition-all hover:tracking-[0.2em]">Fitur
                    Utama</a>
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="text-blue-400 hover:text-blue-300 transition-colors">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn-portal px-6 py-2 text-white rounded-xl shadow-lg">Portal
                    Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-20 px-8 max-w-[1440px] mx-auto space-y-24">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 reveal">

            <div
                class="md:col-span-8 glass-card p-12 flex flex-col justify-center min-h-[400px] relative overflow-hidden group">
                <div
                    class="absolute -right-20 -bottom-20 opacity-[0.03] pointer-events-none transition-transform group-hover:scale-110 duration-700">
                    <i class="fas fa-globe-asia text-[25rem]"></i>
                </div>
                <div class="relative z-10 space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-[9px] font-bold uppercase tracking-[0.2em] border border-blue-500/10">
                        Smart Supply Chain Management
                    </div>
                    <h2 class="text-5xl md:text-7xl font-black text-white leading-[0.9] tracking-tighter uppercase">
                        Efficiency <br><span class="text-blue-glow text-4xl md:text-6xl">Without Compromise.</span>
                    </h2>
                    <p class="text-slate-400 font-medium max-w-lg text-base leading-relaxed">
                        Platform manajemen distribusi terpadu yang dirancang khusus untuk memonitor performa, inventori,
                        dan logistik secara real-time.
                    </p>
                </div>
            </div>

            <div id="tujuan"
                class="md:col-span-4 glass-card p-8 flex flex-col justify-center border-l-4 border-l-blue-600 scroll-mt-24">
                <h3 class="text-blue-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4">Strategic Purpose</h3>
                <p class="text-xl font-medium text-white leading-relaxed italic mb-6">
                    "Mengubah data distribusi menjadi keputusan bisnis yang cerdas, cepat, dan akurat untuk mendominasi
                    pasar Kalimantan."
                </p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-check-circle text-blue-500"></i> Integrasi Data Terpusat
                    </div>
                    <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-check-circle text-blue-500"></i> Transparansi Kinerja Sales
                    </div>
                    <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-check-circle text-blue-500"></i> Optimalisasi Piutang & Cashflow
                    </div>
                </div>
            </div>
        </div>

        <div id="fitur" class="space-y-12 reveal scroll-mt-24">
            <div class="flex flex-col items-center text-center space-y-2">
                <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Core Ecosystem Fitur</h2>
                <div class="h-1 w-20 bg-blue-600 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="glass-card p-8 group hover:bg-blue-600/5">
                    <div
                        class="w-12 h-12 bg-blue-600/10 rounded-2xl flex items-center justify-center text-blue-500 text-xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">Analisa Penjualan</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Monitoring pencapaian target
                        harian dan bulanan secara otomatis per divisi.</p>
                </div>

                <div class="glass-card p-8 group hover:bg-emerald-600/5">
                    <div
                        class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-500 text-xl mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">Kinerja Sales</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Evaluasi produktivitas sales
                        melalui metrik Outlet Aktif (OA) dan Efektif Call (EC).</p>
                </div>

                <div class="glass-card p-8 group hover:bg-orange-600/5">
                    <div
                        class="w-12 h-12 bg-orange-600/10 rounded-2xl flex items-center justify-center text-orange-500 text-xl mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">Kontrol Piutang</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Manajemen Account Receivable untuk
                        meminimalisir piutang macet dan overdue.</p>
                </div>

                <div class="glass-card p-8 group hover:bg-purple-600/5">
                    <div
                        class="w-12 h-12 bg-purple-600/10 rounded-2xl flex items-center justify-center text-purple-500 text-xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">Mix Supplier</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Analisa penetrasi produk dari
                        berbagai supplier untuk distribusi yang merata.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
            <div class="md:col-span-1 glass-card p-8 flex flex-col justify-center items-center text-center space-y-4">
                <span class="text-xs font-black text-blue-500 uppercase tracking-[0.3em]">Data Integrity</span>
                <div class="text-4xl font-black text-white">100%</div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Accurate Digital Reports</p>
            </div>

            <div class="md:col-span-2 glass-card p-1.5 group">
                <a href="{{ route('login') }}"
                    class="w-full h-full bg-blue-600/5 rounded-[1.3rem] flex items-center justify-between p-10 transition-all group-hover:bg-blue-600/10">
                    <div class="space-y-2">
                        <h4 class="text-2xl font-black text-white uppercase tracking-tighter">Siap Untuk Memulai?</h4>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-widest">Masuk ke portal
                            administrasi untuk akses database penuh.</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-xl shadow-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>

    </main>

    <footer class="py-12 border-t border-white/5">
        <div
            class="max-w-[1440px] mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-6 text-slate-600">
            <p class="text-[9px] font-black uppercase tracking-[0.4em]">© 2025 PT MULIA ANUGERAH DISTRIBUSINDO</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-blue-500 transition-colors"><i class="fab fa-linkedin-in text-xs"></i></a>
                <a href="#" class="hover:text-blue-500 transition-colors"><i class="fas fa-globe text-xs"></i></a>
            </div>
        </div>
    </footer>

    <script>
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 150;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            }
        }
    }
    window.addEventListener("scroll", reveal);
    // Jalankan sekali saat load
    reveal();
    </script>

</body>

</html>
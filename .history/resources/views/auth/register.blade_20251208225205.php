<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Muliadis App</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    </style>
</head>

<body class="bg-white overflow-hidden">

    <div class="flex min-h-screen" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">

        <div class="hidden lg:block lg:w-1/2 relative bg-indigo-900" x-show="show"
            x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0">

            <img src="https://images.unsplash.com/photo-1616401784845-180882ba9ba8?q=80&w=2070&auto=format&fit=crop"
                alt="Distribution" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">

            <div class="absolute inset-0 flex flex-col justify-between p-20 z-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-white font-bold text-xl tracking-wide">Muliadis App</span>
                </div>

                <div class="text-white max-w-lg">
                    <h3 class="text-4xl font-bold leading-tight mb-4">Bergabung Bersama Kami.</h3>
                    <p class="text-indigo-200 text-lg leading-relaxed">
                        Daftarkan akun baru untuk mulai mengelola operasional distribusi perusahaan Anda.
                    </p>
                </div>

                <div class="flex gap-2">
                    <div class="w-2 h-1.5 bg-white/30 rounded-full"></div>
                    <div class="w-12 h-1.5 bg-white rounded-full"></div>
                    <div class="w-2 h-1.5 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 lg:px-24 py-12 bg-white" x-show="show"
            x-transition:enter="transition ease-out duration-700 delay-100"
            x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">

            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Buat Akun Baru 🚀</h2>
                <p class="text-slate-500 mt-2 text-sm">Lengkapi data diri Anda untuk akses sistem.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="Nama Anda">
                </div>
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="nama@perusahaan.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="Ulangi password">
                </div>
                <button type="submit"
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1 mt-4">
                    Daftar Akun
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Login disini</a>
            </p>
        </div>

    </div>
</body>

</html>
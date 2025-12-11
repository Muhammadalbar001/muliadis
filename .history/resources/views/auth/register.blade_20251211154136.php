<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Muliadis App</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .gradient-bg {
        background: linear-gradient(-45deg, #1e1b4b, #312e81, #4338ca, #3730a3);
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

<body class="gradient-bg min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 w-64 h-64 sm:w-96 sm:h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
        </div>
        <div
            class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4 w-64 h-64 sm:w-96 sm:h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
        </div>
    </div>

    <div class="relative w-full max-w-md bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-6 sm:p-8 transition-all duration-500 my-4"
        x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100">

        <div class="text-center mb-6">
            <a href="/"
                class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white text-xl shadow-lg shadow-indigo-500/30 mb-3 hover:scale-110 transition-transform">
                <i class="fas fa-user-plus"></i>
            </a>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Akun Baru</h2>
            <p class="text-slate-500 text-sm mt-1">Bergabunglah untuk mengelola distribusi.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Nama
                    Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm text-slate-800 placeholder:text-slate-400 font-medium"
                        placeholder="Nama Anda">
                </div>
                @error('name') <span class="text-red-500 text-xs mt-1 ml-1 font-bold block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label
                    class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Username</label>
                <div class="relative">
                    <i class="fas fa-id-card absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                    <input id="username" type="text" name="username" :value="old('username')" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm text-slate-800 placeholder:text-slate-400 font-medium"
                        placeholder="admin123">
                </div>
                @error('username') <span class="text-red-500 text-xs mt-1 ml-1 font-bold block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Email
                    Address</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                    <input id="email" type="email" name="email" :value="old('email')" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm text-slate-800 placeholder:text-slate-400 font-medium"
                        placeholder="nama@email.com">
                </div>
                @error('email') <span class="text-red-500 text-xs mt-1 ml-1 font-bold block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label
                    class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm text-slate-800 placeholder:text-slate-400 font-medium"
                        placeholder="••••••••">
                </div>
                @error('password') <span class="text-red-500 text-xs mt-1 ml-1 font-bold block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Ulangi
                    Password</label>
                <div class="relative">
                    <i class="fas fa-key absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm text-slate-800 placeholder:text-slate-400 font-medium"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 mt-6 text-sm">
                Daftar Akun
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <p class="text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors underline decoration-2 decoration-transparent hover:decoration-indigo-200">
                    Login disini
                </a>
            </p>
        </div>
    </div>

</body>

</html>
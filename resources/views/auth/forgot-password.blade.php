<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Muliadis App</title>

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

<body class="gradient-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div
        class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
    </div>
    <div
        class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
    </div>

    <div class="relative w-full max-w-md bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 sm:p-10 transition-all duration-500"
        x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0">

        <div class="mb-8">
            <a href="{{ route('login') }}"
                class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Login
            </a>
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 text-lg">
                    <i class="fas fa-lock-open"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Reset Password 🔒</h2>
            <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>
        </div>

        @if (session('status'))
        <div
            class="mb-6 p-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Email
                    Address</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400"></i>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-800 placeholder:text-slate-400 font-medium"
                        placeholder="nama@perusahaan.com">
                </div>
                @error('email') <span class="text-red-500 text-xs mt-1 ml-1 font-bold">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-1">
                Kirim Link Reset
            </button>
        </form>
    </div>

</body>

</html>
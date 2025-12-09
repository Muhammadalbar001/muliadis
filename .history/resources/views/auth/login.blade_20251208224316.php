<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Muliadis App</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    </style>
</head>

<body class="bg-white">

    <div class="flex min-h-screen">

        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 lg:px-24 py-12 relative z-10 bg-white">

            <div class="mb-10 lg:hidden">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
                        M</div>
                    <span class="font-bold text-lg text-slate-800">Muliadis</span>
                </div>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali! 👋</h2>
                <p class="text-slate-500 mt-2 text-sm">Silakan masuk untuk mengakses dashboard distribusi.</p>
            </div>

            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-100">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="nama@perusahaan.com">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lupa Password?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="••••••••">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-600">Ingat Saya</label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Masuk ke Dashboard
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Daftar sekarang</a>
            </p>

            <div class="mt-12 pt-6 border-t border-slate-100 text-center">
                <p class="text-[10px] text-slate-400">&copy; {{ date('Y') }} PT. Mulia Anugerah Distribusindo</p>
            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 relative bg-indigo-900">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop"
                alt="Warehouse" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">

            <div class="absolute inset-0 flex flex-col justify-between p-20 z-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl tracking-wide">Muliadis App</span>
                </div>

                <div class="text-white max-w-lg">
                    <h3 class="text-4xl font-bold leading-tight mb-4">Sistem Manajemen Distribusi Terpadu.</h3>
                    <p class="text-indigo-200 text-lg leading-relaxed">
                        Kelola stok, pantau sales, dan analisa keuangan perusahaan dengan lebih efisien dan akurat.
                    </p>
                </div>

                <div class="flex gap-2">
                    <div class="w-12 h-1.5 bg-white rounded-full"></div>
                    <div class="w-2 h-1.5 bg-white/30 rounded-full"></div>
                    <div class="w-2 h-1.5 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
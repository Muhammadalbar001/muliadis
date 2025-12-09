<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Muliadis App</title>

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

        <div class="hidden lg:block lg:w-1/2 relative bg-indigo-900">
            <img src="https://images.unsplash.com/photo-1616401784845-180882ba9ba8?q=80&w=2070&auto=format&fit=crop"
                alt="Distribution" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">

            <div class="absolute inset-0 flex flex-col justify-between p-20 z-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
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

        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 lg:px-24 py-12 bg-white">

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
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="nama@perusahaan.com">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="Minimal 8 karakter">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
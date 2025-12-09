<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Muliadis App</title>

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

            <div class="mb-10">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Login
                </a>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lupa Password? 🔒</h2>
                <p class="text-slate-500 mt-3 text-sm leading-relaxed">
                    Jangan khawatir. Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk
                    mengatur ulang kata sandi Anda.
                </p>
            </div>

            @if (session('status'))
            <div
                class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-xl border border-green-100 flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        placeholder="nama@perusahaan.com">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kirim Link Reset Password
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-6 border-t border-slate-100 text-center">
                <p class="text-[10px] text-slate-400">&copy; {{ date('Y') }} PT. Mulia Anugerah Distribusindo</p>
            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?q=80&w=2070&auto=format&fit=crop"
                alt="Security" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">

            <div class="absolute inset-0 flex flex-col justify-between p-20 z-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/20">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="text-white font-bold text-xl tracking-wide">Muliadis Security</span>
                </div>

                <div class="text-white max-w-lg">
                    <h3 class="text-3xl font-bold leading-tight mb-4">Keamanan Akun Anda Prioritas Kami.</h3>
                    <p class="text-slate-300 text-base leading-relaxed">
                        Kami menggunakan enkripsi standar industri untuk melindungi data dan informasi akun Anda.
                        Pastikan selalu menggunakan password yang kuat.
                    </p>
                </div>

                <div class="flex gap-2">
                    <div class="w-2 h-1.5 bg-white/30 rounded-full"></div>
                    <div class="w-2 h-1.5 bg-white/30 rounded-full"></div>
                    <div class="w-12 h-1.5 bg-white rounded-full"></div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
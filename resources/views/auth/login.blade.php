<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Portal - PT. Mulia Anugerah Distribusindo</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .corporate-bg {
        background-color: #0f172a;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231e293b' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    </style>
</head>

<body class="corporate-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden"
        x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show"
        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">

        <div class="bg-slate-50 border-b border-slate-100 p-8 text-center">
            <div
                class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-900 text-white text-2xl shadow-md mb-4">
                <i class="fas fa-building"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">PT. Mulia Anugerah Distribusindo</h2>
            <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold mt-1">Distribution Management
                System</p>
        </div>

        <div class="p-8 pt-6">

            @if (session('status'))
            <div
                class="mb-5 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username / ID
                        Pegawai</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        <input id="username" type="text" name="username" :value="old('username')" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                            placeholder="Masukkan username Anda">
                    </div>
                    @error('username') <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" type="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                            placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 text-blue-900 border-gray-300 rounded focus:ring-blue-900">
                        <label for="remember_me" class="ml-2 block text-xs text-slate-600">Ingat Saya</label>
                    </div>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-semibold text-blue-700 hover:text-blue-900 transition-colors">Lupa
                        Password?</a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-2.5 px-4 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all transform active:scale-95 text-sm flex justify-center items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk Portal
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Belum memiliki akses?
                    <a href="{{ route('register') }}"
                        class="font-bold text-blue-700 hover:text-blue-900 transition-colors">Daftar Akun</a>
                </p>
            </div>
        </div>

        <div class="bg-slate-50 p-3 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400">&copy; {{ date('Y') }} Internal System • Secure Connection</p>
        </div>
    </div>

</body>

</html>
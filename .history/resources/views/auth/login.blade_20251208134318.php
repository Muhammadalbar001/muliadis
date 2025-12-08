<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PT. Mulia Anugerah Distribusindo</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    </style>
</head>

<body class="antialiased bg-white">

    <div class="min-h-screen flex">

        <div class="hidden lg:flex w-1/2 relative bg-indigo-900 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
                class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay" alt="Warehouse">

            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 to-purple-900/80"></div>

            <div class="relative z-10 w-full flex flex-col justify-center px-12 text-white">
                <div class="mb-6">
                    <div
                        class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 mb-6">
                        <i class="fas fa-truck-fast text-3xl text-white"></i>
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-tight mb-2">Sistem Informasi <br>Distribusi Terpadu</h1>
                    <p class="text-indigo-200 text-lg">PT. Mulia Anugerah Distribusindo</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center border border-indigo-400/30">
                            <i class="fas fa-chart-line text-indigo-300"></i>
                        </div>
                        <div>
                            <h3 class="font-bold">Real-time Dashboard</h3>
                            <p class="text-xs text-indigo-300">Pantau omzet dan stok secara langsung.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center border border-indigo-400/30">
                            <i class="fas fa-user-graduate text-indigo-300"></i>
                        </div>
                        <div>
                            <h3 class="font-bold">Analisa Kinerja Sales</h3>
                            <p class="text-xs text-indigo-300">Evaluasi target IMS dan OA otomatis.</p>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-10 left-12 text-xs text-indigo-400">
                    &copy; {{ date('Y') }} Divisi IT & Pengembangan Sistem.
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md space-y-8">

                <div class="lg:hidden text-center mb-8">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-truck text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">PT. Mulia Anugerah</h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-extrabold text-gray-900">Selamat Datang 👋</h2>
                    <p class="mt-2 text-sm text-gray-500">Silakan login untuk mengakses dashboard eksekutif.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Perusahaan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username"
                                class="pl-10 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3"
                                placeholder="nama@mulia-anugerah.co.id">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                autocomplete="current-password"
                                class="pl-10 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                @click="show = !show">
                                <i class="far text-gray-400" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                        </div>
                        @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500">
                                Lupa Password?
                            </a>
                        </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-lg hover:shadow-indigo-500/30">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt group-hover:text-indigo-100 transition-colors"></i>
                            </span>
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400">
                        Masalah saat login? Hubungi <a href="#"
                            class="font-medium text-indigo-600 hover:text-indigo-500">Tim IT Support</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
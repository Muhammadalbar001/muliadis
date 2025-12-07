<x-guest-layout>
    <div class="flex min-h-screen">

        <div
            class="hidden md:flex md:w-1/2 lg:w-5/12 bg-slate-900 relative overflow-hidden flex-col justify-between p-12 text-white">

            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop"
                    alt="Warehouse Background" class="w-full h-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 to-slate-900/90 mix-blend-multiply">
                </div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <i class="fas fa-cube text-xl text-white"></i>
                    </div>
                    <span class="text-xl font-bold tracking-wide">MULIADIS</span>
                </div>
            </div>

            <div class="relative z-10 mb-10">
                <h1 class="text-4xl font-bold leading-tight mb-4">Sistem Distribusi & Manajemen Stok</h1>
                <p class="text-slate-300 text-lg font-light leading-relaxed">
                    Kelola ribuan SKU, pantau penjualan harian, dan kontrol piutang dengan presisi tinggi dalam satu
                    dashboard terintegrasi.
                </p>
            </div>

            <div class="relative z-10 text-xs text-slate-500 font-medium">
                &copy; {{ date('Y') }} PT. Mulia Anugerah Distribusindo. All rights reserved.
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-7/12 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md space-y-8">

                <div class="md:hidden flex justify-center mb-6">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-cube text-2xl"></i>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                    <p class="mt-2 text-sm text-slate-500">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-slate-700">Email Perusahaan</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-gray-400 text-slate-800"
                                placeholder="nama@perusahaan.com">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-semibold text-slate-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                Lupa sandi?
                            </a>
                            @endif
                        </div>
                        <div class="relative" x-data="{ show: false }">
                            <input id="password" type="password" :type="show ? 'text' : 'password'" name="password"
                                required autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-gray-400 text-slate-800"
                                placeholder="••••••••">

                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>

                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer"
                                name="remember">
                            <span class="ms-2 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Ingat
                                saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Masuk Dashboard
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-xs text-slate-400">
                            Mengalami kendala akses? <a href="#"
                                class="text-indigo-600 hover:text-indigo-500 font-medium">Hubungi IT Support</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
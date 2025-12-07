<x-guest-layout>
    <div class="flex min-h-screen">
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-16 lg:px-24 bg-white relative">

            <div class="lg:hidden absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

            <div class="mb-10 text-left">
                <div class="flex items-center gap-2 mb-6 text-indigo-600">
                    <x-application-logo class="w-10 h-10 fill-current" />
                    <span class="font-bold text-2xl tracking-tight text-gray-900">Muliadis</span>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
                <p class="text-gray-500">Silakan masukkan akun Anda untuk melanjutkan.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email"
                            class="block mt-1 w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-200 py-3"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="nama@perusahaan.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password"
                            class="block mt-1 w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-200 py-3"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition"
                        href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                    @endif
                </div>

                <div>
                    <button
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.01]">
                        MASUK SEKARANG
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} PT. Mulia Anugerah Distribusindo.
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-blue-900 to-slate-900 opacity-90 z-10">
            </div>

            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
                alt="Warehouse Background"
                class="absolute inset-0 w-full h-full object-cover grayscale mix-blend-overlay">

            <div class="relative z-20 text-white p-12 max-w-lg text-center">
                <div class="mb-6 flex justify-center">
                    <div class="bg-white/10 p-4 rounded-full backdrop-blur-sm border border-white/20">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 01-2-2h-2a2 2 0 01-2 2" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold mb-4 tracking-tight">Sistem Manajemen Distribusi</h3>
                <p class="text-indigo-100 text-lg leading-relaxed font-light">
                    Kelola Penjualan, Retur, AR, dan Stok Gudang dalam satu platform terintegrasi untuk efisiensi
                    maksimal.
                </p>

                <div class="mt-10 grid grid-cols-3 gap-4 text-center border-t border-white/10 pt-8">
                    <div>
                        <span class="block text-2xl font-bold">Fast</span>
                        <span class="text-xs text-indigo-200 uppercase tracking-wider">Import</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold">Secure</span>
                        <span class="text-xs text-indigo-200 uppercase tracking-wider">Data</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold">Realtime</span>
                        <span class="text-xs text-indigo-200 uppercase tracking-wider">Report</span>
                    </div>
                </div>
            </div>

            <div
                class="absolute -bottom-24 -left-24 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
        </div>
    </div>
</x-guest-layout>
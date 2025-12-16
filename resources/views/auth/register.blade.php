<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Akun - PT. Mulia Anugerah Distribusindo</title>

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

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden my-4"
        x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show"
        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" style="display: none;">

        <div class="bg-blue-900 p-6 text-white flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">Registrasi Pegawai Baru</h2>
                <p class="text-blue-200 text-xs mt-0.5">Silakan isi data diri Anda dengan lengkap.</p>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
        </div>

        <div class="p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                        placeholder="Nama sesuai KTP">
                    @error('name') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                class="fas fa-id-badge"></i></span>
                        <input id="username" type="text" name="username" :value="old('username')" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                            placeholder="username123">
                    </div>
                    @error('username') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Email Kantor</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                class="fas fa-envelope"></i></span>
                        <input id="email" type="email" name="email" :value="old('email')" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                            placeholder="nama@perusahaan.com">
                    </div>
                    @error('email') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                    class="fas fa-lock"></i></span>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                                placeholder="••••••••">
                        </div>
                        @error('password') <span
                            class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Ulangi Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                    class="fas fa-check-circle"></i></span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all text-sm font-medium"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg shadow-md transition-all hover:-translate-y-0.5 text-sm flex justify-center items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Daftarkan Akun
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}"
                        class="font-bold text-blue-700 hover:text-blue-900 transition-colors">Login disini</a>
                </p>
            </div>
        </div>
    </div>

</body>

</html>
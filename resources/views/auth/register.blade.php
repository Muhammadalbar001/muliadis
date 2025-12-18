<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar | PT MULIA ANUGERAH DISTRIBUSINDO</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
        background: linear-gradient(rgba(10, 10, 10, 0.75), rgba(10, 10, 10, 0.75)), url('/images/bg-login.jpg') center/cover fixed;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .input-glass {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: 0.3s;
    }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg">
        <div class="glass rounded-[2.5rem] p-10 shadow-2xl">
            <h2 class="text-xl font-extrabold text-white uppercase tracking-tight mb-8 text-center">Registrasi Pegawai
            </h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama
                        Lengkap</label>
                    <input type="text" name="name" required
                        class="input-glass w-full px-5 py-3 rounded-2xl text-sm outline-none" placeholder="Sesuai KTP">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Username</label>
                        <input type="text" name="username" required
                            class="input-glass w-full px-5 py-3 rounded-2xl text-sm outline-none"
                            placeholder="user_mulia">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email
                            Kantor</label>
                        <input type="email" name="email" required
                            class="input-glass w-full px-5 py-3 rounded-2xl text-sm outline-none"
                            placeholder="staff@mulia.com">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="input-glass w-full px-5 py-3 rounded-2xl text-sm outline-none" placeholder="••••••••">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Ulangi
                        Password</label>
                    <input type="password" name="password_confirmation" required
                        class="input-glass w-full px-5 py-3 rounded-2xl text-sm outline-none" placeholder="••••••••">
                </div>
                <button type="submit"
                    class="w-full mt-4 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-900/40">DAFTARKAN
                    AKUN</button>
            </form>
            <div class="mt-8 text-center">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sudah Punya Akun? <a
                        href="{{ route('login') }}"
                        class="text-blue-400 hover:text-white ml-1 underline underline-offset-4">Masuk</a></p>
            </div>
        </div>
    </div>
</body>

</html>
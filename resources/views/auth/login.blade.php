<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | PT MULIA ANUGERAH DISTRIBUSINDO</title>
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

    .input-glass:focus {
        border-color: #3b82f6;
        background: rgba(0, 0, 0, 0.4);
    }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-xl mx-auto mb-4"><i
                    class="fas fa-truck-fast text-white text-2xl"></i></div>
            <h1 class="text-xl font-black text-white tracking-tighter uppercase leading-none">PT MULIA ANUGERAH</h1>
            <p class="text-[9px] font-bold text-blue-400 tracking-[0.3em] uppercase mt-2">Distribution System</p>
        </div>
        <div class="glass rounded-[2.5rem] p-10 shadow-2xl">
            <h2 class="text-xl font-extrabold text-white uppercase tracking-tight mb-8 text-center">Portal Login</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                    <input type="text" name="username" required
                        class="input-glass w-full px-5 py-3.5 rounded-2xl text-sm outline-none"
                        placeholder="Username Anda">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="input-glass w-full px-5 py-3.5 rounded-2xl text-sm outline-none" placeholder="••••••••">
                </div>
                <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all active:scale-95 shadow-lg shadow-blue-900/40">MASUK
                    SEKARANG</button>
            </form>
            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Belum Punya Akun? <a
                        href="{{ route('register') }}"
                        class="text-blue-400 hover:text-white transition-colors ml-1 underline decoration-2 underline-offset-4">Daftar
                        Disini</a></p>
            </div>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password | PT MULIA ANUGERAH DISTRIBUSINDO</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
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
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="glass rounded-[2.5rem] p-10 shadow-2xl text-center">
            <h2 class="text-xl font-extrabold text-white uppercase mb-4">Reset Password</h2>
            <p class="text-slate-400 text-xs mb-8 uppercase tracking-widest leading-relaxed">Masukkan email Anda untuk
                menerima tautan pemulihan kata sandi.</p>
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <input type="email" name="email" required
                    class="bg-black/20 border border-white/10 w-full px-5 py-4 rounded-2xl text-sm text-white outline-none focus:border-blue-500"
                    placeholder="Email Anda">
                <button type="submit"
                    class="w-full py-4 bg-white text-black rounded-2xl font-black text-xs uppercase tracking-widest transition-all active:scale-95">Kirim
                    Tautan</button>
            </form>
        </div>
    </div>
</body>

</html>
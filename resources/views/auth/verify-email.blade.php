<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email | PT MULIA ANUGERAH DISTRIBUSINDO</title>
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
            <div
                class="w-16 h-16 bg-blue-600/20 text-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-envelope-open-text text-2xl"></i>
            </div>
            <h2 class="text-xl font-extrabold text-white uppercase tracking-tight mb-4">Verifikasi Email</h2>
            <p class="text-slate-400 text-xs leading-relaxed mb-8 uppercase tracking-widest">
                Klik tautan yang kami kirimkan ke email Anda. Jika tidak menerima email, kami akan mengirimkan ulang.
            </p>

            @if (session('status') == 'verification-link-sent')
            <div
                class="mb-6 text-[10px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-500/10 p-3 rounded-xl">
                Tautan baru telah dikirim!</div>
            @endif

            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Kirim
                        Ulang Email</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Log
                        Out</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
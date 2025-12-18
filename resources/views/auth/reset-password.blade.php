<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setel Ulang Password | PT MULIA ANUGERAH DISTRIBUSINDO</title>
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
    }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="glass rounded-[2.5rem] p-10 shadow-2xl">
            <div
                class="w-16 h-16 bg-blue-600/20 text-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <h2 class="text-xl font-extrabold text-white uppercase tracking-tight mb-8 text-center">Setel Password Baru
            </h2>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                        class="input-glass w-full px-5 py-3.5 rounded-2xl text-sm opacity-50 cursor-not-allowed">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password
                        Baru</label>
                    <input type="password" name="password" required
                        class="input-glass w-full px-5 py-3.5 rounded-2xl text-sm outline-none focus:border-blue-500"
                        placeholder="••••••••">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Ulangi
                        Password</label>
                    <input type="password" name="password_confirmation" required
                        class="input-glass w-full px-5 py-3.5 rounded-2xl text-sm outline-none focus:border-blue-500"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-900/40">SIMPAN
                    PASSWORD</button>
            </form>
        </div>
    </div>
</body>

</html>
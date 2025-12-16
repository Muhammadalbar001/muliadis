<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Tidak Ditemukan - Muliadis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .gradient-bg {
        background: linear-gradient(-45deg, #1e1b4b, #312e81, #4338ca, #3730a3);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-6 text-white text-center">

    <div class="max-w-xl w-full bg-white/10 backdrop-blur-md rounded-3xl p-10 border border-white/20 shadow-2xl">
        <h1
            class="text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-purple-300 mb-4">
            404</h1>
        <h2 class="text-2xl font-bold mb-4">Waduh! Halaman Hilang.</h2>
        <p class="text-indigo-200 mb-8">
            Sepertinya halaman yang Anda cari tersesat di gudang atau sudah dipindahkan.
        </p>

        <a href="{{ url('/dashboard') }}"
            class="inline-flex items-center px-6 py-3 text-sm font-bold text-indigo-900 bg-white rounded-xl hover:bg-slate-100 transition-all shadow-lg hover:-translate-y-1">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

</body>

</html>
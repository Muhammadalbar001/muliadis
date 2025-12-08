<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Muliadis App') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    [x-cloak] {
        display: none !important;
    }

    /* Scrollbar Halus */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Loading Bar di Atas */
    .loading-bar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: #4f46e5;
        z-index: 9999;
        width: 100%;
        animation: load 1s infinite;
    }

    @keyframes load {
        0% {
            width: 0;
            margin-left: 0;
        }

        50% {
            width: 50%;
            margin-left: 25%;
        }

        100% {
            width: 100%;
            margin-left: 100%;
        }
    }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-600">

    <div wire:loading class="loading-bar"></div>

    <div class="min-h-screen flex overflow-hidden" x-data="{ sidebarOpen: true }">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">

            @include('layouts.navigation')

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 relative">
                @if (isset($header))
                <header class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                            {{ $header }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">Sistem Informasi Distribusi & Kinerja Sales</p>
                    </div>
                </header>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('livewire:init', () => {
        // Notifikasi Toast
        Livewire.on('show-toast', (event) => {
            let data = event[0] || event;
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: data.type,
                title: data.title,
                text: data.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#ffffff',
                iconColor: data.type === 'success' ? '#10b981' : '#ef4444',
                customClass: {
                    popup: 'shadow-xl border border-slate-100 rounded-xl'
                }
            });
        });

        // Konfirmasi Hapus
        Livewire.on('confirm-delete', (event) => {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(event.method, {
                        id: event.id
                    });
                }
            });
        });
    });
    </script>
</body>

</html>
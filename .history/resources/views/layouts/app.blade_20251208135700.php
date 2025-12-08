<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Muliadis App') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

    /* Custom Scrollbar */
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
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-600">

    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300"
            :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">

            @include('layouts.navigation')

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                @if (isset($header))
                <header class="mb-6">
                    <h2 class="text-xl font-bold text-slate-800 border-b border-slate-200 pb-2">
                        {{ $header }}
                    </h2>
                </header>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('livewire:init', () => {
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
                timerProgressBar: true
            });
        });
    });
    </script>
</body>

</html>
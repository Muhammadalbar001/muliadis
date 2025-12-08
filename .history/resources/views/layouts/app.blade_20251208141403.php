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
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-600">

    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 lg:pl-64">

            @include('layouts.navigation')

            <main class="flex-1 p-4 lg:p-8 overflow-y-auto">
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
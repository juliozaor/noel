<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Select2 -->
    {{--      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/content3.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @livewireStyles

</head>

<body class="font-sans antialiased">
    {{--  <x-banner /> --}}

    <div class="min-h-screen bg-gray-100">
        {{-- @livewire('navigation-menu') --}}
        @include('layouts.sidebar')
        @include('layouts.navbar')
        {{--  @livewire('navbar') --}}

        <!-- Page Heading -->


        <!-- Page Content -->
        <main class="contenido">
            @if (isset($header))
                <header>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            {{ $slot }}
        </main>
    </div>

    {{--  @stack('modals') --}}
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @stack('js')
    <script>
        Livewire.on('alert', (message, icon) => {
            Swal.fire({
                position: 'center',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 1500
            })
        });
    </script>
</body>

</html>

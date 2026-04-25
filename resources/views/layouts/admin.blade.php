<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.tailwindcss.css">

    <style>
        .nav-icon { color: #4a7c44 !important; }
        .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: rgba(255, 255, 255, 0.5) !important;
            color: #1a2e18 !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    
    <div class="wrapper min-h-screen">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper sm:ml-64 transition-all duration-300 pt-16 min-h-screen flex flex-col">
            <!-- Content Header (Page header) -->
            <div class="content-header px-6 py-6">
                <div class="container-fluid">
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>
            </div>

            <!-- Main content -->
            <section class="content px-6 pb-12 flex-grow">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </section>

            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.tailwindcss.js"></script>

    <!-- Flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    @stack('scripts')
</body>
</html>

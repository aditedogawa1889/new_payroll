<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    
    <div class="wrapper min-h-screen flex flex-col">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper sm:ml-64 flex-grow transition-all duration-300">
            <!-- Content Header (Page header) -->
            <div class="content-header px-4 py-4">
                <div class="container-fluid">
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>
            </div>

            <!-- Main content -->
            <section class="content px-4 pb-12">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </section>
        </div>

        @include('layouts.partials.footer')
    </div>

    <!-- Flowbite or other JS if needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="hold-transition login-page bg-gray-200">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="login-box w-full max-w-md">
            <div class="login-logo text-center mb-6">
                <a href="/" class="text-3xl font-light text-gray-800">
                    <span class="font-bold">Payroll</span>System
                </a>
            </div>
            
            <div class="card bg-white shadow-xl rounded-lg overflow-hidden border-t-4 border-adminlte-primary">
                <div class="card-body p-8">
                    <p class="login-box-msg text-center text-gray-600 mb-6 font-medium">Sign in to start your session</p>
                    {{ $slot }}
                </div>
            </div>
            
            <div class="text-center mt-8 text-gray-500 text-sm">
                &copy; {{ date('Y') }} <a href="#" class="text-adminlte-primary hover:underline font-semibold">AdminLTE.io</a>. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>


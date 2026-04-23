<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="text-slate-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center mb-4 transform hover:scale-110 transition-transform">
                        <span class="text-white font-bold text-3xl">P</span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Payroll System</h1>
                    <p class="text-slate-500 text-sm">Sign in to start your session</p>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl border border-gray-200 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-gray-400 text-xs">© {{ date('Y') }} All Rights Reserved.</p>
        </div>
    </body>
</html>


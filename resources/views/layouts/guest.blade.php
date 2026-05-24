<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dormitory Management') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --navy: #0f172a;
            --royal: #1e40af;
            --maroon: #7c2d12;
            --gold: #fbbf24;
        }

        body {
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a8a 100%);
            min-height: 100vh;
        }

        .glass-effect {
            background: rgba(248, 250, 252, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-gold {
            background: linear-gradient(135deg, #1e40af 0%, #fbbf24 100%);
        }

        .shadow-lg-custom {
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.3);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
        }

        .transition-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>
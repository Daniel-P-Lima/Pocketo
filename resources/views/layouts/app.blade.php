<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Financas') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen pb-20">
        @if(isset($header))
            <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center">
                @if(isset($backUrl))
                    <a href="{{ $backUrl }}" class="mr-3 p-1 -ml-1">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif
                <h1 class="text-lg font-semibold">{{ $header }}</h1>
            </header>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

    <x-bottom-nav />

    @stack('scripts')
</body>
</html>

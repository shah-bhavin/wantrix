<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $page = \App\Support\Navigation::current();
    @endphp
    <title>
        {{ $page['title'] ?? config('app.name') }}
        |
        {{ config('app.name') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-100">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <x-vendor.sidebar />

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col">
            {{-- Navbar --}}
            <x-vendor.navbar />

            {{-- Page Content --}}
            <main class="flex-1 p-6">
                
                
                {{ $slot }}
            </main>
        </div>

    </div>
    <x-flash-message />
    @livewireScripts
</body>

</html>
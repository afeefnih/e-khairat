{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Khairat') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <script>
            document.addEventListener('livewire:load', function () {
                Livewire.on('darkModeChanged', (darkMode) => {
                    if (darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                });
            });
        </script>
    </head>
    <body x-cloak x-data="{darkMode: $persist(false)}" :class="{'dark': darkMode === true }" class="antialiased">

        <x-banner />

        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-indigo-950">
            @livewire('navigation-menu')
            <div class="h-5"></div>
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow dark:bg-gray-800 border-b border-indigo-100 dark:border-indigo-900/40 mt-16">
                    <div class="max-w-screen-xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @else
                <!-- Add padding when no header to prevent content from hiding under navbar -->
                <div class="pt-16"></div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('modals')

        @livewireScripts
    </body>
</html>

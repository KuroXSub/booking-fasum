<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title ?? config('app.name') }} | Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
    <x-users.partials.navigation />
    
    @if (isset($header))
        <header>
            <div class="max-w-7xl mx-auto pt-6 px-2 sm:px-4 lg:px-6">
                {{ $header }}
            </div>
        </header>
    @endif

    <main class="w-full max-w-7xl mx-auto p-4 sm:p-6">
        {{ $slot }}
        @stack('scripts')
    </main>

    <x-welcome.layouts.footer />
</body>
</html>
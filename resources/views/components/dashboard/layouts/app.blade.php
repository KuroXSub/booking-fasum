<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <title>{{ $title ?? config('app.name') }} | Dashboard</title>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
    <x-dashboard.partials.navigation />
    
    <main class="w-full max-w-7xl mx-auto p-4 sm:p-6">
        {{ $slot }}
        @stack('scripts')
    </main>
</body>
</html>
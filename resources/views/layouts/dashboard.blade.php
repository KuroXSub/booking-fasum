<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }} | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
    <!-- Include Navigation -->
    @include('dashboard.navigation')

    <!-- Main Content -->
    <main class="w-full max-w-7xl mx-auto p-4 sm:p-6">
        @yield('content')
    </main>
</body>
</html>
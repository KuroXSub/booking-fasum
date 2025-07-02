<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>

    @vite('resources/css/app.css')
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800">

    <div class="min-h-screen flex flex-col items-center justify-center text-center px-4">
        <div class="max-w-md">
            <p class="text-9xl font-black text-indigo-400">404</p>

            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                Halaman Tidak Ditemukan
            </h1>

            <p class="mt-4 text-base text-gray-600">
                Maaf, kami tidak dapat menemukan halaman yang Anda cari. Mungkin URL-nya salah ketik atau halamannya telah dipindahkan.
            </p>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="inline-block rounded-md bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>
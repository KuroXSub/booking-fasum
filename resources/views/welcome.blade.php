<x-welcome.layouts.app title="Beranda">
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
                    Sistem Booking Fasilitas Umum Desa
                </h1>
                <p class="mt-2 text-base sm:text-lg md:text-xl text-gray-600 sm:mt-4 max-w-2xl mx-auto">
                    Pinjam fasilitas umum dengan mudah dan transparan
                </p>
                <div class="mt-8 sm:mt-10 flex justify-center">
                    <a href="#facilities"
                       class="inline-flex items-center px-5 sm:px-6 py-3 sm:py-4 border border-transparent text-sm sm:text-base font-medium rounded-lg shadow-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-200 ease-in-out">
                        Lihat Fasilitas Tersedia
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Daftar Fasilitas --}}
    <div id="facilities" class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-8 text-center">Fasilitas Tersedia</h2>

            @php
                $dayNames = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu',
                ];
            @endphp

            @if($facilities->isEmpty())
                <p class="text-center text-gray-600">Belum ada fasilitas yang tersedia.</p>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($facilities as $facility)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden transition hover:shadow-lg">
                            @if($facility->image_path)
                                <img src="{{ $facility->image_path }}" alt="{{ $facility->name }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $facility->name }}</h3>
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($facility->description, 100) }}</p>

                                <div class="mt-4 text-sm text-gray-500 space-y-1">
                                    <p><strong>Jam Buka:</strong> {{ $facility->opening_time }} - {{ $facility->closing_time }}</p>
                                    <p>
                                        <strong>Hari Aktif:</strong>
                                        {{ collect($facility->available_days)->map(fn($d) => $dayNames[$d] ?? $d)->implode(', ') }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-welcome.layouts.app>

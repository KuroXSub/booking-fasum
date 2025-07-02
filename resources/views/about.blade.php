<x-welcome.layouts.app title="Tentang Kami">
    <div class="flex flex-col min-h-screen">
        {{-- Wrapper untuk membuat konten memenuhi layar dan di tengah secara vertikal --}}
        <div class="flex-grow flex flex-col justify-center">
            
            {{-- Section 1: Tentang Proyek & Fitur --}}
            <div class="bg-white">
                {{-- Padding atas dikurangi (pt-4) untuk merapatkan dengan header --}}
                <div class="max-w-7xl mx-auto pt-4 pb-8 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-3xl mx-auto text-center">
                        <h2 class="text-base font-semibold text-indigo-600 tracking-wider uppercase">Proyek Kami</h2>
                        {{-- Ukuran font dan margin dikurangi --}}
                        <p class="mt-1 text-3xl font-extrabold text-gray-900 tracking-tight">
                            Memodernisasi Peminjaman Fasilitas
                        </p>
                        <p class="mt-3 max-w-2xl mx-auto text-base text-gray-500">
                            Sistem ini dibangun untuk menyederhanakan proses peminjaman fasilitas umum, menjadikannya lebih transparan dan mudah diakses.
                        </p>
                    </div>
                    {{-- Margin atas dikurangi (mt-10) --}}
                    <dl class="mt-10 space-y-8 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-x-6 sm:gap-y-10 md:grid-cols-3 lg:gap-x-8">
                        {{-- Fitur 1 --}}
                        <div class="flex">
                            <svg class="flex-shrink-0 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div class="ml-3">
                                {{-- Ukuran font dan margin deskripsi dikurangi --}}
                                <dt class="text-base leading-6 font-medium text-gray-900">Booking Online 24/7</dt>
                                <dd class="mt-1 text-sm text-gray-500">Akses jadwal dan pesan kapan saja tanpa terikat jam kerja.</dd>
                            </div>
                        </div>

                        {{-- Fitur 2 --}}
                        <div class="flex">
                            <svg class="flex-shrink-0 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <div class="ml-3">
                                <dt class="text-base leading-6 font-medium text-gray-900">Prioritas Terkelola</dt>
                                <dd class="mt-1 text-sm text-gray-500">Mengelola prioritas peminjaman untuk institusi atau acara penting.</dd>
                            </div>
                        </div>

                        {{-- Fitur 3 --}}
                        <div class="flex">
                           <svg class="flex-shrink-0 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7" />
                            </svg>
                            <div class="ml-3">
                                <dt class="text-base leading-6 font-medium text-gray-900">Penggunaan Mudah</dt>
                                <dd class="mt-1 text-sm text-gray-500">Antarmuka yang dirancang agar mudah digunakan oleh semua orang.</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Section 2: Tim Kami --}}
            <div class="bg-gray-50">
                {{-- Padding vertikal dikurangi (py-8) --}}
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-3xl mx-auto text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900">Tim hORE</h2>
                        <p class="mt-2 text-base text-gray-500">Mahasiswa Universitas Amikom Purwokerto Semester 6 yang lagi pusing-pusingnya mikirin tugas.</p>
                    </div>
                    
                    {{-- Margin atas dikurangi (mt-10) --}}
                    <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">

                        {{-- Kartu Anggota 1: Hakim --}}
                        <div class="text-center">
                            <div class="flex justify-center">
                                {{-- Ukuran gambar dikurangi (h-32 w-32) --}}
                                <img class="object-cover h-32 w-32 rounded-full shadow-lg" src="{{ asset('images/fotohakim.jpg') }}" alt="Foto profil Hakim">
                            </div>
                            {{-- Margin atas dikurangi (mt-4) --}}
                            <div class="mt-4">
                                {{-- Ukuran font dikurangi --}}
                                <h3 class="text-lg font-medium text-gray-900">Muhamad Saroful Hakim</h3>
                                <p class="mt-1 text-sm font-semibold text-indigo-600">22SA31A001</p>
                            </div>
                        </div>

                        {{-- Kartu Anggota 2: Bani --}}
                        <div class="text-center">
                            <div class="flex justify-center">
                                <img class="object-cover h-32 w-32 rounded-full shadow-lg" src="{{ asset('images/fotobani.jpg') }}" alt="Foto profil Bani">
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Sha'Bani Septriansyah</h3>
                                <p class="mt-1 text-sm font-semibold text-indigo-600">22SA31A006</p>
                            </div>
                        </div>

                        {{-- Kartu Anggota 3: Ulin --}}
                        <div class="text-center">
                            <div class="flex justify-center">
                                {{-- Pastikan ada foto fotoulin.jpg di folder images --}}
                                <img class="object-cover h-32 w-32 rounded-full shadow-lg" src="{{ asset('images/fotoulin.jpg') }}" alt="Foto profil Ulin">
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Qurrota A'yun Liulinuha</h3>
                                <p class="mt-1 text-sm font-semibold text-indigo-600">22SA31A016</p>
                            </div>
                        </div>

                        {{-- Kartu Anggota 4: Adi --}}
                        <div class="text-center">
                            <div class="flex justify-center">
                                {{-- Path gambar diperbaiki (tanpa spasi) --}}
                                <img class="object-cover h-32 w-32 rounded-full shadow-lg" src="{{ asset('images/foto adi.png') }}" alt="Foto profil Adi">
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Adi Puja Pangestu</h3>
                                <p class="mt-1 text-sm font-semibold text-indigo-600">22SA31A029</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-welcome.layouts.app>

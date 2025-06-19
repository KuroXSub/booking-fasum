<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>README - Booking Fasilitas Umum</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            line-height: 1.6;
            color: #24292e;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        h1, h2, h3 {
            border-bottom: 1px solid #eaecef;
            padding-bottom: 0.3em;
            margin-top: 24px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        h1 {
            font-size: 2.2em;
        }
        h2 {
            font-size: 1.8em;
        }
        h3 {
            font-size: 1.4em;
        }
        p {
            margin-bottom: 16px;
        }
        ul, ol {
            padding-left: 2em;
            margin-bottom: 16px;
        }
        li {
            margin-bottom: 8px;
        }
        code {
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
            padding: 0.2em 0.4em;
            margin: 0;
            font-size: 85%;
            background-color: rgba(27,31,35,0.05);
            border-radius: 3px;
        }
        pre {
            padding: 16px;
            overflow: auto;
            font-size: 85%;
            line-height: 1.45;
            background-color: #f6f8fa;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        pre code {
            padding: 0;
            margin: 0;
            font-size: 100%;
            background-color: transparent;
            border: 0;
        }
        strong {
            font-weight: 600;
        }
        a {
            color: #0366d6;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Website Booking Fasilitas Umum (Booking Fasum)</h1>
        <p>Website ini adalah platform digital yang dirancang untuk memudahkan masyarakat dalam proses pemesanan atau peminjaman fasilitas umum yang tersedia. Sistem ini memungkinkan pengguna untuk melihat ketersediaan jadwal secara <em>real-time</em> dan melakukan booking secara mandiri.</p>
        <p>Proyek ini dibangun menggunakan <strong>Laravel 12</strong> dan panel admin modern <strong>Filament v3</strong>.</p>

        <h2>Fitur Utama</h2>
        <h3>Manajemen Booking (Sisi Pengguna)</h3>
        <ul>
            <li><strong>Pemesanan Interaktif</strong>: Form pemesanan canggih dengan pengecekan ketersediaan jadwal secara <em>real-time</em> tanpa perlu me-refresh halaman.</li>
            <li><strong>Riwayat Pemesanan</strong>: Pengguna dapat melihat seluruh riwayat pemesanan yang pernah mereka buat beserta statusnya (Menunggu, Disetujui, Ditolak).</li>
            <li><strong>Manajemen Mandiri</strong>: Pengguna dapat mengubah detail atau membatalkan pemesanan mereka sendiri selama statusnya masih "Menunggu Persetujuan".</li>
        </ul>
        <h3>Panel Admin Modern (Filament)</h3>
        <ul>
            <li><strong>Dashboard Overview</strong>: Widget informatif yang menampilkan statistik total pemesanan, jumlah yang disetujui, dan yang masih menunggu.</li>
            <li><strong>Manajemen Terpusat</strong>: Seluruh data master seperti fasilitas, pengguna, kategori, dan jadwal khusus dikelola dalam satu antarmuka yang intuitif.</li>
            <li><strong>Sistem Notifikasi</strong>: Admin menerima notifikasi untuk setiap pemesanan baru yang masuk.</li>
        </ul>
        <h3>Autentikasi dan Pengaturan (Livewire)</h3>
        <ul>
            <li><strong>Desain Antarmuka Baru</strong>: Halaman Login, Register, dan Lupa Password telah didesain ulang menggunakan komponen Livewire agar lebih responsif dan modern.</li>
            <li><strong>Social Login</strong>: Kemudahan login untuk pengguna melalui akun Google mereka, didukung oleh Laravel Socialite.</li>
            <li><strong>Pengaturan Akun Lengkap</strong>: Pengguna dapat mengelola profil, mengubah password, dan memilih tampilan tema (terang/gelap) melalui halaman pengaturan.</li>
        </ul>

        <h2>Dashboard Admin Panel</h2>
        <h3>Overview Widget</h3>
        <ul>
            <li><strong>Informasi Umum</strong>: Menampilkan jumlah total fasilitas yang terdaftar dan total pengguna.</li>
            <li><strong>Status Pemesanan</strong>: Ringkasan jumlah pemesanan dengan status Menunggu, Disetujui, dan Ditolak.</li>
            <li><strong>Aktivitas Terbaru</strong>: Daftar pemesanan terbaru yang masuk untuk pemantauan cepat.</li>
        </ul>
        <h3>Manajemen Data</h3>
        <ul>
            <li><strong>Kelola Booking</strong>: Admin dapat melihat semua pemesanan, melakukan persetujuan, penolakan, atau mengubah detail pemesanan dari semua pengguna.</li>
            <li><strong>Kelola Fasilitas</strong>: CRUD untuk data fasilitas, termasuk mengatur hari dan jam operasional, serta menonaktifkan fasilitas untuk sementara.</li>
            <li><strong>Kelola Tanggal Khusus</strong>: Admin dapat menetapkan hari libur atau jam operasional khusus untuk fasilitas tertentu (misalnya untuk hari raya atau perbaikan).</li>
        </ul>

        <h2>Teknologi yang Digunakan</h2>
        <ul>
            <li><strong>Framework</strong>: Laravel 12</li>
            <li><strong>Admin Panel</strong>: Filament v3</li>
            <li><strong>Database</strong>: MySQL</li>
            <li><strong>Frontend</strong>: Tailwind CSS, Alpine.js</li>
            <li><strong>UI Interaktif</strong>: Livewire</li>
            <li><strong>Autentikasi</strong>: Laravel Auth + Laravel Socialite (Google)</li>
            <li><strong>Development Tool</strong>: Vite</li>
        </ul>

        <h2>Petunjuk Instalasi</h2>
        <h3>Konfigurasi Awal</h3>
        <ol>
            <li><strong>Clone repository</strong>
                <pre><code>git clone https://github.com/KuroXSub/booking-fasum.git
cd booking-fasum</code></pre>
            </li>
            <li><strong>Install dependencies</strong>
                <pre><code>composer install
npm install</code></pre>
            </li>
            <li><strong>Setup environment</strong>
                <pre><code>cp .env.example .env
php artisan key:generate</code></pre>
            </li>
            <li><strong>Konfigurasi database di file <code>.env</code></strong>
                <pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_booking_fasum
DB_USERNAME=root
DB_PASSWORD=</code></pre>
            </li>
            <li><strong>Konfigurasi Google Login (Opsional)</strong>
                <p>Tambahkan Client ID dan Client Secret Anda di file <code>.env</code>.</p>
                <pre><code>GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback</code></pre>
            </li>
        </ol>

        <h3>Menjalankan Aplikasi</h3>
        <ol>
            <li><strong>Migrasi database</strong>
                <pre><code>php artisan migrate --seed</code></pre>
            </li>
            <li><strong>Kompilasi assets</strong>
                <pre><code>npm run build</code></pre>
                <p>Atau untuk development:</p>
                <pre><code>npm run dev</code></pre>
            </li>
            <li><strong>Jalankan server lokal</strong>
                <pre><code>php artisan serve</code></pre>
            </li>
        </ol>

        <h3>Akses Admin Panel</h3>
        <ol>
            <li><strong>Buat user admin</strong>
                <pre><code>php artisan make:filament-user</code></pre>
                <p>Ikuti instruksi untuk membuat akun admin pertama Anda.</p>
            </li>
            <li><strong>Akses Admin Panel</strong>
                <p>Buka <code>http://127.0.0.1:8000/admin</code> di browser Anda.</p>
            </li>
        </ol>

        <h2>Panduan Penggunaan</h2>
        <h3>Untuk Admin</h3>
        <ul>
            <li>Pantau semua aktivitas pemesanan dari halaman dashboard.</li>
            <li>Kelola status pemesanan (setujui/tolak) melalui menu "Bookings".</li>
            <li>Kelola data master seperti fasilitas, pengguna, dan kategori melalui menu navigasi yang tersedia.</li>
        </ul>
        <h3>Untuk Pengguna</h3>
        <ul>
            <li>Login menggunakan email atau akun Google.</li>
            <li>Buat pemesanan baru melalui form interaktif yang akan menampilkan ketersediaan jadwal secara langsung.</li>
            <li>Lihat status dan riwayat pemesanan Anda di halaman "My Bookings".</li>
            <li>Ubah atau batalkan pemesanan jika diperlukan.</li>
        </ul>
    </div>
</body>
</html>